<?php

namespace App\Mail;

use App\Models\Pembeli;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pembeli;
    public $filePath;

    public function __construct(Pembeli $pembeli)
    {
        $this->pembeli = $pembeli;
    }

    public function build()
    {
        return $this->view('emails.tiket')
        ->subject('Your Concert Ticket')
        ->with([
            'pembeliNama' => $this->pembeli->nama,
            'pembeliIdPesanan' => $this->pembeli->id_pesanan,
        ]);

    }
}
