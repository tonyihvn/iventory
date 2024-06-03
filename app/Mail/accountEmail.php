<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class accountEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;

    public function __construct($name,$password)
    {
        $this->name = $name;
        $this->password = $password;
    }

    public function build()
    {
        return $this->view('emails.account_email')
                    ->with([
                        'name' => $this->name,
                        'password' => $this->password
                    ]);
    }
}
