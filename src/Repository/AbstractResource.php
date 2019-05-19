<?php

namespace App\Repository;

use Doctrine\ORM\EntityManager;

abstract class AbstractResource
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_entityManager = null;

    public function __construct(EntityManager $entityManager)
    {
        $this->_entityManager = $entityManager;
    }
}