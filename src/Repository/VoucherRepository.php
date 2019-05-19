<?php

namespace App\Repository;

use App\Repository\AbstractResource;
use App\Models\Entity\Recipient;
use App\Models\Entity\SpecialOffer;
use App\Models\Entity\Voucher;

class VoucherRepository extends AbstractResource
{

    public function search($email)
    {
        $today = new \DateTime();
        $dql = "SELECT v FROM App\Models\Entity\Voucher v JOIN v.specialOffer s WHERE v.email = :email and s.expireDate >= :today  and v.dateUsed is null";
        $query = $this->_entityManager->createQuery($dql);
        $query->setParameter("email", $email);
        $query->setParameter("today", $today->format('Y-m-d'));
        $vouchers = $query->getResult();

        $vouchers_list = [];
        foreach ($vouchers as $voucher) {
            $vouchers_list[] = $voucher->toArray();
        }

        return $vouchers_list;
    }

    public function find($code)
    {
        $voucher = $this->_entityManager->getRepository(Voucher::class)->findOneBy(["code" => $code]);
        return $voucher;
    }

    public function validate($code, $email)
    {
        $voucher = $this->_entityManager->getRepository(Voucher::class)
                        ->findOneBy(["code" => $code, "email" => $email]);
        if (!$voucher) {
            return null;
        }

        $voucher->setDateUsed(new \DateTime());
        $this->_entityManager->persist($voucher);
        $this->_entityManager->flush();
        return $voucher;
    }

    public function create_vouchers(SpecialOffer $special_offer)
    {
        $recipients = $this->_entityManager->getRepository(Recipient::class)->findBy([]);
        
        foreach ($recipients as $recipient) {
            $code = $this->createCode();
            $voucher = new Voucher();
            $voucher->setCode($code);
            $voucher->setDiscount($special_offer->getDiscount());
            $voucher->setEmail($recipient->getEmail());
            $voucher->setRecipient($recipient);
            $voucher->setSpecialOffer($special_offer);
    
            $this->_entityManager->persist($voucher);
        }
        $this->_entityManager->flush();
    }

    public function createCode()
    {
        $code = strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10));
        print($code);
        return $code;
    }
}