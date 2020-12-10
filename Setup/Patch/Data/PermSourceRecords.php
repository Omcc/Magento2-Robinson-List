<?php


namespace Mnm\Iys\Setup\Patch\Data;


use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Mnm\Iys\Model\ResourceModel\PermissionSources;




class PermSourceRecords implements DataPatchInterface
{

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
        $this->moduleDataSetup->startSetup();
        $table = $this->moduleDataSetup->getTable(PermissionSources::MAIN_TABLE);
        $this->moduleDataSetup->getConnection()->insert($table,[
            'id'=>1,
            'type'=>'HS_2015'
        ]);

        $this->moduleDataSetup->getConnection()->insert($table,[
            'id'=>2,
            'type'=>'HS_KARAR'
        ]);

        $this->moduleDataSetup->getConnection()->insert($table,[
            'id'=>3,
            'type'=>'HS_FIZIKSEL_ORTAM'
        ]);

        $this->moduleDataSetup->getConnection()->insert($table,[
            'id'=>4,
            'type'=>'HS_ISLAK_IMZA'
        ]);

        $this->moduleDataSetup->getConnection()->insert($table,[
            'id'=>5,
            'type'=>'HS_ETKINLIK'
        ]);
        $this->moduleDataSetup->getConnection()->insert($table,[
            'id'=>6,
            'type'=>'HS_ATM'
        ]);

        $this->moduleDataSetup->getConnection()->insert($table,[
            'id'=>7,
            'type'=>'HS_ORTAM'
        ]);

        $this->moduleDataSetup->getConnection()->insert($table,[
            'id'=>8,
            'type'=>'HS_WEB'
        ]);
        $this->moduleDataSetup->getConnection()->insert($table,[
            'id'=>9,
            'type'=>'HS_MOBIL'
        ]);
        $this->moduleDataSetup->getConnection()->insert($table,[
            'id'=>10,
            'type'=>'HS_MESAJ'
        ]);
        $this->moduleDataSetup->getConnection()->insert($table,[
            'id'=>11,
            'type'=>'HS_EPOSTA'
        ]);
        $this->moduleDataSetup->getConnection()->insert($table,[
            'id'=>12,
            'type'=>'HS_CAGRI_MERKEZI'
        ]);
        $this->moduleDataSetup->getConnection()->insert($table,[
            'id'=>13,
            'type'=>'HS_SOSYAL_MEDYA'
        ]);

    }
}
