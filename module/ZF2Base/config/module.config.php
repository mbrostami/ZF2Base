<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(  
    'zf2base_config' => array(
        
        'adapter_class'  => 'default',
        
        'resource_class' => 'default',
        
        'resource_db_adapter'  => 'default',
        
        'resource_unauthorized_strategy'  => 'exception',
         
        'model_services_identity_pattern' => '(.+)Model(.+)',
        
        'authentication_config'  => array(
            "groups_table_name"		          => "groups",
            "usergroup_table_name"		      => "user_group",
            "users_table_name"		          => "users",
            "identity_column" 		          => "username",
            "credential_column"    	          => "password",
            "credential_treatment" 	          => "?",
            "credential_treatment_as_client"  => "?",
            "session_name" 			          => "logged_in_user",
            "fetch_columns" 		          => array(
                "id",
                "username" ,
                "level",
                "user_groups"
            ),
            "ignore_columns" 		          => null
        ),
    ), 
    'service_manager' => array(
        'factories' => array(
            'ResourceFactory' => 'ZF2Base\Services\ResourceFactory', 
            'ModelFactory' => 'ZF2Base\Services\ModelFactory',
            'ResourceDbAdapterFactory' => 'ZF2Base\Services\ResourceDbAdapterFactory',
        ),
        'aliases' => array(
            'ZF2BaseDbAdapter' => 'Zend\Db\Adapter\Adapter',
            'Zend\Authentication\AuthenticationService' => 'AuthenticationService'
        ),
        'abstract_factories' => array(
            'ZF2Base\Services\ModelAbstractFactory'
        ),
        ////ZendDeveloperTools Toolbars
        'invokables' => array(
            'permission.toolbar' => 'ZF2Base\Toolbar\Collector\PermissionCollector',
        ),
    ),
    'controllers' => array(
        'abstract_factories' => array(
            'ZF2Base\Services\ControllerAbstractFactory'
        )
    ),
    
    ////ZendDeveloperTools Toolbars 
    'zenddevelopertools' => array(
        'profiler' => array(
			'collectors' => array(
			    'permission.toolbar' => 'permission.toolbar',
			),
        ),
        'toolbar' => array(
			'entries' => array(
			    'permission.toolbar' => 'zend-developer-tools/toolbar/permission-data',
			),
        ),
    ),
    'view_manager' => array( 
        'template_map' => array(
            'zend-developer-tools/toolbar/permission-data'
            => __DIR__ . '/../view/zend-developer-tools/toolbar/permission-data.phtml', 
        ),
    )
);
