<?php
namespace App\Models\Entity;
/**
 * @Entity() 
 * @Table(name="recipient", uniqueConstraints={@UniqueConstraint(name="email", columns={"email"})})
 **/
class Recipient 
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
    protected $name;
    /**
     * @var string
     * @Column(type="string", length=40) 
     */
    protected $email;

    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getEmail()
    {
        return $this->email;
    }    
    public function setName($name)
    {
        $this->name = $name;
        return $this;  
    }
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function toArray()
    {
        return [
            "name" => $this->getName(),
            "email" => $this->getEmail()
        ];
    }
}