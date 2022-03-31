<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\ResetPasswordType;
use App\Repository\UserRepository;
use App\Repository\UserResetPasswordTokenRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/user/")
 */
class ResetPasswordController extends AbstractController
{
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
            return new Response('Token is no longer valid. Please make your request again!');
        }

        $interval = $userReset->getCreatedAt()->diff(new \Datetime('now'));
        if ($interval->i > $this->passwordResetExpirationMinutes) {
            return new Response('Token is no longer valid');
        }
        $user = $userReset->getUser();
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($form->getData()['password']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            $this->userRepository->add($user);

            return $this->renderForm('reset_password/confirmation.html.twig', [
                'form' => $form,
            ]);
        }

        return $this->renderForm('reset_password/form.html.twig', [
            'form' => $form,
            'email' => $user->email,
        ]);
    }
}
