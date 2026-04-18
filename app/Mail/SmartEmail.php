<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SmartEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    public function __construct($details = [])
    {
        $this->details = $details;
    }

    public function build()
    {
        return $this->from($this->details['from'] ?? config('mail.from.address'), $this->details['from_name'] ?? config('mail.from.name'))
                    ->to($this->details['to'])
                    ->subject($this->details['subject'])
                    ->markdown('email.smart')
                    ->with('details', $this->details);
    }
}
