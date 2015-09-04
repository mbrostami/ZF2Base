<?php 
namespace Sample\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

class LoginForm extends Form
{
    protected $inputFilter; 
    
    public function __construct()
    {
        parent::__construct("login-form");
        $this->setAttribute('method', 'POST');
        $this->setAttribute('action', '');
        
        $username = new Element\Text('username');
        $username->setLabel('Username');
        $username->setAttribute('class', 'form-control');
        
        $password = new Element\Password('password');
        $password->setLabel('Password');
        $password->setAttribute('class', 'form-control');
        
        $submit = new Element\Submit('submit');
        $submit->setValue('Login');
        
        $this->add($username);
        $this->add($password);
        $this->add($submit);
    }
    
    public function getInputFilter()
    {
         if (!$this->inputFilter) {
             $inputFilter = new InputFilter();

             $inputFilter->add(array(
                 'name'     => 'username',
                 'required' => true
             ));
             $inputFilter->add(array(
                 'name'     => 'password',
                 'required' => true
             ));
 
             $this->inputFilter = $inputFilter;
         }

         return $this->inputFilter;
     }
}
?>