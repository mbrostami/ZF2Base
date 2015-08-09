<?php
namespace ZF2Base\Listeners;
 
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\Http\Response as HttpResponse;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Mvc\Application as MvcApplication;
use Zend\EventManager\EventManagerInterface;
use Zend\Session\Container;

/** 
 * @author MB Rostami <mb.rostami.h@gmail.com> 
 */

class Access implements ListenerAggregateInterface
{ 
    
	protected $listeners = array();
	protected $events;  
	
	public function attach(EventManagerInterface $events, $priority = 1)
	{
		$this->events = $events;
		$this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'), -100); 
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
	
	public function onRoute(MvcEvent $event)
	{
	    $serviceManager = $event->getApplication()->getServiceManager();
	    
	    $resourceFactory = $serviceManager->get('ResourceFactory'); 
	    $resourceFactory->setMvcEvent($event);
	    $resource = $resourceFactory->getResource();
	    if (!$resource) {
	        return ;
	    } 
	    
	    // If there is no resourceDbAdapter class so we have permission to all resources
	    $isAllowed = true;
	    
	    $resourceDbAdapter = $serviceManager->get('ResourceDbAdapterFactory');
	    if ($resourceDbAdapter) {
	        /* @var $resourceDbAdapter \ZF2Base\Abstracts\AbstractResourceDbAdapter */
	        $isAllowed = $resourceDbAdapter->checkResource($resource);
	    } 
	    if (!$isAllowed) { 
	        $event->getApplication()->getEventManager()->trigger(Error::EVENT_ACCESS_DENIED, $event);
	    }   
	    return ;
	}  
}