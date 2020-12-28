<?php


namespace Mnm\Iys\Observer\Login;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

use Mnm\Iys\Publisher\IysRead;
use Mnm\Iys\Model\IysSubscriptionManager;
use Mnm\Iys\Model\SubscriptionInformation;

class CustomerLogin implements ObserverInterface
{
    protected $publisher;
    protected $iysSubscriptionManager;
    protected $subscriptionInfoFetcher;
    public function __construct(IysRead $iysReadPublisher,IysSubscriptionManager $iysSubscriptionManager,SubscriptionInformation $subscriptionInfoFetcher)
    {
        $this->publisher=$iysReadPublisher;
        $this->iysSubscriptionManager=$iysSubscriptionManager;
        $this->subscriptionInfoFetcher=$subscriptionInfoFetcher;
    }

    public function execute(Observer $observer)
    {


        $customerData= $observer->getEvent()->getCustomer()->getData();
        $customerId = $customerData['entity_id'];
        $email = $customerData['email'];
        $storeId = $customerData['store_id'];


        $subscriberId= $this->subscriptionInfoFetcher->fetchSubscriptionId($customerId);



        $data=[
            "customerId"=>$customerId,
            "subscriberId"=>$subscriberId,
            "email"=>$email,
            "storeID"=>$storeId
            ];



        $this->iysSubscriptionManager->readSubscription($data);




    }
}
