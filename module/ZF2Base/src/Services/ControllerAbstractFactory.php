<?php 
namespace ZF2Base\Services;

use Zend\ServiceManager\AbstractFactoryInterface; 
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Di\ServiceLocator;
 
class ControllerAbstractFactory implements AbstractFactoryInterface
{
	public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
	{
		return class_exists($requestedName . 'Controller');
	}

	public function createServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
	{ 
		$className = $requestedName . 'Controller';  
		return new $className();
	}
}