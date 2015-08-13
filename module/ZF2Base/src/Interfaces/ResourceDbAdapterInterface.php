<?php 
namespace ZF2Base\Interfaces;
 
use Zend\ServiceManager\ServiceManager;  
use ZF2Base\Services\Authentication;

interface ResourceDbAdapterInterface
{
    public function __construct(ServiceManager $serviceManager, Authentication $authenticationService);
    public function setMainResource($mainResource);
}