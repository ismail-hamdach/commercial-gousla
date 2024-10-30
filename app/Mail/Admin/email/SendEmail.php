<?php

namespace App\Mail\Admin\email;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details=$details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $pdf = PDF::loadView('Dashboard.admin.email.invoice',['title' => 'gfj dfgjh fdgh fdgh fdgh fdgh']);
        //return $pdf->download('document.pdf');
        return $this->subject('test')->view('Dashboard.admin.email.invoice') and $pdf->download('document.pdf');
    }
}
