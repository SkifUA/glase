<?php


namespace Users\Controller;


use Interop\Container\ContainerInterface;
use Users\Model\AuthModel;
use Users\Model\MailModel;
use Users\Model\UserModel;
use Zend\ServiceManager\Factory\FactoryInterface;

class UsersControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em = $container->get('doctrine.entitymanager.orm_default');
        $userModel = $container->get(UserModel::class);
        $mailModel = $container->get(MailModel::class);
        $authModel = $container->get(AuthModel::class);
        $authStorage = $container->get('Auth_storage');

        return new UsersController(
            $em,
            $userModel,
            $mailModel,
            $authModel,
            $authStorage
        );
    }
}
