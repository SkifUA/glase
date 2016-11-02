<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Helper\CatText;
use Application\Helper\TopList;
use Application\Model\Grid;
use Users\Model\UserModel;
use Zend\Authentication\Storage\Session;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Permissions\Acl\Role\GenericRole;

class Module
{
    const VERSION = '3.0.2dev';
    const ACL_ROLE_GUEST = 'guest';
    const ACL_ROLE_USER = 'user';
    const ACL_ROLE_ADMIN = 'admin';


    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAclConfig()
    {
        return include __DIR__ . '/config/acl.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, [$this, 'initAcl']);

    }

    /**
     * @param $e
     * @return mixed
     */
    public function initAcl($e)
    {
        $aclConfig = $this->getAclConfig();

        $urlController = $e->getRouteMatch()->getParams();

        $acl = new Acl();
        $acl->addRole(new GenericRole(self::ACL_ROLE_GUEST));
        $acl->addRole(new GenericRole(self::ACL_ROLE_USER), self::ACL_ROLE_GUEST);
        $acl->addRole(new GenericRole(self::ACL_ROLE_ADMIN), self::ACL_ROLE_USER);

        foreach ($aclConfig['AclResource'] as $keyController => $controller) {

            foreach ($controller as $action) {
                $acl->addResource(new GenericResource($this->makeAclAccessKey($keyController, $action)));
            }
        }

        $acl->deny();

        $acl->allow(self::ACL_ROLE_GUEST, $this->getAclAccessRole(self::ACL_ROLE_GUEST, 'allow'));
        $acl->allow(self::ACL_ROLE_USER, $this->getAclAccessRole( self::ACL_ROLE_USER, 'allow'));
        $acl->allow(self::ACL_ROLE_ADMIN,$this->getAclAccessRole(self::ACL_ROLE_ADMIN, 'allow'));

        $adaptersStorage = $e->getApplication()->getServiceManager()->get('Auth_storage');

        $role = (isset($adaptersStorage->read()[UserModel::AUTH_NAME_STAGING_ROLE]))
            ? $adaptersStorage->read()[UserModel::AUTH_NAME_STAGING_ROLE]
            : self::ACL_ROLE_GUEST;
        $aclAccessKey = $this->makeAclAccessKey($urlController['controller'], $urlController['action']);

        if ($acl->hasResource($aclAccessKey)) {
            if (!$acl->isAllowed($role, $aclAccessKey)) {
                $controller = $e->getTarget();
                $controller->plugin('redirect')->toRoute('application', ['action' => 'access']);
            }
        }
    }

    /**
     * @param $role
     * @param $access
     * @return array
     */
    protected function getAclAccessRole($role, $access) {
        $aclConfig = $this->getAclConfig();
        $result = [];
        foreach($aclConfig['AclRole'][$role][$access] as $keyController => $controller) {
            foreach($controller as $action) {
                $result[] = $this->makeAclAccessKey($keyController, $action);
            }
        }
        return $result;
    }

    /**
     * @param $controller
     * @param $action
     * @return string
     */
    protected function makeAclAccessKey($controller, $action)
    {
        return $controller . '_' . $action;
    }

    public function getViewHelperConfig()
    {
        return [
            'factories' => [
                'topList' => function($helperPluginManager) {
                    $viewHelper = new TopList();
                    $storage = $helperPluginManager->get('Auth_storage');
                    $viewHelper->setStorage($storage);
                    return $viewHelper;
                },
                'catText' => function($helperPluginManager) {
                    $catText = new CatText();
                    return $catText;
                }

            ]
        ];
    }

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'Auth_storage' => function($container) {
                    return new Session();
                },
            ],
        ];
    }
}
