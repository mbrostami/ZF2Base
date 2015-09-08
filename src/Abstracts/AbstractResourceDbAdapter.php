<?php 
namespace ZF2Base\Abstracts;

use Zend\ServiceManager\ServiceManager; 
use ZF2Base\Services\Authentication;
use ZF2Base\Interfaces\ResourceDbAdapterInterface;

/**
 * Abstract class for resource db adapters 
 * Resource db adapter is a layer between permission service and database
 * 
 * @author MB Rostami <mb.rostami.h@gmail.com>
 */
abstract class AbstractResourceDbAdapter implements ResourceDbAdapterInterface
{ 
    /**
     * @var string|null
     */
    public $lastMatchedResource;
    
    /**
     * @var array|null
     */
    public $resources;
    
    /**
     * @var array|null
     */
    public $subResources;
    
    /**
     * @var array|null
     */
    public $groupResources;
    
    /**
     * @var array|null
     */
    public $userResources;
    
    /**
     * @var array|null
     */
    public $allResources;
    
    /**
     * @var array|null
     */
    public $allowedResources;
    
    /**
     * @var array|null
     */
    public $deniedResources;
    
    /**
     * @var \ZF2Base\Services\Authentication|null
     */
    public $authenticatedService;
    
    /** 
     * @var string|null
     */
    public $mainResource; 
    
    /**
     * @var ServiceManager
     */
    protected $serviceManager;
    
    /**
     * @var \ZF2Base\Services\ModelFactory|null
     */
    protected $modelFactory; 
    
    /** 
     * @var object
     */
    protected $userData; 
    
    /**
     * @param ServiceManager $serviceManager
     * @param Authentication $authenticationService
     */
    public function __construct(ServiceManager $serviceManager, Authentication $authenticationService)
    {
        $this->serviceManager = $serviceManager;
        $this->authenticatedService = $authenticationService;
        $this->userData = $authenticationService->checkLogin();
        $this->modelFactory = $serviceManager->get('ModelFactory'); 
    }

    /**
     * Set the very first resource
     * @param string $mainResource
     */
    public function setMainResource($mainResource)
    {
        $this->mainResource = $mainResource;    
    }
    
    /**
     * @param string $resource
     * @return bool
     */
    abstract public function checkResource($resource);
    
    /**
     * @return array
     */
    abstract public function getAllResources();
    
    /**
     * @return array
     */
    abstract public function getAllowedResources();
    
    /**
     * @return array
     */
    abstract public function getDeniedResources();
    
    /**
     * @param integer $userId
     * @return array
     */
    abstract protected function getGroupResources($userId);

    /**
     * @param integer $userId 
     * @return array
     */
    abstract protected function getUserResources($userId);
    
    /**
     * @return array
     */
    abstract protected function getResources();
    
    /**
     * @return array
     */
    abstract protected function getSubResources(); 
    
}
