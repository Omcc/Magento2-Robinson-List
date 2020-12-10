<?php


namespace Mnm\Iys\Model\ResourceModel\Subscriber\Subscriber;

use Mnm\Iys\Model\ResourceModel\Subscriber\Subscriber as SubscriberResourceModel;
use Mnm\Iys\Model\Subscriber\Subscriber as SubscriberModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(SubscriberModel::class,SubscriberResourceModel::class);
    }
}
