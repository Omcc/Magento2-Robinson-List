<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Mnm\Iys\Model;

use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session;

/**
 * Checks guest subscription by email.
 */
class SubscriptionInformation
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ResourceConnection $resourceConnection
     * @param StoreManagerInterface $storeManager
     */

    private $customerSession;

    public function __construct(ResourceConnection $resourceConnection, StoreManagerInterface $storeManager,Session $customerSession)
    {
        $this->resourceConnection = $resourceConnection;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
    }

    /**
     * Check is subscribed by email
     *
     * @param string $subscriberEmail
     * @return bool
     */
    public function fetchSubscriptionId()
    {

        $storeId = $this->storeManager->getStore()->getId();
        $customerId = $this->customerSession->getCustomer()->getId();
        $connection = $this->resourceConnection->getConnection();

        $select = $connection
            ->select()
            ->from($this->resourceConnection->getTableName('newsletter_subscriber'))
            ->where('store_id = ?', $storeId)
            ->where('customer_id = ?',$customerId)
            ->limit(1);

        return $connection->fetchOne($select);

    }


}
