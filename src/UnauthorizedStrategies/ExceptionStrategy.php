<?php 
namespace ZF2Base\UnauthorizedStrategies;

use ZF2Base\Interfaces\UnauthorizedStrategyInterface;
use Zend\Mvc\MvcEvent; 
use Zend\View\Model\ViewModel;

class ExceptionStrategy  extends \Exception implements UnauthorizedStrategyInterface
{
    
    const EXCEPTION_ERROR = 'error.zf2base.unauthorized.exception';
    
    public function setError(MvcEvent &$event)
    {  
    	$serviceManager = $event->getApplication()->getServiceManager();
    	$resourceDbAdapterFactory = $serviceManager->get('ResourceDbAdapterFactory');  
        $event->setError(self::EXCEPTION_ERROR); 
        $message = sprintf("Unauthorized Access To : %s", $resourceDbAdapterFactory->mainResource);
        $event->setParam('exception', new self($message, 403)); 
        $app = $event->getTarget();
        $app->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event); 
    }  
}