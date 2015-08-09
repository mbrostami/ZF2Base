<?php
namespace ZF2Base\Toolbar\Collector;

use ZendDeveloperTools\Collector\CollectorInterface;
use Zend\Mvc\MvcEvent;

class PermissionCollector implements CollectorInterface
{  
    
	protected $allResources;
	protected $allowedResources;
	protected $deniedPermissions;
	protected $acceptedResource;
	protected $lastMatchedResource;
	  
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
         // this name must same with *collectors* name in the configuration
        return 'permission.toolbar';
    }

    /**
     * {@inheritDoc}
     */
    public function getPriority()
    {
        return 10;
    }

    /**
     * @inheritdoc
     */
    public function collect(MvcEvent $mvcEvent)
    { 
        $resourceDbAdapter = $mvcEvent->getApplication()->getServiceManager()->get('ResourceDbAdapterFactory');
        $isAllowed = true;
        if ($resourceDbAdapter) {
            /* @var $resourceDbAdapter \ZF2Base\Abstracts\AbstractResourceDbAdapter */
            $isAllowed = $resourceDbAdapter->checkResource($resourceDbAdapter->mainResource);
            $this->lastMatchedResource = $resourceDbAdapter->lastMatchedResource;
            if ($isAllowed) {
                $this->acceptedResource = $resourceDbAdapter->mainResource;
            }
        }
        $this->allowedResources = $resourceDbAdapter->getAllowedResources();
        $this->deniedResources  = $resourceDbAdapter->getDeniedResources();
        if (!$this->deniedResources) {
            $this->deniedResources = array();
        }
        if (!$this->allowedResources) {
            $this->allowedResources = array();
        } 
        $this->allResources = array_merge($this->allowedResources, $this->deniedResources); 
    }
    
    public function getPermissionData()
    {  
        $data['allowedResources']   = $this->allowedResources; 
        $data['deniedResources']    = $this->deniedResources; 
        $data['allResources']       = $this->allResources; 
        $data['acceptedResource']   = $this->acceptedResource; 
        $data['lastMatchedResource'] = $this->lastMatchedResource; 
        return $data;
    }
}
