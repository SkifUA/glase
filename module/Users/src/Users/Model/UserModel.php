<?php


namespace Users\Model;

use Application\Module;
use Users\Entity\AuthKey;
use Users\Entity\User;


class UserModel
{
    const AUTH_NAME_STAGING_ROLE = 'role';
    const AUTH_NAME_STAGING_USER_ID = 'user';
    const PASSWORDS_DO_NOT_MATH = 'Passwords do not match';
    const ERROR_EMAIL_OR_PASSWORD = 'Error Email Or Password';
    const INTERVAL_MINUTES_FOR_REGISTRATION = 30;
    const MESSAGE_NOT_REGISTRATION = 'This Email was not registered';
    const MESSAGE_SUCCESS_REGISTRATION = 'To Your email sent to the email registration link';
    /**
     * @var object EntityManager
     */
    public $em;

    /**
     * @var object Session
     */
    protected $authStorage;

    /**
     * @var array
     */
    protected $adminList;


    public function __construct($em, $authStorage, $adminList)
    {
        $this->em = $em;
        $this->authStorage = $authStorage;
        $this->adminList = $adminList;
    }

    /**
     * @param $email
     * @param $inviteId
     * @param string $service
     * @return bool
     */
    public function saveKey($email, $inviteId, $service=AuthKey::AUTH_KEY_SERVICE_REGISTRATION)
    {

        $dataUser = $this->em
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        $dataKey = $this->em
            ->getRepository(AuthKey::class)
            ->findOneBy(['userId' => $dataUser->getId()]);

        if(!$dataKey) {
            $authData = [
                'identification' => $inviteId,
                'date_key' => new \DateTime(),
                'service' => $service,
                'user_id' => $dataUser->getId()
            ];
            $keyObject = new AuthKey($authData);
            $this->em->persist($keyObject);
        } else {
            $dataKey
                ->setService($service)
                ->setIdentification($inviteId)
                ->setDateKey(new \DateTime());
        }
        $this->em->flush();
        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function checkRegistrationUrl($key)
    {
        $dataKey = $this->em
            ->getRepository(AuthKey::class)
            ->findOneBy(['identification' => $key]);

        if (!$dataKey) {
            return false;
        }

        if (!$this->checkIntervalTime($dataKey->getDateKey())) {
            return false;
        }

        $dataUser = $this->em
            ->getRepository(User::class)
            ->find($dataKey->getUserId());

        $dataUser->setState(true);
        $dataKey->setIdentification(null);
        $this->em->flush();
        return true;
    }

    /**
     * @param $date
     * @return bool
     */
    public function writeAuthInStorage($email) {

        $dataUser = $this->em
            ->getRepository('\Users\Entity\User')
            ->findOneBy(['email' => $email]);

        $data = [
            self::AUTH_NAME_STAGING_ROLE => $dataUser->getRole(),
            self::AUTH_NAME_STAGING_USER_ID => $dataUser->getId()
        ];

        $storage = $this->authStorage;
        $storage->write($data);

        return true;
    }

    /**
     * @param $role
     * @return bool
     */
    public function checkAuth()
    {
        $storage = $this->authStorage;

        if ($storage->read()[self::AUTH_NAME_STAGING_ROLE]
        && $storage->read()[self::AUTH_NAME_STAGING_ROLE] != Module::ACL_ROLE_GUEST) {
            return true;
        }
        return false;
    }

    /**
     * @param $email
     * @return bool
     */
    public function checkIssetRegistrationUser($email)
    {
        $user = $this->em
            ->getRepository(User::class)
            ->findOneBy(['email' => $email, 'state' => 1]);
        if ($user) {
            return true;
        }
        return false;
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getUserByEmail($email)
    {
        $user = $this->em
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);
        return $user;
    }

    /**
     * @param $dateKey
     * @return bool
     */
    public function checkIntervalTime($dateKey)
    {
        $dateTimeNow = new \DateTime();
        $interval = $dateKey->diff($dateTimeNow);

        if ($interval->format('%i') > self::INTERVAL_MINUTES_FOR_REGISTRATION) {
            return false;
        }
        return true;
    }

    public function getAlcRole($email)
    {
        if (in_array($email, $this->adminList)) {
            return Module::ACL_ROLE_ADMIN;
        }
        return Module::ACL_ROLE_USER;
    }
}