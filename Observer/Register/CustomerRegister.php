<?php


namespace Mnm\Iys\Observer\Register;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mnm\Iys\Model\StatusCheck;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
class CustomerRegister implements ObserverInterface
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
        $postedData = $observer->getEvent()->getAccount_controller()->getRequest()->getParams();

        $customer=$observer->getEvent()->getCustomer();
        $email = $customer->getEmail();
        $customerId = $customer->getId();
        $firstName= $customer->getFirstname();
        $lastName=$customer->getLastName();
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
