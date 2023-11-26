<?php

namespace App\Tests;

use App\Entity\Invitation;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InvitationControllerTest extends WebTestCase
{

    public function testSendInvitation(): void
    {
        $client = static::createClient();

        $container = $client->getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $invitationController = $container->get('App\Controller\InvitationController');

        $data = ['sender' => 'user1', 'invited' => 'user2'];
        $request = Request::create('/send-invitation', 'POST', [], [], [], [], json_encode($data));

        $response = $invitationController->sendInvitation($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $expectedData = ['message' => 'Invitation sent successfully'];
        $this->assertEquals(json_encode($expectedData), $response->getContent());

        $invitation = $entityManager->getRepository(Invitation::class)->findOneBy([
            'sender' => $data['sender'],
            'invitedUser' => $data['invited'],
            'status' => 'pending',
        ]);

        $this->assertNotNull($invitation);

        if ($invitation) {
            $entityManager->remove($invitation);
            $entityManager->flush();
        }
    }


    public function testCancelInvitation(): void
    {
        $client = static::createClient();

        $container = $client->getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $invitationController = $container->get('App\Controller\InvitationController');

        $invitation = new Invitation();
        $invitation->setSender('user1');
        $invitation->setInvitedUser('user2');
        $invitation->setStatus('pending');
        $invitation->setCreatedAtValue();

        $entityManager->persist($invitation);
        $entityManager->flush();

        $response = $invitationController->cancelInvitation($invitation->getId());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $expectedData = ['message' => 'Invitation canceled successfully'];
        $this->assertEquals(json_encode($expectedData), $response->getContent());

        $this->assertEquals('canceled', $invitation->getStatus());

        $entityManager->remove($invitation);
        $entityManager->flush();
    }


    public function testAcceptInvitation(): void
    {
        $client = static::createClient();

        $container = $client->getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $invitationController = $container->get('App\Controller\InvitationController');

        $invitation = new Invitation();
        $invitation->setSender('user1');
        $invitation->setInvitedUser('user2');
        $invitation->setStatus('pending');
        $invitation->setCreatedAtValue();

        $entityManager->persist($invitation);
        $entityManager->flush();

        $response = $invitationController->acceptInvitation($invitation->getId());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $expectedData = ['message' => 'Invitation accepted successfully'];
        $this->assertEquals(json_encode($expectedData), $response->getContent());

        $this->assertEquals('accepted', $invitation->getStatus());

        $entityManager->remove($invitation);
        $entityManager->flush();
    }


    public function testDeclineInvitation(): void
    {
        $client = static::createClient();

        $container = $client->getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $invitationController = $container->get('App\Controller\InvitationController');

        $invitation = new Invitation();
        $invitation->setSender('user1');
        $invitation->setInvitedUser('user2');
        $invitation->setStatus('pending');
        $invitation->setCreatedAtValue();

        $entityManager->persist($invitation);
        $entityManager->flush();

        $response = $invitationController->declineInvitation($invitation->getId());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $expectedData = ['message' => 'Invitation declined successfully'];
        $this->assertEquals(json_encode($expectedData), $response->getContent());

        $this->assertEquals('declined', $invitation->getStatus());

        $entityManager->remove($invitation);
        $entityManager->flush();
    }

}