<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\ResetPasswordType;
use App\Repository\UserRepository;
use App\Repository\UserResetPasswordTokenRepository;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/user/")
 */
class ResetPasswordController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    use ReturnValidationErrorsTrait;

    private UserResetPasswordTokenRepository $userResetPasswordTokenRepository;

    private UserRepository $userRepository;

    private UserPasswordHasherInterface $passwordHasher;

    private int $passwordResetExpirationMinutes;

    public function __construct(
        UserResetPasswordTokenRepository $userResetPasswordTokenRepository,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        $passwordResetExpirationMinutes
    ) {
        $this->userResetPasswordTokenRepository = $userResetPasswordTokenRepository;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->passwordResetExpirationMinutes = intval($passwordResetExpirationMinutes);
    }

    /**
     * @Route("reset-password", name="app_reset_password", methods={"GET", "POST"})
     */
    public function newPasswordAction(Request $request): Response
    {
        $userReset = $this->userResetPasswordTokenRepository->findOneBy(['resetToken' => $request->get('token')]);
        if (null === $userReset) {
            $this->logger->error('Password reset token no longer valid');

            return new Response('Token is no longer valid. Please make your request again!');
        }

        $interval = $userReset->getCreatedAt()->diff(new \Datetime('now'));
        if ($interval->i > $this->passwordResetExpirationMinutes) {
            $this->logger->error('Password reset token no longer valid');

            return new Response('Token is no longer valid. Please make your request again!');
        }

        $user = $userReset->getUser();
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $form->getData()['password']));
            $this->userRepository->add($user);

            return $this->renderForm('reset_password/form.html.twig', [
                'email' => $user->email,
            ]);
        }

        return $this->renderForm('reset_password/form.html.twig', [
            'form' => $form,
            'email' => $user->email,
        ]);
    }
}
