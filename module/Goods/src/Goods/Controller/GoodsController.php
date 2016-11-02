<?php


namespace Goods\Controller;



use Application\Controller\TemplateController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;

class GoodsController extends TemplateController
{


    public function __construct(
        $em,
        $goodsModel,
        $authStorage)
    {
        $this->fm = $this->plugin(FlashMessenger::class);
        $this->em = $em;
        $this->goodsModel = $goodsModel;
        $this->authStorage = $authStorage;
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function index()
    {
        return $this->getViewModel();
    }


}