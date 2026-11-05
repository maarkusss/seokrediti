<?php

namespace App\Mail;

use App\Models\Admincp\Lenders\Lenders;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LenderOffer extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $lenders;

    public $user;

    /**
     * Create a new message instance.
     *
     * LenderOffer constructor.
     * @param $lenders
     */
    public function __construct($user)
    {
        $this->lenders = Lenders::where('active', 1)->orderBy('position', 'ASC')->get();
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Предложение на ' . date('d-m-Y'))->view('mailoffer');
    }
}
