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
Besides resources for route permissions, you can add resources and check access in controller or view. `$this->hasAccess('resourceName')`  
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

### Configs

  Open `ZF2Base/config/module.config.php`


## Installation

  * Import zf2base.sql to database.
  * Add ZF2Base module to your project and enable it.
  * Define group and users in databse.  
  * Define resources which you need in resources table.
  * Assign resources to group or user in databse (user_permissions | group_permissions).
