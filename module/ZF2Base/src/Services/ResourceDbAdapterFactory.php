<?php 

namespace ZF2Base\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;   
use ZF2Base\Abstracts\AbstractResourceDbAdapter;
use ZF2Base\Adapters\DefaultResourceDbAdapter;

class ResourceDbAdapterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $authenticationService = $serviceLocator->get('AuthenticationService');
        $resourceDbAdapterClassName = '\ZF2Base\Adapters\\' . ucwords($config['zf2base_config']['resource_db_adapter']) . 'ResourceDbAdapter';
        if (class_exists($resourceDbAdapterClassName)) {
            $resourceDbAdapterClass = new $resourceDbAdapterClassName($serviceLocator, $authenticationService);
            if ($resourceDbAdapterClass instanceof AbstractResourceDbAdapter) {
                $resourceFactory = $serviceLocator->get('ResourceFactory'); 
                $resource = $resourceFactory->getResource();
                $resourceDbAdapterClass->setMainResource($resource);
                return $resourceDbAdapterClass; 
            }
        }
        return new DefaultResourceDbAdapter($serviceLocator, $authenticationService);
    }   
}