<?php


namespace Mnm\Iys\Observer;


use Magento\Framework\Event\Observer;

use Mnm\Iys\Model\SubscriptionInformation;


class ListenSubscribe implements \Magento\Framework\Event\ObserverInterface
{
    protected $subscriptionInfoFetcher;
    public function __construct(SubscriptionInformation $subscriptionInfoFetcher)
    {
        $this->subscriptionInfoFetcher = $subscriptionInfoFetcher;

    }

    public function execute(Observer $observer)
    {

        return;





    }
}
