<?php 
namespace ZF2Base\Models\Table;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class UsersTable extends TableGateway
{
    public static $tableName = 'users'; 
    
    public function getUsersResources($userId)
    {
        if (!$userId) {
            return null;
        }
        return $this->selectWith(function (Select $select) {
//             $select->join('', $on);
        });
    }
    
}
