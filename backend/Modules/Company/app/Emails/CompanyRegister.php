<?php

namespace Modules\Company\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompanyRegister extends Mailable
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
        return $this->view('company::email.register', [
            'token' => $this->token
        ]);
    }
}
