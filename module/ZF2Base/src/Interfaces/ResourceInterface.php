<?php 
namespace ZF2Base\Interfaces;
 
use Zend\ServiceManager\ServiceManager; 
use Zend\Mvc\MvcEvent;

interface ResourceInterface
{
    public function __construct(ServiceManager $serviceManager);
    
    public function getRequest();
    
    public function getMethod();
    
    public function getResource();
    
    public function getControllerName();
    
    public function getActionName();
    
    public function getModuleName();
    
    public function setMvcEvent(MvcEvent $mvcEvent);
}