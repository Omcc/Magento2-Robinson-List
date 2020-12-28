<?php


namespace Mnm\Iys\Observer\Register;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

use Mnm\Iys\Model\StatusCheck;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

class FooterRegister implements ObserverInterface
{

    protected $statusCheck;
    protected $remoteAddress;

    public function __construct(StatusCheck $statusCheck,RemoteAddress $remoteAddress)
    {

        $this->statusCheck = $statusCheck;
        $this->remoteAddress = $remoteAddress;

    }
    public function execute(Observer $observer)
    {

        $subscriber = $observer->getEvent()->getSubscriber();
        $subscriberData = $subscriber->getData();
        $ip_address = $this->remoteAddress->getRemoteAddress();

        $email = $subscriberData['subscriber_email'];
        $status = $subscriberData['subscriber_status'];
        $subscriberId = $subscriberData['subscriber_id'];

        $postedData = [
            "is_subscribed"=> $status,
            "is_sms_confirmed"=>0,
            "is_call_confirmed"=>0
        ];



        $this->statusCheck->setEmailAddress($email);
        $this->statusCheck->setIpAddress($ip_address);
        $this->statusCheck->setParamData($postedData);
        $this->statusCheck->setFirstname(" ");
        $this->statusCheck->setLastname(" ");
        $this->statusCheck->setSubscriberId($subscriberId);
        $this->statusCheck->startCheck();


    }
}
