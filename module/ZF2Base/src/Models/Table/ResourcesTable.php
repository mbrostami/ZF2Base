<?php 
namespace ZF2Base\Models\Table;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class ResourcesTable extends TableGateway
{
    public static $tableName = 'resources';
    
    public function getResources()
    {  
        return $this->select(function (Select $select) { 
              // $select->join('sub_resources', 'resources.id = sub_resources.id', array('resource'));
              return $select;
        })->toArray(); 
    }
}
