<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Reclamation;
use RuntimeException;

class MailerService
{
    private $mailer;
    private $from;

    public function __construct(MailerInterface $mailer, string $from)
    {
        $this->mailer = $mailer;
        $this->from = $from;
    }

    public function sendConfirmationEmail(Reclamation $reclamation): void
    {
        $user = $reclamation->getUser(); // Ensure this method exists in the Reclamation entity
        if (!$user || !$user->getEmail()) {
            throw new RuntimeException('Aucun utilisateur ou email trouvé pour cette réclamation.');
        }

        $email = (new Email())
            ->from($this->from)
            ->to($user->getEmail())
            ->subject('Confirmation de réception de votre réclamation')
            ->html(sprintf(
                '<p>Votre réclamation concernant "%s" a bien été reçue.</p>',
                $reclamation->getSujet()
            ));

        // Debug the email content
        dump($email->getHtmlBody()); // Check the rendered HTML content
        dump($email->getSubject()); // Check the subject

        $this->mailer->send($email);
    }

    public function sendResolutionEmail(Reclamation $reclamation): void
    {
        $user = $reclamation->getUser(); // Ensure this method exists in the Reclamation entity
        if (!$user || !$user->getEmail()) {
            throw new RuntimeException('Aucun utilisateur ou email trouvé pour cette réclamation.');
        }

        $email = (new Email())
            ->from($this->from)
            ->to($user->getEmail())
            ->subject('Votre réclamation a été résolue')
            ->html(sprintf(
                '<p>Votre réclamation concernant "%s" a été résolue.</p>',
                $reclamation->getSujet()
            ));

        // Debug the email content
        dump($email->getHtmlBody()); // Check the rendered HTML content
        dump($email->getSubject()); // Check the subject

        $this->mailer->send($email);
    }
}