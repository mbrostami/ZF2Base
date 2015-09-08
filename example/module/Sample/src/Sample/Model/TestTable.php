<?php 
namespace Sample\Model;

use Zend\Db\TableGateway\TableGateway;

class TestTable extends TableGateway
{
    public static $tableName = 'group_permissions';
     
}