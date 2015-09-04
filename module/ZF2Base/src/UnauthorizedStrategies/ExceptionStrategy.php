<?php 
namespace ZF2Base\UnauthorizedStrategies;

use ZF2Base\Interfaces\UnauthorizedStrategyInterface;
use Zend\Mvc\MvcEvent; 

class ExceptionStrategy  extends \Exception implements UnauthorizedStrategyInterface
{
    public function setError(MvcEvent &$event)
    {  
    	$serviceManager = $event->getApplication()->getServiceManager();
    	$resourceDbAdapterFactory = $serviceManager->get('ResourceDbAdapterFactory'); 
        $response = $event->getResponse();  
        $response->setStatusCode(403);
        $event->setResponse($response); 
        $viewVariables['message'] = "Unauthorized Access To : " . $resourceDbAdapterFactory->mainResource;
        $viewVariables['reason'] = "Unauthorized Access";
        $viewVariables['error']  = 'error-unauthorized';
        $viewVariables['exception']  = $this;
        $viewVariables['display_exceptions']  = true;
        $event->getViewModel()->setVariables($viewVariables);
    }  
}