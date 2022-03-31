<?php

declare(strict_types=1);

namespace App\MailHandling;

use App\Entity\User;
use App\Entity\UserResetPasswordToken;
use App\Repository\UserResetPasswordTokenRepository;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Uid\Uuid;

class SendNewPasswordMail implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private UserResetPasswordTokenRepository $userResetPasswordTokenRepository;

    private MailerInterface $mailer;

    private string $passwordResetExpirationMinutes;

    public function __construct(
        UserResetPasswordTokenRepository $userResetPasswordTokenRepository,
        MailerInterface $mailer,
        $passwordResetExpirationMinutes
    ) {
        $this->userResetPasswordTokenRepository = $userResetPasswordTokenRepository;
        $this->mailer = $mailer;
        $this->passwordResetExpirationMinutes = $passwordResetExpirationMinutes;
    }

    /**
     * @return Response|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(User $user)
    {
        $userToken = $this->userResetPasswordTokenRepository->findOneBy(['user' => $user]);
        if (null === $userToken) {
            $userToken = new UserResetPasswordToken();
        }
        $userToken->setUser($user);
        $resetToken = Uuid::v4();
        $userToken->setResetToken($resetToken);
        $userToken->setCreatedAt(new \DateTime());
        $this->userResetPasswordTokenRepository->add($userToken);

        $email = (new TemplatedEmail())
            ->from('marceldobrica66@gmail.com')
            ->to($user->email)
            ->subject('Reset password')
            ->htmlTemplate('emails/password-reset.html.twig')
            ->context([
                'token' => $resetToken,
                'expiration_date' => new \DateTime('+' . $this->passwordResetExpirationMinutes . ' minutes'),
            ]);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage(), [$e]);
        }
    }
}
