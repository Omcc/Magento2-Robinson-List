<?php


namespace Mnm\Iys\Plugin\Manage;



use Mnm\Iys\Model\SubscriptionInformation;
use Mnm\Iys\Model\SubscriberFactory;
use Mnm\Iys\Model\StatusCheck;




class IysSave
{

    protected $subscriberFactory;
    protected $dateTime;
    protected $statusCheck;


    public function __construct(SubscriptionInformation $subscriptionInfoFetcher,SubscriberFactory $subscriberFactory,StatusCheck $statusCheck)
    {
        $this->subscriptionInfoFetcher = $subscriptionInfoFetcher;
        $this->subscriberFactory = $subscriberFactory;
        $this->statusCheck = $statusCheck;

    }

      public function afterExecute(\Magento\Newsletter\Controller\Manage\Save $subject)
      {




          $postedData = $subject -> getRequest()->getParams();


          $subscription = $this->subscriptionInfoFetcher->fetchSubscriptionId();


          $this->statusCheck->setSubscriberId($subscription);

          $this->statusCheck->setParamData($postedData);

          $this->statusCheck->startCheck();












      }
}
