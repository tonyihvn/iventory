<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $reason;

    public function __construct($name,$reason)
    {
        $this->name = $name;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->view('emails.send_email')
                    ->with([
                        'name' => $this->name,
                        'reason'=>$this->reason
                    ]);
    }
}
