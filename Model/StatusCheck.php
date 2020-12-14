<?php


namespace Mnm\Iys\Model;
use Magento\Framework\App\ObjectManager;

use Mnm\Iys\Model\ResourceModel\Subscriber\CollectionFactory as SubscriberCollectionFactory;
use Mnm\Iys\Model\SubscriberFactory;
use Mnm\Iys\Model\SubscriptionInformation;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Mnm\Iys\Model\RecordMobilDev;

use Mnm\Iys\Model\IysSubscriptionManager;


class StatusCheck
{
    public static $permTypes = array(
        "is_sms_confirmed"=>1,
        "is_call_confirmed"=>2,
        'is_subscribed'=>3
    );

    private $subscriberId;
    private $subscriberCollectionFactory;
    private $paramData;
    private $subscriberFactory;
    private $dateTime;
    private $subscriptionInfoFetcher;
    private $iysSubscriptionManager;




    public function __construct(SubscriberCollectionFactory $subscriberCollectionFactory,SubscriberFactory $subscriberFactory,SubscriptionInformation $subscriptionInfoFetcher,IysSubscriptionManager $iysSubscriptionManager)
    {

        $this->subscriptionInfoFetcher = $subscriptionInfoFetcher;
        $this->subscriberCollectionFactory=$subscriberCollectionFactory;
        $this->subscriberFactory = $subscriberFactory;
        $this->dateTime = ObjectManager::getInstance()->get(
            DateTime::class
        );
        $this->iysSubscriptionManager=$iysSubscriptionManager;




    }

    public function setSubscriberId($subscriberId)
    {
        $this->subscriberId=$subscriberId;
    }

    public function setParamData($paramData)
    {
        $this->paramData = $paramData;
    }

    public function startCheck()
    {
        if($this->isSubscriberRecorded())
        {

            $this->recordSubscriber();

            $this->initialSubscribeToQueue();
        }
    }

    public function initialSubscribeToQueue()
    {



        $customerId = $this->subscriptionInfoFetcher->getCustomerId();
        $smsPerm = $this->paramData['is_sms_confirmed'];
        $callPerm = $this->paramData['is_call_confirmed'];

        $email = $this->subscriptionInfoFetcher->getEmailAddress();

        $emailPerm = $this->paramData['is_subscribed'];

        $nameComposition=$this->subscriptionInfoFetcher->getName();
        $nameArray = explode(" ",$nameComposition);
        $firstName = $nameArray[0];
        $lastName = $nameArray[1];

        $source = 1;
        $date = $this->dateTime->gmtDate();
        $individual=1;
        $corporate=0;
        $note="dummy note";
        $phoneNumber="";



        $recordMobilDev = new RecordMobilDev($customerId,$phoneNumber,$smsPerm,$emailPerm,$callPerm,$email,$firstName,$lastName,$source,$date,$individual,$corporate,$note);

        $this->iysSubscriptionManager->subscribe($recordMobilDev);
        exit(1);

    }




    public function isSubscriberRecorded()
    {

        try{
            $subscriberCollection = $this->subscriberCollectionFactory->create();
        }catch(\Exception $e)
        {
            var_dump($e);
            exit(1);
        }

        $subscriberRecords =  $subscriberCollection->addFieldToSelect('id')->addFieldtoFilter("subscriber_id",$this->subscriberId)->load()->getData();

        return $subscriberRecords?true:false;



    }

    public function recordSubscriber()
    {
        foreach($this->paramData as $permission => $val)
        {

            if($permission === "form_key")
            {
                continue;
            }

            $model = $this->subscriberFactory->create();


            $permType = StatusCheck::$permTypes[$permission];



            $data = [
                "subscriber_id"=>$this->subscriberId,
                "perm_type_id"=>$permType,
                "perm_source_id"=>1,
                "status"=>$val,
                "value"=>'asda',
                "change_status_at"=>$this->dateTime->gmtDate()];
            $model->setData($data);



            try{
                $saveData = $model->save();
            }catch (\Exception $e)
            {
                var_dump($e);
                exit(1);
            }


        }

    }

    public function isSMSPermChanged($smsParam)
    {
        try{
            $subscriberCollection = $this->subscriberCollectionFactory->create();
        }catch(\Exception $e)
        {
            var_dump($e);
            exit(1);
        }
        $smsPermConfirmed = $subscriberCollection->addFieldToSelect('status')->addFieldToFilter('subscriber_id',$this->subscriberId)->addFieldToFilter('perm_type_id',3)->load()->getData();

        var_dump($smsPermConfirmed);
        exit(1);
    }

    public function isCallPermChanged($callParam)
    {
        return;
    }

    public function isPhonePermChanged($phoneParam)
    {
        return;
    }

    public function setPhonePermFlag()
    {
        return;
    }

    public function setCallPermFlag()
    {
        return;
    }

    public function setSmsPermFlag()
    {
        return;
    }

    public function isRecordUpdated()
    {
        return;
    }
}
