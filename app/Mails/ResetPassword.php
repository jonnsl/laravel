<?php

namespace App\Mails;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user that will receive this e-mail.
     *
     * @var App\Models\User
     */
    protected $user;

    /**
     * The token sent inside the e-email.
     *
     * @var string
     */
    protected $token;

    /**
     * Create a new reset password message instance.
     *
     * @param App\Models\User $user
     * @param string          $token
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reset_password')
                    ->text('emails.reset_password_plain')
                    ->with([
                        'user' => $this->user,
                        'token' => $this->token,
                    ]);
    }
}
