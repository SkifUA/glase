<?php


namespace Application\Helper;

use Zend\View\Helper\AbstractHelper;


class CatText extends AbstractHelper
{
    public function __invoke($text,$size)
    {
        $textCat = implode(array_slice(explode('<br>',wordwrap($text,$size,'<br>',false)),0,1));

        if (strlen($textCat) < strlen($text)) {
            $textCat = $textCat . '...';
        } elseif (strlen($textCat) > $size) {
            $textCat = substr($textCat,0,$size-4).'...';
        } else {
            $textCat = $text;
        }

        return $textCat;
    }

}