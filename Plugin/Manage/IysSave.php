<?php


namespace Mnm\Iys\Plugin\Manage;



use Magento\Catalog\Plugin\Model\AttributeSetRepository\RemoveProductsTest;
use Mnm\Iys\Model\SubscriptionInformation;
use Mnm\Iys\Model\SubscriberFactory;
use Mnm\Iys\Model\StatusCheck;
use Mnm\Iys\Helper\Data;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;





class IysSave
{

    protected $subscriberFactory;
    protected $dateTime;
    protected $statusCheck;
    protected $subscriptionInfoFetcher;
    protected $remoteAddress;


    public function __construct(SubscriptionInformation $subscriptionInfoFetcher,SubscriberFactory $subscriberFactory,StatusCheck $statusCheck,RemoteAddress $remoteAddress)
    {
        $this->subscriptionInfoFetcher = $subscriptionInfoFetcher;
        $this->subscriberFactory = $subscriberFactory;
        $this->statusCheck = $statusCheck;
        $this->remoteAddress=$remoteAddress;


    }

      public function afterExecute(\Magento\Newsletter\Controller\Manage\Save $subject)
      {



          $ip_address = $this->remoteAddress->getRemoteAddress();

          $postedData = $subject -> getRequest()->getParams();

          $subscription = $this->subscriptionInfoFetcher->fetchSubscriptionId();

          $this->statusCheck->setSubscriberId($subscription);

          $this->statusCheck->setParamData($postedData);

          $this->statusCheck->setIpAddress($ip_address);


          $this->statusCheck->startCheck();





      }
}
