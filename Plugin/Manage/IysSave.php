<?php


namespace Mnm\Iys\Plugin\Manage;


use Mnm\Iys\Model\SubscriptionInformation;
use Mnm\Iys\Model\SubscriberFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class IysSave
{

    public static $permTypes = array(
        "is_sms_confirmed"=>1,
        "is_call_confirmed"=>2,
        'is_subscribed'=>3
    );

    protected $_date;
    protected $subscriberFactory;
    public function __construct(SubscriptionInformation $subscriptionInfoFetcher,SubscriberFactory $subscriberFactory,TimezoneInterface $timezone)
    {
        $this->subscriptionInfoFetcher = $subscriptionInfoFetcher;
        $this->subscriberFactory = $subscriberFactory;
        $this->_date=$timezone;

    }

      public function afterExecute(\Magento\Newsletter\Controller\Manage\Save $subject)
      {

          $postedData = $subject -> getRequest()->getParams();

          $subscription = $this->subscriptionInfoFetcher->fetchSubscriptionId();

          foreach($postedData as $permission => $val)
          {
              if($permission === "form_key")
              {
                  continue;
              }

              $model = $this->subscriberFactory->create();

              $permType = IysSave::$permTypes[$permission];

              $data = [
                  "subscriber_id"=>$subscription,
                  "perm_type_id"=>$permType,
                  "perm_source_id"=>1,
                  "status"=>$val,
                  "value"=>'asda',
                  "change_status_at"=>$this->_date->date()->format("d/m/y H:i:s")];
              $model->setData($data);
              $saveData = $model->save();
          }

      }
}
