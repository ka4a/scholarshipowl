<?php namespace App\Http\Controllers;

use App\Entities\User;
use App\Entities\PasswordResets;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * ResetPasswordController constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return Response::json(['message' => $response]);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return Response::json(['error' => $response])->setStatusCode(400);
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = $request->only('password', 'password_confirmation', 'token');

        /**
         * Fetch email by token. So we don't need show it on form.
         */
        if (isset($credentials['token'])) {
            /** @var PasswordResets $token */
            $token = $this->em->getRepository(PasswordResets::class)
                ->findOneBy(['token' => $credentials['token']]);

            if ($token) {
                $credentials['email'] = $token->getEmail();
            }
        }

        return $credentials;
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword|User  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->setPassword(Hash::make($password));
        $user->setRememberToken(Str::random(60));

        $this->em->flush($user);

        event(new PasswordReset($user));
    }
}
