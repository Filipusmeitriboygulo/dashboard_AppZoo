<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContinuePayment extends Mailable
{
    use Queueable, SerializesModels;

    public $pembeli;
    public $paymentUrl;

    public function __construct($pembeli, $paymentUrl)
    {
        $this->pembeli = $pembeli;
        $this->paymentUrl = $paymentUrl;
    }

    public function build()
    {
        return $this->view('emails.lanjutkan_pembayaran')
                    ->with([
                        'pembeliNama' => $this->pembeli->nama,
                        'paymentUrl' => $this->paymentUrl,
                    ]);
    }
}
