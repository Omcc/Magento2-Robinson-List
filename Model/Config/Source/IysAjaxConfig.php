<?php


namespace Mnm\Iys\Model\Config\Source;


class IysAjaxConfig implements \Magento\Framework\Data\OptionSourceInterface
{

    public function toOptionArray()
    {
        return [
            ['value' => 'MobilDevAjax', 'label' => __('MobilDev')],
            ['value' => 'DerinmorAjax', 'label' => __('Derinmor')],
        ];
    }
}
