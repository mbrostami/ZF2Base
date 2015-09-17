# ZF2Base
Base ACL+Authentication module for Zend Framrwork 2   
ZF2Base can auto check that a user has access to method|module|controller|action or not besides you can add custom permissions and check access in view or controller inside.  

## Scenario

  1. User requests a url : `/application/index/index`
  2. ZF2Base (ResourceFactory) creates a default resource string : `METHOD-MODULE-CONTROLLER-ACTION`. So our current resource should be : `get-application-index-index`
  3. ZF2Base (ResourceDbAdapter) calls authentication service and gets all allowed resources from database.
  4. ZF2Base checks that current resource is in allowed resources or not. (exact match - if false then with regex).
  5. If access is denied then throws an exception (by strategies), otherwise continues to load controller and action.

You can write your own ResourceFactory.  
You can write your own ResourceDbFactory.  
Besides resources for route permissions, you can add resources and check access in controller/view. `$this->getAccess('resourceName')`  
For working route access you don't need to write any additional code in your modules.  
You can use regex pattern in your resources. If you define `get-*` as a resource, who has access to this resource, will have access to all get requests. You can do that for one module or one controller and so on. e.g : `(post|get)-admin-*` for access to all controllers inside admin module. 


## Requirements 

 * [Zend Framework 2](https://github.com/zendframework/zf2)
 
## Information

### Database

  * `groups` | user groups table
  * `users` | users table
  * `user_group` | groups of each user
  * `resources` | our permissions are defined in this table
  * `sub_resources` | if a resource is accessible by a user/group then that user/group has access to it's sub resources
  * `group_permissions` | which group has access to which resources (value field is for using in the future)
  * `user_permissions` | which user has or HAS NOT(deny) access to which resources

If you insert a record in `user_permissions` with `deny` value, it means that this access is denied and will override on user group same resource.  
If you insert a record in `resources` with `default` type, it means that every body has access to this resource.  
Value columns in `group_permissions` and `user_permissions` is for using in the future.

### Configs

```php
<?php 

return array(  
    'zf2base_config' => array(
         
        // you can create you custom resource class and place name it here 
        // default means that we want to use ZF2Base/Resources/DefaultResource.php class as our resource class.
        // "Resource" is a static suffix
        'resource_class' => 'default',
        
        // this points to ZF2Base/Adapters/DefaultResourceDbAdapter.php 
        // example : ZF2Base/Adapters/CustomResourceDbAdapter.php | config value : 'custom'
        'resource_db_adapter'  => 'default',
        
        // this points to ZF2Base/UnauthorizedStrategies/ExceptionStrategy.php
        // example : ZF2Base/UnauthorizedStrategies/RedirectStrategy.php | config value : 'redirect'
        'resource_unauthorized_strategy'  => 'exception',
         
        // when you want to use models class in other modules you can get that with service manager
        // for example you can use $sm->get('MyModule/Db/Models/MyTable');
        // for doing this you should define MyTable as a service, but you can give a pattern here to auto create service
        // in above example we can use this pattern '(.+)\/Db\/Models\/(.+)' 
        'model_services_identity_pattern' => '(.+)Model(.+)',
        
        // authentication config 
        'authentication_config'  => array(
            "groups_table_name"		          => "groups",
            "usergroup_table_name"		      => "user_group",
            "users_table_name"		          => "users",
            "identity_column" 		          => "username",
            "credential_column"    	          => "password",
            "credential_treatment" 	          => "?",
            "session_name" 			          => "logged_in_user",
            "fetch_columns" 		          => array(
                "id",
                "username" , 
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
	// By this abstract_factory you can get your model from service manager without define model service. 
        'abstract_factories' => array(
            'ZF2Base\Services\ModelAbstractFactory'
        ),
        // ZendDeveloperTools Toolbars
        'invokables' => array(
            'permission.toolbar' => 'ZF2Base\Toolbar\Collector\PermissionCollector',
        ),
    ),
    
    // Auto create controller as invokable service.
    // If you remove this lines you have to define each controller as an invokable service yourself.
    'controllers' => array(
        'abstract_factories' => array(
            'ZF2Base\Services\ControllerAbstractFactory'
        )
    ),
    
    // ZendDeveloperTools Toolbars 
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
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true, 
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array( 
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
```
## Installation

  * Import zf2base.sql to database.
  * Add ZF2Base module to your project and enable it.
  * Define group and users in database.  
  * Define resources which you need in resources table.
  * Assign resources to group or user in databse (user_permissions | group_permissions).  
  * Install using composer without zf2 `composer require zf2base/zf2base "dev-master"`
  * Install using composer with zf2 `composer require zf2base/zf2base`

## Guide 
  
![ZF2Base Simple Guide](https://cdn.rawgit.com/mbrostami/ZF2Base/master/zf2base-workflow.jpg)

