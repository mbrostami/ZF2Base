# ZF2Base
Base ACL module for Zend Framrwork 2 

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
