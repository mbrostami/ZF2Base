<?php 
namespace ZF2Base\Models\Table;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class UserPermissionsTable extends TableGateway
{
    public static $tableName = 'user_permissions';
    
    public function getUsersResources($userId = null)
    { 
        if (!$userId) {
            return null;
        }
        return $this->select(function (Select $select) use ($userId) { 
              $select->join('resources', 'user_permissions.resource_id = resources.id', array('resource'));
              if ($userId) {
                    $select->where->equalTo('user_permissions.user_id', $userId);
              }
              return $select;
        })->toArray();
        
    }
}
