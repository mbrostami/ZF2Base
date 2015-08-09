<?php 
namespace ZF2Base\Interfaces;

use Zend\Mvc\MvcEvent;

interface UnauthorizedStrategyInterface
{
    public function setError(MvcEvent &$event);   
}