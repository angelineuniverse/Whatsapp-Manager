<?php

namespace Modules\Users\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailRegister extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public $token,
    ) {
        //
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->view('users::email.register', [
            'token' => $this->token
        ]);
    }
}
