<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\RequestNewPasswordType;
use App\MailHandling\SendNewPasswordMail;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/")
 */
class RequestNewPasswordController extends AbstractController
{
    private UserRepository $userRepository;

    private SendNewPasswordMail $sendNewPasswordMail;

    public function __construct(
        UserRepository $userRepository,
        SendNewPasswordMail $sendNewPasswordMail
    ) {
        $this->userRepository = $userRepository;
        $this->sendNewPasswordMail = $sendNewPasswordMail;
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
            if (null !== $user) {
                $this->sendNewPasswordMail->handle($user);
            }
            return $this->render('request_new_password/confirmation.html.twig', [
                    'email' => $receivedEmail,
            ]);
        }

        return $this->renderForm('request_new_password/form.html.twig', [
            'form' => $form,
        ]);
    }
}
