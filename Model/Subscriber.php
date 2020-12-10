<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mnm\Iys\Model;
use Magento\Framework\Model\AbstractModel;
use Mnm\Iys\Model\ResourceModel\Subscriber as SubscriberResourceModel;

class Subscriber extends AbstractModel
{
    public function _construct()
    {
        $this->_init(SubscriberResourceModel::class);
    }

}
