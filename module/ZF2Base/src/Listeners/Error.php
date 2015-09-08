<?php 
namespace ZF2Base\Listeners;
 
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\Http\Response;
use Zend\Feed\PubSubHubbub\HttpResponse;
use Zend\EventManager\EventManagerInterface;
use ZF2Base\Interfaces\UnauthorizedStrategyInterface;
use Zend\View\Model\ViewModel;

class Error implements ListenerAggregateInterface
{
    const EVENT_ACCESS_DENIED = 'error.zf2base.unauthorized'; 
      
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->events = $events; 
        $this->listeners[] = $events->attach(self::EVENT_ACCESS_DENIED, array($this, 'unAuthorizedAction'), -100); 
    }
    
    /**
     * Detach all our listeners from the event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
     
    public function unAuthorizedAction(MvcEvent $event)
    {  
        $serviceManager = $event->getApplication()->getServiceManager(); 
        $config = $serviceManager->get('Config');
        $resourceUnauthorizedStrategy = $config['zf2base_config']['resource_unauthorized_strategy'];
        $unauthorizedStrategyClassName = "\ZF2Base\UnauthorizedStrategies\\" . ucwords($resourceUnauthorizedStrategy) . "Strategy";
        if (class_exists($unauthorizedStrategyClassName)) {
            $unauthorizedStrategyClass = new $unauthorizedStrategyClassName();
            if ($unauthorizedStrategyClass instanceof UnauthorizedStrategyInterface) {
                $unauthorizedStrategyClass->setError($event);
            }
        }  
    }  
}