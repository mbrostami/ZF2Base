<?php 
namespace ZF2Base\Services;

use Zend\ServiceManager\AbstractFactoryInterface; 
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Di\ServiceLocator;
 
class ModelAbstractFactory implements AbstractFactoryInterface
{
	public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
	{

	    $config = $locator->get("Config");
	    // Check if given service name is matched by model service identity pattern
	    if (preg_match("/" . $config['zf2base_config']['model_services_identity_pattern'] . "/i", $requestedName)) {
	        return true;
	    } 
	}

	public function createServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
	{ 
	    if (class_exists($requestedName)) {
	        if (property_exists($requestedName, 'tableName')) {
	            $tableName = $requestedName::$tableName;  
	            return new $requestedName($tableName, $locator->get('ZF2BaseDbAdapter'));
	        } else {
	            // TODO exception : there is no tableName property in model class 
	        }
	    }
	}
}