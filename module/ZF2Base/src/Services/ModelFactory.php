<?php 
namespace ZF2Base\Services;
 
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;

class ModelFactory implements FactoryInterface
{
    protected $serviceLocator;
       
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;    
    }
    
    public function getModel($tableNamespace)
    {
        if (class_exists($tableNamespace)) { 
            if (property_exists($tableNamespace, 'tableName')) {
                $tableName = $tableNamespace::$tableName;
                return new $tableNamespace($tableName, $this->serviceLocator->get('ZF2BaseDbAdapter'));
            }
        }
        return ;
    }
}
