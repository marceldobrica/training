<?php

declare(strict_types=1);

namespace App\Controller;

use App\MailHandling\SendMailNotification;
use App\Message\SmsNotification;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class SmsMailMessengerController extends AbstractController
{
    private UserRepository $userRepository;

    private SendMailNotification $sendMailNotification;


    public function __construct(UserRepository $userRepository, SendMailNotification $sendMailNotification)
    {
        $this->userRepository = $userRepository;
        $this->sendMailNotification = $sendMailNotification;
    }

    /**
     * @Route ("/api/messages", name="app_messages_mail_sms", methods={"POST"})
     */
    public function sendSmsMailAction(MessageBusInterface $bus): Response
    {
        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $mailMessage = 'Look! I created a message to be dispatched by mail!';

            $this->sendMailNotification->sendMailNotification($mailMessage, $user->email);
            if (null !== $user->getPhone() && strlen($user->getPhone()) > 5 ) {
                $bus->dispatch(
                    new SmsNotification(
                        'Look! I created a message to be dispatched by sms!',
                        $user->getPhone()
                    )
                );
            }
        }

        return new JsonResponse(
            'The application is starting to send all sms and emails notifications',
            Response::HTTP_OK
        );
    }
}
