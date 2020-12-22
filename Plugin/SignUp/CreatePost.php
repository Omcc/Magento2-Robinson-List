<?php

namespace Mnm\Iys\Plugin\SignUp;


use Mnm\Iys\Model\StatusCheck;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

class CreatePost
{
    private $statusCheck;
    private $remoteAddress;

    public function __construct(StatusCheck $statusCheck,RemoteAddress $remoteAddress)
    {

        $this->statusCheck = $statusCheck;
        $this->remoteAddress = $remoteAddress;

    }

    public function aroundExecute(\Magento\Customer\Controller\Account\CreatePost $subject,\Closure $proceed)
    {
        $originalResult = $proceed();

        $postedData = $subject -> getRequest()->getParams();
        $email = $postedData['email'];
        $ip_address = $this->remoteAddress->getRemoteAddress();
        $this->statusCheck->setEmailAddress($email);
        $this->statusCheck->setIpAddress($ip_address);
        $this->statusCheck->setParamData($postedData);
        $this->statusCheck->startCheck();

        return $originalResult;


    }

}
