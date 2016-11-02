<?php


namespace Application\Model;


use Zend\Math\Rand;

class HashModel
{

    /**
     * @return string
     */
    public function generatePass($lengthPass=32)
    {
        $arr  = array(
            'A','B','C','D','E','F','G',
            'a','b','c','d','e','f','g',
            'H','I','J','K','L','M','N',
            'h','i','j','k','l','m','n',
            'O','P','Q','R','S','T','U',
            'o','p','q','r','s','t','u',
            'W','X','Y','Z','!','(',')',
            'w','x','y','z','1','2','3',
            '4','5','6','7','8','9','0',
            'V','v','_','-');

        $pass = "";

        for ($i = 0; $i < $lengthPass; $i++) {
            //random
            $index = rand(0, count($arr)-1);
            $pass .= $arr[$index];
        }
        return $pass;
    }

    /**
     * @param $data
     * @return bool|string
     */
    public function hash($data)
    {
        return password_hash($data, PASSWORD_DEFAULT);
    }

    /**
     * @param $pass
     * @param $hash
     * @return bool
     */
    public function isValid($pass, $hash)
    {
        return password_verify($pass, $hash);
    }

    /**
     * @param int $lengthPass
     * @return string
     */
    public  function getNumbersKey($lengthPass=32)
    {
        $pass = "";
        for ($i = 0; $i < $lengthPass; $i++) {
            $pass .= Rand::getInteger(0,9);
        }
        return $pass;
    }

}