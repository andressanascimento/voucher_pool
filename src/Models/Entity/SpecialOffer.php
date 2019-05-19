<?php
namespace App\Models\Entity;
/**
 * @Entity() 
 * @Table(name="special_offer")
 **/
class SpecialOffer {
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
    protected $name;
    /**
     * @var float
     * @Column(type="float") 
     */
    protected $discount;

    /**
     * @var float
     * @Column(type="date", name="expire_date") 
     */
    protected $expireDate;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDiscount() 
    {
        return $this->discount;
    }   
    
    public function getExpireDate()
    {
        return $this->expireDate;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;  
    }

    public function setDiscount($discount) 
    {
        $this->discount = $discount;
        return $this;
    }

    public function setExpireDate($expire_date)
    {
        $this->expireDate = $expire_date;
    }

    public function toArray()
    {
        return [
            "name" => $this->getName(),
            "discount" => ($this->getDiscount() * 100)."%",
            "expire_date" => $this->getExpireDate()
        ];
    }
}