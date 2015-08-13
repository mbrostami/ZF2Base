<?php
namespace Sample\Controller;
  
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use Sample\Form\LoginForm;
 
class IndexController extends AbstractActionController 
{ 
    
  	public function indexAction()
  	{  
  	    $this->getServiceLocator()->get("Sample\Model\TestTable");
  	    $hasAccess = $this->getAccess('zf2base-hello');
  	    // $this->identity();
  	    
  	    $loginForm = new LoginForm();
  	    $view['loginForm'] = $loginForm;
  	    $request = $this->getRequest();
  	    if ($request->isPost()) {
  	        $postData = $request->getPost();
  	        $authenticationService = $this->getServiceLocator()->get('AuthenticationService');
  	        if (!isset($postData['logout'])) {
      	        $loginForm->setData($postData);
      	        if ($loginForm->isValid()) {
      	            $formData = $loginForm->getData();
      	            if ($authenticationService->authentication($formData['username'], $formData['password'])) {
      	                $view['message'] = 'Welcome';
      	                // TODO flash messanger
      	            } else {
      	                $view['message'] = 'Error';
      	                // TODO flash messanger
      	            }
      	        }
  	        } else {
  	            $authenticationService->logOut();
  	            return $this->redirect()->refresh();
  	        }
  	    }
  		return new ViewModel($view);
  	}
}