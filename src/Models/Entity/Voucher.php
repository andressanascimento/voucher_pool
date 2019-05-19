<?php

namespace App\Models\Entity;

use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity() 
 * @Table(name="voucher")
 **/
class Voucher 
{
    /**
     * @var int
     * @Id @Column(type="integer") 
     * @GeneratedValue
     */
    protected $id;
    /**
     * @var string
     * @Column(type="string") 
     */
    protected $code;

    /**
     * @var string
     * @Column(type="string", length=50) 
     */
    protected $email;

    /**
     * @var string
     * @Column(type="float") 
     */
    protected $discount;

    /**
     * @var string
     * @Column(type="date", name="date_used", nullable=true) 
     */
    protected $dateUsed;


    /**
     * @ManyToOne(targetEntity="App\Models\Entity\Recipient")
     * @JoinColumn(name="recipient_id", referencedColumnName="id")
     */
    protected $recipient;

    /**
     * @ManyToOne(targetEntity="App\Models\Entity\SpecialOffer")
     * @JoinColumn(name="special_offer_id", referencedColumnName="id")
     */
    protected $specialOffer;

    public function getId()
    {
        return $this->id;
    }
    
    public function getCode()
    {
        return $this->code;
    }

    public function getEmail()
    {
        return $this->email;
    }
    
    public function getDiscount() 
    {
        return $this->discount;
    }
    
    public function getDateUsed() 
    {
        return $this->dateUsed;
    }

    public function getSpecialOffer() 
    {
        return $this->specialOffer;
    }

    public function getRecipient() 
    {
        return $this->recipient;
    }
    
    public function setCode($code)
    {
        $this->code = $code;
        return $this;  
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;  
    }
    
    public function setDiscount($discount) 
    {
        $this->discount = $discount;
        return $this;
    }

    public function setDateUsed($date_used)
    {
        $this->dateUsed = $date_used;
        return $this;  
    }

    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;  
    }

    public function setSpecialOffer($special_offer)
    {
        $this->specialOffer = $special_offer;
        return $this;  
    }

    public function toArray()
    {
        return [
            "code" => $this->getCode(),
            "offer" => $this->getSpecialOffer()->getName()
        ];
    }
}