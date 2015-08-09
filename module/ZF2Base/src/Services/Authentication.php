<?php

/**
 * Authenticate user | Check logged in users | Set credential from config options
 * @author Mohammad Rostami
 * @package ZF2Base/Services 
 */

namespace ZF2Base\Services;

use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;
use Zend\ServiceManager\ServiceManager;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class Authentication  extends AuthenticationService
{
	/** 
	 * @var object loggedin user's data
	 */
	public $userData;
	
	public $isLoggedIn = false;
	
	public $redirectOnAuthFail;
	
	public $redirectRoute;
	/**
	 * @var Authentication Adapter 
	 */
	private $authenticationAdapter;
	/**
	 * @var Authentication Service 
	 */
	private $authenticationService;
 
	/**
	 * in config.json file : 
	 * $authenticationConfig['fetch_columns'] = set to null if you want to use ignore_columns instead
	 * $authenticationConfig['ignore_columns'] = set to null if you want to use fetch_columns instead
	 * @var config information from config.json
	 */
	private $authenticationConfig;
	
	
	/**
	 * Get service manager instance from module on initilizing
	 * @param ServiceManager $ServiceManager
	 * @return \Zend\Authentication\AuthenticationService
	 */
	public function __construct(ServiceManager $serviceManager)
	{
		$config 					 = $serviceManager->get("Config"); 
		$this->authenticationConfig  = $config['zf2base_config']['authentication_config']; 
		$dbAdapter 					 = $serviceManager->get("ZF2BaseDbAdapter");  
		$authAdapter 				 = new AuthAdapter($dbAdapter);
		
		$authAdapter->setTableName($this->authenticationConfig['users_table_name']);
		$authAdapter->setIdentityColumn($this->authenticationConfig['identity_column']);
		$authAdapter->setCredentialColumn($this->authenticationConfig['credential_column']); 
		$authAdapter->setCredentialTreatment($this->authenticationConfig['credential_treatment']);
		
		$this->setAdapter($authAdapter);
		$this->setStorage(new Session($this->authenticationConfig['session_name']), $authAdapter);
		$this->authenticationAdapter = $authAdapter; 
	}
	
	public function authentication($identity, $credential)
	{ 
		$this->authenticationAdapter
					->setIdentity($identity)
					->setCredential($credential) ;
		if (is_array($this->authenticationConfig['fetch_columns']) && in_array('level',$this->authenticationConfig['fetch_columns'])) {
			$Select = $this->authenticationAdapter->getDbSelect();
			$Select->join(array('ug' => $this->authenticationConfig['usergroup_table_name']), 'users.id = ug.user_id',array(),Select::JOIN_LEFT);
			$Select->join(array('g' => $this->authenticationConfig['groups_table_name']), 'g.id = ug.group_id',array('level' => new Expression('min(level)'), 'user_groups' =>  new Expression('concat(g.id)')),Select::JOIN_LEFT);
		}
		
		$this->setStorage(new Session($this->authenticationConfig['session_name']), $this->authenticationAdapter);
		$checkLogin = $this->authenticate();
		if ($checkLogin->isValid()) {
			$storage = $this->getStorage($this->authenticationConfig['session_name']);
			$storage->write($this->authenticationAdapter->getResultRowObject($this->authenticationConfig['fetch_columns'], $this->authenticationConfig['ignore_columns']));
			$this->userData = $this->getIdentity();
			$this->isLoggedIn = true; 
			return true;
		} else {
			$this->isLoggedIn = false;
			return false; 
		}
	} 
	
	public function logOut()
	{
		$this->setStorage(new Session($this->authenticationConfig['session_name']));
		$this->clearIdentity();
		$this->isLoggedIn = false;
		$this->userData = false;
		return true;
	}
	
	public function checkLogin()
	{
		$this->userData  =  $this->getIdentity();
		$this->isLoggedIn  = true;
		if (!$this->userData) {
			$this->isLoggedIn  = false;
			// $this->userData = new \stdClass();
			// foreach ($this->authenticationConfig['fetch_columns'] as $columnName) {
			// 	   $this->userData->$columnName = null;
			// } 
		} 
		return $this->userData;
	} 
}