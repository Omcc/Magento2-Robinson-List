<?php


namespace Mnm\Iys\Observer\Register;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mnm\Iys\Model\StatusCheck;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Mnm\Iys\Helper\Data;
class CustomerRegister implements ObserverInterface
{
    protected $statusCheck;
    protected $remoteAddress;
    protected $iysHelper;

    public function __construct(StatusCheck $statusCheck,RemoteAddress $remoteAddress,Data $iysHelper)
    {

        $this->statusCheck = $statusCheck;
        $this->remoteAddress = $remoteAddress;
        $this->iysHelper = $iysHelper;

    }

    public function execute(Observer $observer)
    {

        $postedData = $observer->getEvent()->getAccount_controller()->getRequest()->getParams();

        $customer=$observer->getEvent()->getCustomer();
        $email = $customer->getEmail();
        $customerId = $customer->getId();
        $firstName= $customer->getFirstname();
        $lastName=$customer->getLastName();
        if($this->iysHelper->isIysCallEnabled() || $this->iysHelper->isIysSmsEnabled())
        {
            $phoneNumberAttribute = $customer->getCustomAttributes()['telephone'];
            if($phoneNumberAttribute)
            {
                $phoneNumber = $phoneNumberAttribute->getValue();
            }
            else{
                $phoneNumber="";
            }

            $this->statusCheck->setPhoneNumber($phoneNumber);
        }


        $ip_address = $this->remoteAddress->getRemoteAddress();
        $this->statusCheck->setEmailAddress($email);
        $this->statusCheck->setIpAddress($ip_address);
        $this->statusCheck->setParamData($postedData);
        $this->statusCheck->setCustomerId($customerId);
        $this->statusCheck->setFirstname($firstName);
        $this->statusCheck->setLastname($lastName);
        $this->statusCheck->startCheck();
    }
}
