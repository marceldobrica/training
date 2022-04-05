<?php

declare(strict_types=1);

namespace App\MailHandling;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendMailNotification implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMailNotification(string $message, string $emailAddress): void
    {
        $email = (new Email())
            ->from('marceldobrica66@gmail.com')
            ->to($emailAddress)
            ->subject('Notification email')
            ->text($message)
           ;

        try {
            $this->mailer->send($email);
            $this->logger->info('Sent notification email.', ['to' => $emailAddress]);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage(), [$e]);
        }
    }
}
