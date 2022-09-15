<?php

namespace App\Http\Controllers\Index;

use App\Entity\Account;
use App\Events\Email\NewEmailEvent;
use App\Services\Mailbox\MailboxService;
use Google\Cloud\PubSub\Message;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PubSubMailboxController extends Controller
{
    public function triggerInboxEmailEvent(Request $request, Dispatcher $dispatcher, MailboxService $service): Response
    {
        $secretKey = $request->route('secretKey');
        if (strcmp(config('services.mailbox.api_key'), $secretKey) !== 0) {
            \Log::notice('Invalid secret key for triggerInboxEmailEvent');
            abort(403, 'Invalid secret key');
        }

        $rawMessage = $request->all();
        $attributes = $rawMessage['message']['attributes'];

        /**
         * @var Account $account
         */
        $account = \EntityManager::getRepository(Account::class)->findOneBy(['username' => $attributes['mailbox']]);

        if ($account) {
            $validator = \Validator::make(['email' => $account->getEmail()], [
                'email' => 'not_regex:/(.*)application-inbox\.com$/i',
            ]);
            if ($validator->fails()) {
                \Log::warning('Account has application-inbox.com domain in email. Account id:' . $account->getAccountId() . ' Incoming Pub/Sub message: ' . json_encode($rawMessage));
                abort(204);
            }
        }

        $email = $service->fetchEmailByMessageId($attributes['mailbox'], $attributes['message_id']);

        if ($account && $email) {
            if (!$this->maintainIdempotency($email->getMessageId())) {
                \Log::notice(
                    sprintf(
                        'Pub/Sub mailbox message [ %s ] for the account [ %d ] is arrived more then once. Skipping.',
                        $email->getMessageId(),
                        $account->getAccountId()
                    )
                );

                abort(204);
            }

            $dispatcher->dispatch(new NewEmailEvent($account, $email));
        } else {
            \Log::warning('Account or Email not found to trigger NewEmailEvent. Incoming Pub/Sub message: '.json_encode($rawMessage));
        }

        return response('', 204);
    }

    /**
     * Used to make sure that a certain message received and processed only once.
     * Return FALSE if message has already been processed.
     *
     * @param string $cacheKey
     * @param int $value
     * @return bool
     */
    protected function maintainIdempotency($messageId): bool
    {
        $store = \Cache::store('redisShared');
        $cacheKey = "mailbox.inbox.event_handled.{$messageId}";
        $prevValue = $store->get($cacheKey);

        if (!$prevValue) {
            $store->put($cacheKey, 1, 24 * 60 * 60);

            return true;
        }

        return false;
    }
}

