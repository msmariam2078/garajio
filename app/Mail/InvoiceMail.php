<?php

namespace App\Mail;
use PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
 
class InvoiceMail extends Mailable
{
    public $invoice,$quotations,$workorder,$technician,$vehicle;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice,$details = [],$quotations,$vehicles,$workorder,$technician)
    //$workorder,$vehicle,$technician)
    {
        $this->invoice = $invoice;
        $this->details = $details;
       $this->workorder = $workorder;
       $this->vehicles = $vehicles;
        $this->technician = $technician;
        $this->quotations=$quotations;
    }

    public function build()
    {
        $pdf = PDF::loadView('invoice.invoicePdf', ['invoice' => $this->invoice,
        'quotations'=>$this->quotations,
        'vehicles'=>$this->vehicles,
        'workorder'=>$this->workorder,
        'technician'=>$this->technician,
     
]);

        return $this->from($this->details['from'] ?? config('mail.from.address'), $this->details['from_name'] ?? config('mail.from.name'))
                    ->to($this->details['to'])
                    ->subject($this->details['subject'])
                    ->markdown('invoice.invoiceEmail')
                    ->with('details', $this->details)
					->attachData($pdf->output(), 'invoice.pdf', [
						'mime' => 'application/pdf',
					]
                );
    }

 
}
