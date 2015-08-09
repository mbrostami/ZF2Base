<?php 
namespace ZF2Base\UnauthorizedStrategies;

use ZF2Base\Interfaces\UnauthorizedStrategyInterface;
use Zend\Mvc\MvcEvent;

class ExceptionStrategy implements UnauthorizedStrategyInterface
{
    public function setError(MvcEvent &$event)
    {  
        $response = $event->getResponse();
        $response->setStatusCode(403);
        $event->setResponse($response);
    }   
}