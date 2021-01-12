<?php
namespace Mnm\Iys\Helper;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;


class Data extends AbstractHelper
{
    protected $customerSession;
    protected $customerRepositoryInterface;
    public function __construct(Context $context,Session $customerSession,CustomerRepositoryInterface  $customerRepositoryInterface)
    {
        $this->customerSession = $customerSession;
        $this->customerRepositoryInterface=$customerRepositoryInterface;
        parent::__construct($context);
    }

    public function getConfig($configPath)
    {
        return $this->scopeConfig->getValue(
            $configPath,\Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function isIysEmailEnabled()
    {
        return $this->getConfig('iys/general/email_enabled');
    }

    public function isIysSmsEnabled()
    {
        return $this->getConfig('iys/general/sms_enabled');

    }

    public function isIysCallEnabled()
    {
        return $this->getConfig('iys/general/call_enabled');
    }

    public function getApiBaseUrl()
    {
        return $this->getConfig('iys/api_conf/base_url');
    }
    public function getTelephoneNumber()
    {
        $customerId = $this->customerSession->getCustomer()->getId();
        $customer =$this->customerRepositoryInterface->getById($customerId);


        $telephoneAttr = $customer->getCustomAttribute('telephone');
        if(!$telephoneAttr)
        {
            return "";
        }
        $telephone = $telephoneAttr->getValue();
        return $telephone;
    }

    public function getUserIpAddress()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

        }else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function getIysAjaxClass()
    {
        return $this->getConfig('iys/api_conf/iys_ajax_class');
    }

    public function getApiKey()
    {
        return $this->getConfig('iys/api_conf/api_key');
    }
    public function getApiSecret()
    {
        return $this->getConfig('iys/api_conf/api_secret');
    }

    public function getEmailFieldLabel()
    {
        return $this->getConfig('iys/labels/email');
    }
    public function getSmsFieldLabel()
    {
        return $this->getConfig('iys/labels/sms');
    }
    public function getCallFieldLabel()
    {
        return $this->getConfig('iys/labels/call');
    }




}
