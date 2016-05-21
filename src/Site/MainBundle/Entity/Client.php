<?php

namespace Site\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="clients")
 * @ORM\Entity(repositoryClass="Site\MainBundle\Entity\Repository\ClientRepository")
 */
class Client implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=512)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=512)
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $phone;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;

    /**
     * @ORM\Column(name="is_payment", type="boolean")
     */
    protected $isPayment;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="client", cascade={"persist", "remove"})
     **/
    protected $payments;

    /**
     * @ORM\ManyToOne(targetEntity="Tariff", inversedBy="client")
     * @ORM\JoinColumn(name="tariff_id", referencedColumnName="id")
     **/
    protected $tariff;

    /**
     * @var \DateTime()
     *
     * @ORM\Column(name="ended", type="datetime", nullable=true)
     */
    protected $ended;

//    /**
//     * @ORM\Column(name="key_", type="string", length=255, nullable=true)
//     */
//    protected $key;
//
    /**
     * @var \DateTime()
     *
     * @ORM\Column(name="key_date", type="datetime", nullable=true)
     */
    protected $keyDate;

    public function __construct()
    {
        $this->isActive = true;
        $this->isPayment = false;
        $this->salt = md5(uniqid(null, true));
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->phone,
            $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->phone,
            $this->salt
            ) = unserialize($serialized);
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Client
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Client
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Client
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Client
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Client
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Client
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set isPayment
     *
     * @param boolean $isPayment
     * @return Client
     */
    public function setIsPayment($isPayment)
    {
        $this->isPayment = $isPayment;

        return $this;
    }

    /**
     * Get isPayment
     *
     * @return boolean 
     */
    public function getIsPayment()
    {
        return $this->isPayment;
    }

    /**
     * Количество оставшихся дней
     *
     * @return mixed
     */
    public function getDaysLeft(){
        if(is_object($this->ended) && $this->ended >= new \DateTime()){
            $diff = $this->ended->diff(new \DateTime());

            if($diff->format('%a') <= 0){
                return 0;
            }

            return $diff->format('%a');
        }

        return 0;
    }

    /**
     * Set ended
     *
     * @param \DateTime $ended
     * @return Client
     */
    public function setEnded($ended)
    {
        $this->ended = $ended;

        return $this;
    }

    /**
     * Get ended
     *
     * @return \DateTime 
     */
    public function getEnded()
    {
        return $this->ended;
    }

    /**
     * Add payments
     *
     * @param \Site\MainBundle\Entity\Payment $payments
     * @return Client
     */
    public function addPayment(\Site\MainBundle\Entity\Payment $payments)
    {
        $this->payments[] = $payments;

        return $this;
    }

    /**
     * Remove payments
     *
     * @param \Site\MainBundle\Entity\Payment $payments
     */
    public function removePayment(\Site\MainBundle\Entity\Payment $payments)
    {
        $this->payments->removeElement($payments);
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Set tariff
     *
     * @param \Site\MainBundle\Entity\Tariff $tariff
     * @return Client
     */
    public function setTariff(\Site\MainBundle\Entity\Tariff $tariff = null)
    {
        $this->tariff = $tariff;

        return $this;
    }

    /**
     * Get tariff
     *
     * @return \Site\MainBundle\Entity\Tariff 
     */
    public function getTariff()
    {
        return $this->tariff;
    }

//    /**
//     * Set key
//     *
//     * @param string $key
//     * @return Client
//     */
//    public function setKey($key)
//    {
//        $this->key = $key;
//
//        return $this;
//    }
//
//    /**
//     * Get key
//     *
//     * @return string
//     */
//    public function getKey()
//    {
//        return $this->key;
//    }
//
    /**
     * Set keyDate
     *
     * @param \DateTime $keyDate
     * @return Client
     */
    public function setKeyDate($keyDate)
    {
        $this->keyDate = $keyDate;

        return $this;
    }

    /**
     * Get keyDate
     *
     * @return \DateTime
     */
    public function getKeyDate()
    {
        return $this->keyDate;
    }
}
