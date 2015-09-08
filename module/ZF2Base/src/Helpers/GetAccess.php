<?php 
namespace ZF2Base\Helpers;
  
use Zend\View\Helper\AbstractHelper;
use ZF2Base\Interfaces\ResourceDbAdapterInterface;

class GetAccess extends AbstractHelper
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