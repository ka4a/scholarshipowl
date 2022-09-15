<?php

namespace App\Http\Controllers\Index;

use App\Services\PubSub\AccountService;
use App\Services\PubSub\DigestService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class DigestController extends Controller
{
    /**
     * @var DigestService
     */
    public $ds;

    public function __construct(Request $request, DigestService $ds)
    {
        $this->ds = $ds;
    }

    /**
     * Trigger Weekly Scholarship Digest
     *
     * @param $secret
     * @return string
     */
    public function weeklyScholarship($secret)
    {
        $this->verifySecret($secret);

        $fields = [
            AccountService::FIELD_SCHOLARSHIP_EL_COUNT,
            AccountService::FIELD_SCHOLARSHIP_EL_AMOUNT,
            AccountService::FIELD_SCHOLARSHIP_EL_COUNT_EXPIRING,
            AccountService::FIELD_SCHOLARSHIP_EL_AMOUNT_EXPIRING,
            AccountService::FIELD_SCHOLARSHIP_EL_COUNT_NEW,
            AccountService::FIELD_SCHOLARSHIP_EL_AMOUNT_NEW,
            AccountService::FIELD_SCHOLARSHIP_EL_LIST_EXPIRING,
        ];

        $mauticContactId = \request('mauticContactId', null);

        $this->ds->triggerDigest('weekly-scholarship-digest', 'weekly-scholarship-digest', $fields, $mauticContactId);

        return 'Ok';
    }

    /**
     * Trigger Weekly Email Digest
     *
     * @param string $secret
     * @return string
     */
    public function weeklyEmail($secret)
    {
        $this->verifySecret($secret);

        $fields = [
            AccountService::FIELD_UNREAD_MESSAGES_COUNT,
            AccountService::FIELD_UNREAD_MESSAGES_LIST
        ];

        $mauticContactId = \request('mauticContactId', null);

        $this->ds->triggerDigest('weekly-email-digest', 'weekly-email-digest', $fields, $mauticContactId);

        return 'Ok';
    }

    /**
     * @param string $providedSecret
     */
    protected function verifySecret($providedSecret)
    {
        $secret = config('services.digest.api_key');

        if (!$providedSecret || strcmp($providedSecret, $secret) !== 0) {
            abort(401, 'Invalid API key');
        }
    }
}

