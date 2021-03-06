<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZF2Base;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent; 
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\AbstractPluginManager;
use ZF2Base\Listeners\Access; 
use ZF2Base\Listeners\Error;
use ZF2Base\Services\Authentication; 
use ZF2Base\Plugins\GetAccess as PluginGetAccess;
use ZF2Base\Helpers\GetAccess as HelperGetAccess;
use Zend\View\HelperPluginManager;

class Module
{	
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $accessListener = new Access();
        $errorListener = new Error();
        $eventManager->attachAggregate($accessListener, -100);
        $eventManager->attachAggregate($errorListener, -100); 
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getControllerPluginConfig()
    {
        return array(
            'factories' => array(
                'getAccess' => function (AbstractPluginManager $pluginManager) {
                    return new PluginGetAccess($pluginManager->getServiceLocator()->get('ResourceDbAdapterFactory'));
                }
            )
        );    
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'getAccess' => function (HelperPluginManager $pluginManager) { 
                    return new HelperGetAccess($pluginManager->getServiceLocator()->get('ResourceDbAdapterFactory'));
                }
            )
        );
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/',
                ),
            ),
        );
    }  
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'AuthenticationService' => function (ServiceManager $serviceManager) {
                    return new Authentication($serviceManager);
                }
            ), 
        );
    } 
    
}