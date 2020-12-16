<?php
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

}
