<?php


namespace Mnm\Iys\Observer\Register;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

use Mnm\Iys\Model\StatusCheck;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Mnm\Iys\Model\SubscriptionInformation;

class FooterRegister implements ObserverInterface
{

    protected $statusCheck;
    protected $remoteAddress;
    protected $subscriptionInfoFetcher;

    public function __construct(StatusCheck $statusCheck,RemoteAddress $remoteAddress,SubscriptionInformation  $subscriptionInfoFetcher)
    {

        $this->subscriptionInfoFetcher=$subscriptionInfoFetcher;
        $this->statusCheck = $statusCheck;
        $this->remoteAddress = $remoteAddress;

    }
    public function execute(Observer $observer)
    {



        $ip_address = $this->remoteAddress->getRemoteAddress();

        $email = $observer->getEvent()->getSubscriber();


        $postedData = [
            "is_subscribed"=> 1,
            "is_sms_confirmed"=>0,
            "is_call_confirmed"=>0
        ];

        $subscriptionId = $this->subscriptionInfoFetcher->fetchSubscriptionByEmail($email);





        $this->statusCheck->setEmailAddress($email);
        $this->statusCheck->setIpAddress($ip_address);
        $this->statusCheck->setParamData($postedData);
        $this->statusCheck->setFirstname(" ");
        $this->statusCheck->setLastname(" ");
        $this->statusCheck->setSubscriberId($subscriptionId);

        $this->statusCheck->startCheck();


    }
}
