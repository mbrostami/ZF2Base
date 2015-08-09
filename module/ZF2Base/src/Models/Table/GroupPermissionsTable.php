<?php 
namespace ZF2Base\Models\Table;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class GroupPermissionsTable extends TableGateway
{
    public static $tableName = 'group_permissions';
    
    public function getGroupsResources($userId = null)
    { 
        if (!$userId) {
            return null;
        }
        return $this->select(function (Select $select) use ($userId) {
              $select->join('groups', 'groups.id = group_permissions.group_id', array('name'));
              $select->join('user_group', 'user_group.group_id = groups.id', array());
              $select->join('resources', 'group_permissions.resource_id = resources.id', array('resource'));
              if ($userId) {
                    $select->where->equalTo('user_group.user_id', $userId);
              }
              return $select;
        })->toArray();
        
    }
}
