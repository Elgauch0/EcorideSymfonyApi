<?php

namespace App\EventListener;



use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;


class JWTCreatedListener
{

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        /** @var \App\Entity\User $user */
        $user = $event->getUser();
        $payload = $event->getData();
        $payload['userID'] = $user->getId();
        $event->setData($payload);
    }
}
