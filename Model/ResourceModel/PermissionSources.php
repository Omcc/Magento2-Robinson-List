<?php


namespace Mnm\Iys\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PermissionSources extends AbstractDb
{
    const MAIN_TABLE = 'mnm_permission_sources';
    const ID_FIELD_NAME = 'id';


    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE,self::ID_FIELD_NAME);
    }
}
