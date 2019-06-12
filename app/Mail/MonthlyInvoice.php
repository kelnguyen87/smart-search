<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MonthlyInvoice extends Mailable
{
    use Queueable, SerializesModels;
    protected $last_month;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($last_month)
    {
        $this->last_month = $last_month;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.invoice.monthly_invoice')->with(['last_month'=>$this->last_month])->subject($this->last_month['subject']);
    }
}
