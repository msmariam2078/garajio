<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Recieptemail extends Mailable
{
    use Queueable, SerializesModels;
    public $settings;
    public $payment;
    public $client;
    public $invoice;
    public function __construct($client,$payment,$settings,$invoice)
    {
        $this->client=$client;
        $this->payment=$payment;

        $this->settings=$settings;
        $this->invoice=$invoice;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Recieptemail',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    // public function content()
    // {
    //     return new Content(
    //         view: 'payment.show',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
    public function build()
    {
        
        return $this->from($this->payment->user->email)
                     ->view('payment.show')->with(['payments' => $this->payment,'settings'=>$this->settings,'clients'=>$this->client,'invoice'=>$this->invoice]);
    }
}
