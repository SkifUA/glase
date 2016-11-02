<?php


namespace Application\Helper;

use Application\Module;
use Users\Model\UserModel;
use Zend\View\Helper\AbstractHelper;


class TopList extends AbstractHelper
{
    /**
     * @var object AuthStorage
     */
    public $storage;

    public function __invoke($active)
    {
        $role = Module::ACL_ROLE_GUEST;
        if (Module::ACL_ROLE_ADMIN == $this->storage->read()[UserModel::AUTH_NAME_STAGING_ROLE]) {
            $role = Module::ACL_ROLE_ADMIN;
        }
        if (Module::ACL_ROLE_USER == $this->storage->read()[UserModel::AUTH_NAME_STAGING_ROLE]) {
            $role = Module::ACL_ROLE_USER;
        }
      return $this->getView()->render('application/helper/top-list', ['role' => $role, 'active' => $active]);
    }

    /**
     * @param $storage
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
    }
}