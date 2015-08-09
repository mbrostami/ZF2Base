<?php
namespace Sample\Controller;
  
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
 
class IndexController extends AbstractActionController 
{ 
    
  	public function indexAction()
  	{  
  	    $this->getServiceLocator()->get("Sample\Model\TestTable");
  	    $this->identity();
  	    
  	    
  		return new ViewModel();
  	}
}