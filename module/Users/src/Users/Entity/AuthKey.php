<?php

namespace Users\Entity;


use Application\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * A list key.
 *
 * @ORM\Entity
 * @ORM\Table(name="auth_key")
 * @property string $service
 * @property string $identification
 * @property /DateTime $dateKey
 * @property int $userId
 * @property int $id
 */
class AuthKey extends Entity
{
    const AUTH_KEY_SERVICE_REGISTRATION = 'reg';
    const AUTH_KEY_SERVICE_FORGOT = 'forgot';
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint", length=20);
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="bigint", name="user_id", length=20, unique=true);
     */
    protected $userId;


    /**
     * @ORM\Column(type="string", length=50, unique=true, nullable=true)
     */
    protected $identification;

    /**
     * @ORM\Column(type="datetime", name="date_key")
     */
    protected $dateKey;

    /**
     * @ORM\Column(type="string", length=16)
     */
    protected $service;

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
     * @param $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function setIdentification($identification)
    {
        $this->identification = $identification;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentification()
    {
        return $this->identification;
    }

    /**
     * @param $dateKey
     * @return $this
     */
    public function setDateKey($dateKey)
    {
        $this->dateKey = $dateKey;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateKey()
    {
        return $this->dateKey;
    }

    /**
     * @param $service
     * @return $this
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }


}