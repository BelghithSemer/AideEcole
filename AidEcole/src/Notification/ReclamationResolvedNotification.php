<?php
namespace App\Notification;


use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class ReclamationResolvedNotification extends Notification
{
    private string $reclamationTitle;
    private string $reclamationContent;

    public function __construct(string $reclamationTitle, string $reclamationContent)
    {
        $this->reclamationTitle = $reclamationTitle;
        $this->reclamationContent = $reclamationContent;
    }

    public function asEmailMessage(Recipient $recipient): EmailMessage
    {
        $email = (new NotificationEmail())
            ->from('Ihebcherni60@gmail.com')
            ->subject('Votre réclamation a été résolue')
            ->htmlTemplate('emails/reclamation_resolved.html.twig')
            ->context([
                'reclamationTitle' => $this->reclamationTitle,
                'reclamationContent' => $this->reclamationContent,
            ]);

        // Debug the email content
        dump($email->getHtmlBody());

        return $email;
    }

    
}