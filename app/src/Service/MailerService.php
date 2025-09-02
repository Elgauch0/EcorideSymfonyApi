<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{


    private string $subject = 'Ecoride';


    public function __construct(
        public  MailerInterface $mailer
    ) {}




    public function sendtoUser(string $to, string $body): void
    {
        $email = (new Email())
            ->from('admin@ecoride.com')
            ->to($to)
            ->subject($this->subject)
            ->text($body);

        $this->mailer->send($email);
    }




    public function sendToAdmin(string $from, string $body): void
    {
        $email = (new Email())
            ->from($from)
            ->to('admin@ecoride.com')
            ->subject($this->subject)
            ->text($body);

        $this->mailer->send($email);
    }
}
