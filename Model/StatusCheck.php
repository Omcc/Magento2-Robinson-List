<?php


namespace Mnm\Iys\Model;
use Magento\Framework\App\ObjectManager;

use Mnm\Iys\Model\ResourceModel\Subscriber\CollectionFactory as SubscriberCollectionFactory;
use Mnm\Iys\Model\SubscriberFactory;
use Mnm\Iys\Model\SubscriptionInformation;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Mnm\Iys\Model\RecordMobilDev;
use Mnm\Iys\Model\IysSubscriptionManager;
use Mnm\Iys\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Newsletter\Model\SubscriberFactory as NewsletterSubscriberFactory;


class StatusCheck
{
    public static $permTypes = array(
        "is_sms_confirmed"=>1,
        "is_call_confirmed"=>2,
        'is_subscribed'=>3,
        'status_not_known'=>-1
    );



    private $subscriberId;
    private $subscriberCollectionFactory;
    private $paramData;
    private $subscriberFactory;
    private $dateTime;
    private $subscriptionInfoFetcher;
    private $iysSubscriptionManager;
    private $isPermChanged;
    private $iysDataHelper;
    private $ipAddress;
    private $emailAddress;
    private $_storeManager;
    private $customerFactory;
    private $newsLetterSubscriberFactory;
    private $customerId;
    private $email;
    private $firstName;
    private $lastName;







    public function __construct(SubscriberCollectionFactory $subscriberCollectionFactory,SubscriberFactory $subscriberFactory,SubscriptionInformation $subscriptionInfoFetcher,IysSubscriptionManager $iysSubscriptionManager,Data $iysDataHelper,StoreManagerInterface $storeManager,CustomerFactory $customerFactory,NewsletterSubscriberFactory $newsletterSubscriberFactory)
    {

        $this->subscriptionInfoFetcher = $subscriptionInfoFetcher;
        $this->subscriberCollectionFactory=$subscriberCollectionFactory;
        $this->subscriberFactory = $subscriberFactory;
        $this->dateTime = ObjectManager::getInstance()->get(
            DateTime::class
        );
        $this->iysSubscriptionManager=$iysSubscriptionManager;
        $this->iysDataHelper=$iysDataHelper;
        $this->_storeManager=$storeManager;
        $this->customerFactory=$customerFactory;
        $this->newsLetterSubscriberFactory=$newsletterSubscriberFactory;




    }

    public function setSubscriberId($subscriberId)
    {
        $this->subscriberId=$subscriberId;
    }

    public function setParamData($paramData)
    {
        $this->paramData = $paramData;
    }

    public function setIpAddress($ipAddress)
    {
        $this->ipAddress=$ipAddress;
    }
    public function setEmailAddress($email)
    {
        $this->emailAddress=$email;
    }

    public function recordToDefaultTable()
    {

        $storeId = (int)$this->_storeManager->getStore()->getId();
        $websiteId = (int)$this->_storeManager->getStore()->getWebsiteId();



        $subscriber = $this->newsLetterSubscriberFactory->create();


        $subscriber->setSubscriberConfirmCode($subscriber->randomSequence());



        $subscriber->setSubscriberEmail($this->emailAddress);
        $subscriber->setStatus(3)
            ->setStoreId($storeId)
            ->setCustomerId((int)$this->customerId)
            ->save();


        $this->subscriberId = $this->subscriptionInfoFetcher->fetchSubscriptionId($this->customerId);
    }


    public function startCheck()
    {


        if(!$this->subscriberId)
            $this->subscriberId = $this->subscriptionInfoFetcher->fetchSubscriptionId($this->customerId);







        if(!$this->subscriberId)
        {

            $this->recordToDefaultTable();
        }





        if(!$this->isSubscriberRecorded())
        {


            $this->recordSubscriber();
            $this->SubscribeToQueue();

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


        if(!$this->customerId)
            $this->customerId = $this->subscriptionInfoFetcher->getCustomerId();


        if(isset($this->paramData['is_sms_confirmed']) && $this->iysDataHelper->isIysSmsEnabled())
            $smsPerm = $this->paramData['is_sms_confirmed'];
        else
            $smsPerm = self::$permTypes['status_not_known'];
        if(isset($this->paramData['is_call_confirmed']) && $this->iysDataHelper->isIysCallEnabled()){
            $callPerm= $this->paramData['is_call_confirmed'];
        }
        else
            $callPerm=self::$permTypes['status_not_known'];

        if(isset($this->paramData['is_subscribed'])  && $this->iysDataHelper->isIysEmailEnabled())
            $emailPerm = $this->paramData['is_subscribed'];
        else
            $emailPerm = self::$permTypes['status_not_known'];


        if(!$this->emailAddress)
             $this->emailAddress= $this->subscriptionInfoFetcher->getEmailAddress();

        if(!$this->firstName || !$this->lastName)
        {
            $nameComposition=$this->subscriptionInfoFetcher->getName();
            $nameArray = explode(" ",$nameComposition);
            $this->firstName = $nameArray[0];
            $this->lastName = $nameArray[1];
        }

        $source = 1;
        $date = $this->dateTime->gmtDate();
        $individual=1;
        $corporate=0;
        $note="dummy note";
        $phoneNumber="5415053382";

        $ipAddress = $this->ipAddress;

        $recordMobilDev = new RecordMobilDev($this->subscriberId,$phoneNumber,$smsPerm,$emailPerm,$callPerm,$this->emailAddress,$this->firstName,$this->lastName,$source,$date,$individual,$corporate,$note,$ipAddress);

        $this->iysSubscriptionManager->subscribe($recordMobilDev);

    }

    public function updateTable()
    {

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

            if(!str_starts_with($permission,"is_"))
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

        if(!$this->iysDataHelper->isIysSmsEnabled())
            return null;
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
        if(!$this->iysDataHelper->isIysCallEnabled())
            return null;
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
        if(!$this->iysDataHelper->isIysEmailEnabled())
            return null;
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

    public function setCustomerId($id)
    {
        $this->customerId=$id;
    }

    public function setEmail($email)
    {
        $this->email=$email;
    }
    public function setFirstname($firstName)
    {
        $this->firstName=$firstName;
    }
    public function setLastname($lastName)
    {
        $this->lastName=$lastName;
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
