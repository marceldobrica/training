<?php

declare(strict_types=1);

namespace App\MailHandling;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Uid\Uuid;

class SendNewPasswordMail implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private MailerInterface $mailer;

    private string $passwordResetExpirationMinutes;

    private RouterInterface $router;

    public function __construct(
        MailerInterface $mailer,
        $passwordResetExpirationMinutes,
        RouterInterface $router
    ) {
        $this->mailer = $mailer;
        $this->passwordResetExpirationMinutes = $passwordResetExpirationMinutes;
        $this->router = $router;
    }

    public function sendResetPasswordMail(string $emailAddress, Uuid $resetToken): void
    {
        $resetPasswordUrl = $this->router->generate('app_reset_password', [
            'token' => $resetToken
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new TemplatedEmail())
            ->from('marceldobrica66@gmail.com')
            ->to($emailAddress)
            ->subject('Reset password')
            ->htmlTemplate('emails/password-reset.html.twig')
            ->context([
                'resetlink' => $resetPasswordUrl,
                'mail' => $emailAddress,
                'expiration_date' => new \DateTime('+' . $this->passwordResetExpirationMinutes . ' minutes'),
            ]);

        try {
            $this->mailer->send($email);
            $this->logger->info('Reset password email sent.', ['to' => $emailAddress]);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage(), [$e]);
        }
    }
}
