<?php


namespace Users\Model;


class AuthModel
{
    protected $authAdapter;

    public function __construct($authAdapter )
    {
        $this->authAdapter = $authAdapter;
    }

    /**
     * @param $password
     * @param $email
     * @return bool
     */
    public function isValid($password, $email)
    {
        $passwordValidation = function ($hash, $password) {
            return password_verify($password, $hash);
        };

        $authAdapter = $this->authAdapter;
        $authAdapter
            ->setTableName('user')
            ->setIdentityColumn('email')
            ->setCredentialColumn('password')
            ->setCredentialValidationCallback($passwordValidation);

        $authAdapter->setIdentity($email);
        $authAdapter->setCredential($password);

        $result = $authAdapter->authenticate();

        return $result->isValid();
    }
}