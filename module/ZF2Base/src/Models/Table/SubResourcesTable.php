<?php 
namespace ZF2Base\Models\Table;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class SubResourcesTable extends TableGateway
{
    public static $tableName = 'sub_resources';
    
    public function getSubResources()
    {  
        return $this->select(function (Select $select) {
              $select->join('resources', 'resources.id = sub_resources.resource_id', array(
                  'resource',
                  'type',
                  'label',
                  'level',
                  'extra_group'
              ));
              return $select;
        })->toArray();
        
    }
}
