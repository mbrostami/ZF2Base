<?php 

namespace ZF2Base\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;  
use ZF2Base\Interfaces\ResourceInterface;
use ZF2Base\Resources\DefaultResource;

class ResourceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $resourceClass = '\ZF2Base\Resources\\' . ucwords($config['zf2base_config']['resource_class']) . 'Resource';
        if (class_exists($resourceClass)) {
            $resource = new $resourceClass($serviceLocator);
            if ($resource instanceof ResourceInterface) {
                return $resource;
            }
        }   
        return new DefaultResource($serviceLocator);
    }   
}