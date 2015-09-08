<?php 
namespace ZF2Base\Plugins;

use Zend\Mvc\Controller\Plugin\AbstractPlugin; 
use ZF2Base\Interfaces\ResourceDbAdapterInterface;

class GetAccess extends AbstractPlugin
{  
    protected $resourceDbAdapter;
    
    public function __construct(ResourceDbAdapterInterface $resourceDbAdapter)
    {
        $this->resourceDbAdapter = $resourceDbAdapter;  
    }    
    
    public function __invoke($resource, $getValue = false)
    {
        return $this->resourceDbAdapter->checkResource($resource);
    }
}