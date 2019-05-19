<?php

namespace App\Repository;

use App\Repository\AbstractResource;
use App\Models\Entity\Recipient;

class RecipientRepository extends AbstractResource
{

    public function create($args)
    {
        
        $recipient = new Recipient();
        $recipient->setName($args['name']);
        $recipient->setEmail($args['email']);

        $this->_entityManager->persist($recipient);
        $this->_entityManager->flush();

        return $recipient;
    }

    public function find($id)
    {
        $repository = $this->_entityManager->getRepository(Recipient::class);
        
        $recipient = $repository->findOneBy(["email" => $id]);
        return $recipient;
    }
}