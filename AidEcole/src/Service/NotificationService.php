<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Notification; // Ensure this entity exists
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationService
{
    private $mailer;
    private $entityManager;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    public function sendNotification(User $user, string $subject, string $message): void
    {
        // Send an email
        $email = (new Email())
            ->from('Ihebcherni60@gmail.com')
            ->to($user->getEmail())
            ->subject($subject)
            ->html($message); // Use ->html() for HTML emails

        // Debug the email content
        dump($email->getHtmlBody()); // Check the rendered HTML content
        dump($email->getSubject()); // Check the subject

        $this->mailer->send($email);

        // Save the notification to the database
        $notification = new Notification();
        $notification->setMessage($message);
        $notification->setUser($user);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}