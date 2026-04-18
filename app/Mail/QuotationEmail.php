<?php
namespace App\Mail;

use PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuotationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $details, $estimation,$booking,$products,$vehicle,$user;

    public function __construct($details = [], $estimation,$booking,$user,$products,$vehicle)
    {
        $this->details = $details;
        $this->estimation = $estimation;
        $this->booking = $booking;
        $this->user = $user;
        $this->products = $products;
        $this->vehicle =$vehicle;		
    }

    public function build()
    {
		$pdf = PDF::loadView('estimation.quotationPdf', ['quotation' => $this->estimation,'booking'=>$this->booking,'client'=>$this->user,'products'=>$this->products,'vehicle'=>$this->vehicle]);

        return $this->from($this->details['from'] ?? config('mail.from.address'), $this->details['from_name'] ?? config('mail.from.name'))
                    ->to($this->details['to'])
                    ->subject($this->details['subject'])
                    ->markdown('estimation.quotationEmail')
                    ->with('details', $this->details)
					->attachData($pdf->output(), 'quotation.pdf', [
						'mime' => 'application/pdf',
					]);
    }
}
