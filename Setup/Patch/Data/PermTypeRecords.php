<?php


namespace Mnm\Iys\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;
use Mnm\Iys\Model\ResourceModel\PermissionTypes as PermissionTypesResourceModel;

class PermTypeRecords implements DataPatchInterface
{
    /** @var ModuleDataSetupInterface */
    protected $moduleDataSetup;
    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }


    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $setup = $this->moduleDataSetup;
        $setup->startSetup();

        $table = $setup->getTable(PermissionTypesResourceModel::MAIN_TABLE);
        $setup->getConnection()->insert($table,[
            'permission_id'=>1,
            'type'=>'SMS'
        ]);

        $setup->getConnection()->insert($table,[
            'permission_id'=>2,
            'type'=>'Call'
        ]);

        $setup->getConnection()->insert($table,[
            'permission_id'=>3,
            'type'=>'Email'
        ]);

        $setup->endSetup();
    }
}
