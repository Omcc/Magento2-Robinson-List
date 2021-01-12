<?php


namespace Mnm\Iys\Model\Ajax\Factory;
use Mnm\Iys\Helper\Data;
use Mnm\Iys\Model\Ajax\IysAjax;


class IysAjaxFactory
{
    protected $iysDataHelper;
    public function __construct(Data $iysDataHelper)
    {
        $this->iysDataHelper=$iysDataHelper;

    }
    public function create()
    {
        $className = $this->iysDataHelper->getIysAjaxClass();
        $classPath = "\Mnm\Iys\Model\Ajax\\" . $className;
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $iysAjaxClass = $_objectManager->create($classPath);
        if($iysAjaxClass instanceof IysAjax == false)
        {
            throw new \Exception(
                'Object is not instance of IysClass'
            );

        }
        return $iysAjaxClass;

    }

}
