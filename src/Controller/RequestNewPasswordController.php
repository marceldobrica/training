<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\UserResetPasswordToken;
use App\Form\Type\RequestNewPasswordType;
use App\MailHandling\SendNewPasswordMail;
use App\Repository\UserRepository;
use App\Repository\UserResetPasswordTokenRepository;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

/**
 * @Route("/user/")
 */
class RequestNewPasswordController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private UserRepository $userRepository;

    private SendNewPasswordMail $sendNewPasswordMail;

    private UserResetPasswordTokenRepository $userResetPasswordTokenRepository;

    public function __construct(
        UserRepository $userRepository,
        SendNewPasswordMail $sendNewPasswordMail,
        UserResetPasswordTokenRepository $userResetPasswordTokenRepository
    ) {
        $this->userRepository = $userRepository;
        $this->sendNewPasswordMail = $sendNewPasswordMail;
        $this->userResetPasswordTokenRepository = $userResetPasswordTokenRepository;
    }

    /**
     * @Route("request-password", name="app_request_password")
     */
    public function requestResetPasswordAction(Request $request): Response
    {
        $form = $this->createForm(RequestNewPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $receivedEmail = $form->getData()['email'];
            $user = $this->userRepository->findOneBy(['email' => $receivedEmail]);
            if (null === $user) {
                $this->logger->error('No user found for received email', ['receivedEmail' => $receivedEmail]);

                return $this->render('request_new_password/form.html.twig', [
                    'email' => $receivedEmail,
                ]);
            }

            $userToken = $this->userResetPasswordTokenRepository->findOneBy(['user' => $user]);
            if (null === $userToken) {
                $userToken = new UserResetPasswordToken();
            }
            $userToken->setUser($user);
            $resetToken = Uuid::v4();
            $userToken->setResetToken($resetToken);
            $userToken->setCreatedAt(new \DateTime());
            $this->userResetPasswordTokenRepository->add($userToken);

            $this->sendNewPasswordMail->sendResetPasswordMail($user->email, $resetToken);

            return $this->render('request_new_password/form.html.twig', [
                'email' => $receivedEmail,
            ]);
        }

        return $this->renderForm('request_new_password/form.html.twig', [
            'form' => $form,
        ]);
    }
}
