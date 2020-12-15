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
    private $isPermChanged;







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


        if(!$this->isSubscriberRecorded())
        {
            $this->recordSubscriber();
            $this->SubscribeToQueue();
            return;
        }

        if($smsRecord = $this->isSmsPermChanged())
        {
            $smsRecord->setDataToAll('status',$this->paramData['is_sms_confirmed'])->save();
        }
        if($callRecord = $this->isCallPermChanged())
        {
            $callRecord->setDataToAll('status',$this->paramData['is_call_confirmed'])->save();
        }
        if($mailRecord = $this->isMailPermChanged())
        {
            $mailRecord->setDataToAll('status',$this->paramData['is_subscribed'])->save();
        }

        if($this->isPermChanged)
        {
            $this->SubscribeToQueue();
            return;
        }




    }

    public function SubscribeToQueue()
    {



        $customerId = $this->subscriptionInfoFetcher->getCustomerId();
        $smsPerm = $this->paramData['is_sms_confirmed']?1:2;
        $callPerm = $this->paramData['is_call_confirmed']?1:2;

        $email = $this->subscriptionInfoFetcher->getEmailAddress();

        $emailPerm = $this->paramData['is_subscribed']?1:2;

        $nameComposition=$this->subscriptionInfoFetcher->getName();
        $nameArray = explode(" ",$nameComposition);
        $firstName = $nameArray[0];
        $lastName = $nameArray[1];

        $source = 1;
        $date = $this->dateTime->gmtDate();
        $individual=1;
        $corporate=0;
        $note="dummy note";
        $phoneNumber="5415053382";



        $recordMobilDev = new RecordMobilDev($customerId,$phoneNumber,$smsPerm,$emailPerm,$callPerm,$email,$firstName,$lastName,$source,$date,$individual,$corporate,$note);

        $this->iysSubscriptionManager->subscribe($recordMobilDev);


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



    public function isSmsPermChanged()
    {
        try{
            $subscriberCollection = $this->subscriberCollectionFactory->create();

        }catch(\Exception $e)
        {
            var_dump($e);
            exit(1);
        }
        $smsRecord = $subscriberCollection->addFieldToFilter('subscriber_id',$this->subscriberId)->addFieldToFilter('perm_type_id',StatusCheck::$permTypes['is_sms_confirmed'])->load();
        $smsRecordData = $smsRecord->getData();
        if($smsRecordData[0]['status'] !== $this->paramData['is_sms_confirmed'])
        {
            $this->isPermChanged=true;
            return $smsRecord;
        }

        return null;
    }

    public function isCallPermChanged()
    {
        try{
            $subscriberCollection = $this->subscriberCollectionFactory->create();
        }catch(\Exception $e)
        {
            var_dump($e);
            exit(1);
        }
        $callRecord = $subscriberCollection->addFieldToFilter('subscriber_id',$this->subscriberId)->addFieldToFilter('perm_type_id',StatusCheck::$permTypes['is_call_confirmed'])->load();
        $callRecordData = $callRecord->getData();

        if($callRecordData[0]['status'] !== $this->paramData['is_call_confirmed'])
        {
            $this->isPermChanged=true;
            return $callRecord;
        }

        return null;
    }

    public function isMailPermChanged()
    {
        try{
            $subscriberCollection = $this->subscriberCollectionFactory->create();
        }catch(\Exception $e)
        {
            var_dump($e);
            exit(1);
        }
        $mailRecord = $subscriberCollection->addFieldToFilter('subscriber_id',$this->subscriberId)->addFieldToFilter('perm_type_id',StatusCheck::$permTypes['is_subscribed'])->load();
        $mailRecordData = $mailRecord->getData();
        if($mailRecordData[0]['status'] !== $this->paramData['is_subscribed'])
        {
            $this->isPermChanged=true;
            return $mailRecord;
        }

        return null;
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
