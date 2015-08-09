<?php 

namespace ZF2Base\Resources;
 
use ZF2Base\Interfaces\ResourceInterface;
use Zend\Http\Request;
use Zend\ServiceManager\ServiceManager; 
use Zend\Mvc\MvcEvent;

class DefaultResource implements ResourceInterface
{
    
    const RESOURCE_FORMAT = 'METHOD-MODULE-CONTROLLER-ACTION';
    
    protected $serviceManager;
    
    protected $mvcEvent;
    
    protected $routeMatch;
    
    protected $request;
    
    protected $resource;
    
    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }
    
    public function getRequest()
    {
        if ($this->routeMatch) {
            return $this->request;
        }
    }
    
    public function getMethod()
    {
        if ($this->routeMatch) {
            return strtolower($this->request->getMethod());
        }
    }
    
    public function getResource()
    {
        if ($this->resource) {
            return $this->resource;
        }
        if ($this->routeMatch) {  
            $method = $this->getMethod();
            $moduleName = $this->getModuleName(); 
            $controllerName = $this->getControllerName();
            $action = $this->getActionName();
            $this->resource = str_replace(array(
                'METHOD',
                'MODULE',
                'CONTROLLER',
                'ACTION'                
            ), array(
                $method,
                $moduleName,
                $controllerName,
                $action
            ), self::RESOURCE_FORMAT);
            return $this->resource;
        }
    }
    
    public function getControllerName()
    {
        if ($this->routeMatch) { 
            $paramsArray = explode('\\', $this->routeMatch->getParam('controller'));
            return strtolower($paramsArray[2]); 
        }
    }
    
    public function getActionName()
    {
        if ($this->routeMatch) { 
            return $this->routeMatch->getParam('action');
        }
    }
    
    public function getModuleName()
    {
        if ($this->routeMatch) {
            $paramsArray = explode('\\', $this->routeMatch->getParam('controller'));
            return strtolower($paramsArray[0]);
        }  
    }
    
    public function setMvcEvent(MvcEvent $mvcEvent)
    {
        $this->mvcEvent = $mvcEvent;
        $this->routeMatch = $mvcEvent->getRouteMatch();
        $this->request = $mvcEvent->getRequest();
    }
}