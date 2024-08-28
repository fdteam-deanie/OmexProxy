<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BalanceIncreased extends Mailable
{
    use Queueable, SerializesModels;

    public $amount;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($amount, $user)
    {
        $this->amount = $amount;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Balance Increased')
                    ->view('emails.balance_increased');
    }
}
