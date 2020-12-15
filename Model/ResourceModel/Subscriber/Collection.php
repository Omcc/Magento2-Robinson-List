<?php


namespace Mnm\Iys\Model\ResourceModel\Subscriber;

use Mnm\Iys\Model\ResourceModel\Subscriber as SubscriberResourceModel;
use Mnm\Iys\Model\Subscriber as SubscriberModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(SubscriberModel::class,SubscriberResourceModel::class);
    }





}
