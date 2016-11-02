<?php


namespace Goods\Entity;


use Admin\Model\ImagesModel;
use Application\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;


/**
 * A list goods.
 *
 * @ORM\Entity
 * @ORM\Table(name="goods")
 * @property string $name
 * @property integer $price
 * @property string $description
 * @property int $id
 */
class Goods extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint", length=20);
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50);
     */
    protected $name;

    /**
     * @ORM\Column(type="text", nullable=true);
     */
    protected $descriptions;

    /**
     * @ORM\Column(type="integer");
     */
    protected $price;


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
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $descriptions
     * @return $this
     */
    public function setDescriptions($descriptions)
    {
        $this->descriptions = $descriptions;
        return $this;
    }

    /**
     * @return int
     */
    public function getDescriptions()
    {
        return $this->descriptions;
    }

    /**
     * @param $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }
}