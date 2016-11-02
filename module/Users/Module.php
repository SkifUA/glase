<?php


namespace Users;

use Users\Model\AuthModel;
use Users\Model\MailModel;
use Users\Model\UserModel;
use Zend\Db\Adapter\Adapter;
use Zend\Mail\Headers;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Message;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;



class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
//            'Zend\Loader\ClassMapAutoloader' => array(
//                __DIR__ . '/autoload_classmap.php',
//            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                UserModel::class => function($container) {
                    $em = $container->get('doctrine.entitymanager.orm_default');
                    $authStorage = $container->get('Auth_storage');
                    $adminList = $container->get('Config')['admin_list'];
                    return new Model\UserModel($em, $authStorage, $adminList);
                },
                MailModel::class => function($container) {
                    $urlServer = $container->get('url_Server');
                    $mailService = $container->get('mail.service');
                    $mailTransport = $container->get('mail.transport');
                    return new MailModel($urlServer, $mailService, $mailTransport);
                },
                AuthModel::class => function($container) {
                    $adaptersDb = $container->get(Adapter::class);
                    $authAdapter = new AuthAdapter($adaptersDb);
                    return new AuthModel($authAdapter);
                },
                'mail.transport' =>  function($container){
                    try {
                        $config = $container->get('Config');
                        $transport = new Smtp();
                        $transport->setOptions(new SmtpOptions($config['smtp']['transport']['options']));

                        return $transport;
                    } catch (\Exception $e) {
                        return new Smtp();
                    }
                },
                'mail.service' => function ($container) {
                    $config = $container->get('Config');
                    $email = new Message();
                    $header = new Headers();
                    $email->setHeaders($header);
                    $email->addFrom($config['mail']['from']);
                    return $email;
                },
                'url_Server' => function($container){
                    $request = new \Zend\Http\PhpEnvironment\Request();
                    $serverUrl = $request->getServer('REQUEST_SCHEME') . '://' . $request->getServer('HTTP_HOST');
                    return $serverUrl;
                },
            ],
        ];
    }

}