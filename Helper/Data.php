<?php
namespace Mnm\Iys\Helper;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
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



}
