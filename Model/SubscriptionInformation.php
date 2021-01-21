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
use Mnm\Iys\Model\ResourceModel\Subscriber\CollectionFactory as SubscriberCollectionFactory;

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

    private $subscriberCollectionFactory;

    public function __construct(ResourceConnection $resourceConnection, StoreManagerInterface $storeManager,Session $customerSession,SubscriberCollectionFactory $subscriberCollectionFactory)
    {
        $this->resourceConnection = $resourceConnection;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->subscriberCollectionFactory=$subscriberCollectionFactory;
    }

    /**
     * Check is subscribed by email
     *
     * @param string $subscriberEmail
     * @return bool
     */
    public function fetchSubscriptionId($customerId=NULL)
    {

        $storeId = $this->storeManager->getStore()->getId();

        if(!$customerId)
            $customerId = $this->getCustomerId();
        $connection = $this->resourceConnection->getConnection();



        $select = $connection
            ->select()
            ->from($this->resourceConnection->getTableName('newsletter_subscriber'))
            ->where('store_id = ?', $storeId)
            ->where('customer_id = ?',$customerId)
            ->limit(1);

        return $connection->fetchOne($select);

    }
    public function fetchSubscriptionByEmail($email=NULL)
    {
        $storeId = $this->storeManager->getStore()->getId();



        if(!$email)
            return;
        $connection = $this->resourceConnection->getConnection();
        $select = $connection
            ->select()
            ->from($this->resourceConnection->getTableName('newsletter_subscriber'))
            ->where('store_id = ?', $storeId)
            ->where('subscriber_email = ?',$email)
            ->limit(1);

        return $connection->fetchOne($select);


    }

    public function getCustomerId()
    {
        $customerId = $this->customerSession->getCustomer()->getId();
        return $customerId;
    }

    public function getEmailAddress()
    {

        return $this->customerSession->getCustomer()->getEmail();
    }

    public function getName()
    {
        return $this->customerSession->getCustomer()->getName();
    }

    public function getMailRecord(){
        try{
            $subscriberCollection = $this->subscriberCollectionFactory->create();

        }catch(\Exception $e)
        {
            var_dump($e);
            exit(1);
        }

        return $subscriberCollection->addFieldToFilter('subscriber_id',$this->fetchSubscriptionId())->addFieldToFilter('perm_type_id',StatusCheck::$permTypes['is_subscribed'])->load();

    }
    public function getSmsRecord(){
        try{
            $subscriberCollection = $this->subscriberCollectionFactory->create();

        }catch(\Exception $e)
        {
            var_dump($e);
            exit(1);
        }

        return $subscriberCollection->addFieldToFilter('subscriber_id',$this->fetchSubscriptionId())->addFieldToFilter('perm_type_id',StatusCheck::$permTypes['is_sms_confirmed'])->load();

    }

    public function getCallRecord(){
        try{
            $subscriberCollection = $this->subscriberCollectionFactory->create();

        }catch(\Exception $e)
        {
            var_dump($e);
            exit(1);
        }

        return $subscriberCollection->addFieldToFilter('subscriber_id',$this->fetchSubscriptionId())->addFieldToFilter('perm_type_id',StatusCheck::$permTypes['is_call_confirmed'])->load();

    }







}
