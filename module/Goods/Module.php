<?php


namespace Goods;

use Goods\Model\GoodsModel;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;


class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
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
                GoodsModel::class => function($container) {
                    $em = $container->get('doctrine.entitymanager.orm_default');
                    $authStorage = $container->get('Auth_storage');
                    return new Model\GoodsModel($em, $authStorage);
                },
            ],
        ];
    }

}