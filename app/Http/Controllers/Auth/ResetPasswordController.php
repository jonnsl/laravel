<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * This method has two main diferences from the default:
     * 1st - The token is validated before showing the reset password form.
     * 2th - The token is removed from the url, for safety reasons see:
     * https://robots.thoughtbot.com/is-your-site-leaking-password-reset-links
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request)
    {
        if ($request->has('token')) {
            $token = $request->input('token');
            $user = User::find($request->input('uid'));

            if ($user === null || !$this->broker()->tokenExists($user, $token)) {
                abort(400);
            }

            return redirect()
                ->refresh()
                ->with([
                    'token' => $token,
                    'uid' => $user->id,
                ]);
        }

        $session = $request->session();
        if ($session->has('token') && $session->has('uid')) {
            return view('auth.passwords.reset')
                ->with([
                    'token' => $session->get('token'),
                    'uid' => $session->get('uid'),
                ]);
        }

        abort(400);
    }
}
