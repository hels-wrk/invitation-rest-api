<?php

namespace App\Controller;

use App\Entity\Invitation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvitationController extends AbstractController
{

    private EntityManagerInterface $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/send-invitation", name="send_invitation", methods={"POST"})
     */
    public function sendInvitation(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $sender = $data['sender'];
        $invited = $data['invited'];

        $existingInvitation = $this->getDoctrine()->getRepository(Invitation::class)->findOneBy([
            'sender' => $sender,
            'invitedUser' => $invited,
            'status' => 'pending',
        ]);

        if ($existingInvitation) {
            return new JsonResponse(['message' => 'Invitation already sent'], 400);
        }

        $invitation = new Invitation();
        $invitation->setSender($sender);
        $invitation->setInvitedUser($invited);
        $invitation->setStatus('pending');
        $invitation->setCreatedAtValue();

        $this->entityManager->persist($invitation);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Invitation sent successfully'], 200);
    }


    /**
     * @Route("/cancel-invitation/{id}", name="cancel_invitation", methods={"POST"})
     */
    public function cancelInvitation($id): Response
    {
        $invitation = $this->getDoctrine()->getRepository(Invitation::class)->find($id);

        if (!$invitation) {
            return new JsonResponse(['message' => 'Invitation not found'], 404);
        }

        if ($invitation->getStatus() === 'canceled') {
            return new JsonResponse(['message' => 'Invitation is already canceled'], 400);
        }

        $invitation->setStatus('canceled');
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Invitation canceled successfully'], 200);
    }


    /**
     * @Route("/accept-invitation/{id}", name="accept_invitation", methods={"POST"})
     */
    public function acceptInvitation($id): Response
    {
        $invitation = $this->getDoctrine()->getRepository(Invitation::class)->find($id);

        if (!$invitation) {
            return new JsonResponse(['message' => 'Invitation not found'], 404);
        }

        if ($invitation->getStatus() === 'accepted') {
            return new JsonResponse(['message' => 'Invitation is already accepted'], 400);
        }

        $invitation->setStatus('accepted');
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Invitation accepted successfully'], 200);
    }


    /**
     * @Route("/decline-invitation/{id}", name="decline_invitation", methods={"POST"})
     */
    public function declineInvitation($id): Response
    {
        $invitation = $this->getDoctrine()->getRepository(Invitation::class)->find($id);

        if (!$invitation) {
            return new JsonResponse(['message' => 'Invitation not found'], 404);
        }

        if ($invitation->getStatus() === 'declined') {
            return new JsonResponse(['message' => 'Invitation is already declined'], 400);
        }

        $invitation->setStatus('declined');
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Invitation declined successfully'], 200);
    }

}