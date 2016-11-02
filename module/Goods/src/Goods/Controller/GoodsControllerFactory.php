<?php


namespace Goods\Controller;


use Goods\Model\GoodsModel;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class GoodsControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em = $container->get('doctrine.entitymanager.orm_default');
        $goodsModel = $container->get(GoodsModel::class);
        $authStorage = $container->get('Auth_storage');
        return new GoodsController(
            $em,
            $goodsModel,
            $authStorage
        );
    }

}