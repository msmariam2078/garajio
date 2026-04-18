<?php

namespace App\Mail;
use PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
 
class InspectionCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details = [])
    {
        $this->details = $details;
    }

    public function build()
    {
        return $this->from($this->details['from'] ?? config('mail.from.address'), $this->details['from_name'] ?? config('mail.from.name'))
                    ->to($this->details['to'])
                    ->subject($this->details['subject'])
                    ->markdown('techdashboard.inspectionCompletedMail')
                    ->with('details', $this->details);
    }

 
}
