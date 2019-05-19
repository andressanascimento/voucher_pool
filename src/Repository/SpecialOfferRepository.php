<?php

namespace App\Repository;

use App\Repository\AbstractResource;
use App\Models\Entity\SpecialOffer;

class SpecialOfferRepository extends AbstractResource
{

    public function create($args)
    {
        
        $expire_date = \DateTime::createFromFormat('Y-m-d', $args['expire_date']);
        $special_offer = new SpecialOffer();
        $special_offer->setName($args['name']);
        $special_offer->setDiscount($args['discount']);
        $special_offer->setExpireDate($expire_date);

        $this->_entityManager->persist($special_offer);
        $this->_entityManager->flush();

        return $special_offer;
    }
}