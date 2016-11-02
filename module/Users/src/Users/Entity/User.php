<?php


namespace Users\Entity;

use Application\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;


/**
 * A list users.
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @property string $email
 * @property string $firstName
 * @property string $lastName
 * @property string $password
 * @property string $role
 * @property boolean $state
 * @property /DateTime $dateKey
 * @property int $id
 */
class User extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint", length=20);
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", name="first_name", nullable=true, length=50)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", name="last_name", nullable=true, length=50)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=250)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $role;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $state;

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $role
     * @return $this
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param $firstName
     * @return mixed
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return bool
     */
    public function getState()
    {
        return $this->state;
    }


    /**
     * @return mixed
     */
    public function getClass()
    {
        return self::class;
    }

}