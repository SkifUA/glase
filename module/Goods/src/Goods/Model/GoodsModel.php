<?php


namespace Goods\Model;


use Admin\Exception\ImageException;
use Admin\Model\ImagesModel;
use Application\Model\Grid;
use Goods\Entity\Goods;
use Goods\Entity\TypeGoods;
use Users\Model\UserModel;
use Zend\Paginator\Paginator;

class GoodsModel
{

    const PRODUCT_GOODS_PAGINATOR = 6;
    const PRODUCT_DATA_TIME_FORMAT = 'd-M-Y H:i:s';
    const DEFAULT_ORDER_SELECT = 'ASC';
    const DEFAULT_COLUMN_SELECT = 'id';
    const INFOJS_BUYER_FALSE = 'Sorry but a bid not been made';
    const ERRORJS_PRODUCT_FALSE = 'Sorry but a Lot can not be found';


    /**
     * @var object EntityManager
     */
    protected $em;

    /**
     * @var int User Id
     */
    public $authorId;

    /**
     * @var object AuthStorage
     */
    public $authStorage;

    /**
     * @var object Grid
     */
    protected $grid;


    public function __construct($em, $authStorage)
    {
        $this->em = $em;
        $this->authorId = $authStorage->read()[UserModel::AUTH_NAME_STAGING_USER_ID];
        $this->authStorage = $authStorage;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getGoodsById($id)
    {
        $result = $this->em->getRepository(Goods::class)
            ->find($id);
        return $result;
    }

    /**
     * @param null $text
     * @param string $column
     * @param string $order
     * @return Paginator
     */
    public function getListByParameters(
        $text=null,
        $column=self::DEFAULT_COLUMN_SELECT,
        $order=self::DEFAULT_ORDER_SELECT
    )
    {
        $grid = $this->getGrid();
        $result = $grid->getListByParameters(
            $text,
            $column,
            $order,
            'name'
        );
        return $result;
    }


    /**
     * @param $goods
     * @return mixed
     */
    public function save($goods)
    {
        $this->em->persist($goods);
        $this->adapterGoods($goods);
        $this->em->flush();

        return $goods->getId();
    }

    /**
     * @param $goods
     * @return bool
     */
    public function update($goods)
    {
        $goodsDb = $this->em->getRepository(Goods::class)
            ->find($goods->getId());
        if (!$goodsDb) {
            return false;
        }

        $goodsDb->setOptions($goods->getArrayCopy());
        $this->adapterGoods($goodsDb);

        $this->em->flush();

        return true;
    }

    /**
     * @param object Goods
     * @return mixed
     */
    public function adapterGoods($goods)
    {

        $price = (int) $goods->getPrice() * 100;
        $goods
            ->setPrice($price);

        $this->em->persist($goods);
        return ($goods);
    }

    /**
     * @return Grid|object
     */
    protected function getGrid()
    {
        if (!$this->grid) {
            $grid = new Grid(new Goods(), $this->em, $this->authStorage);
            $this->grid = $grid;
        }
        return $this->grid;
    }
}