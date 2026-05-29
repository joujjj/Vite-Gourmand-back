<?php namespace App\Controller;

use App\Entity\Contact;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

// ═══════════════════════════════════════════════════════════
// CONTACT
// ═══════════════════════════════════════════════════════════
#[Route('/api/contact')]
class ContactController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerService          $mailer,
    ) {}

    #[Route('', methods: ['POST'])]
    public function send(Request $request): JsonResponse
    {
        $data  = json_decode($request->getContent(), true);
        $email = trim($data['email'] ?? '');
        $titre = trim($data['titre'] ?? '');
        $desc  = trim($data['description'] ?? '');

        if (!$email || !$titre || !$desc) {
            return $this->json(['error' => 'Tous les champs sont requis.'], 400);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->json(['error' => 'Adresse e-mail invalide.'], 400);
        }

        $contact = new Contact();
        $contact->setEmail($email);
        $contact->setTitre($titre);
        $contact->setDescription($desc);
        $this->em->persist($contact);
        $this->em->flush();

        $this->mailer->sendContact($email, $titre, $desc);

        return $this->json(['message' => 'Votre message a été envoyé. Nous vous répondrons sous 48h.'], 201);
    }
}
