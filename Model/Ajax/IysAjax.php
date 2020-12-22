<?php


namespace Mnm\Iys\Model\Ajax;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Json\Helper\Data;
use Mnm\Iys\Helper\Data as IysDataHelper;
use Mnm\Iys\Logger\IysRegistration;

abstract class IysAjax implements  IysAjaxInterface
{

    protected $curl;
    protected $jsonHelper;
    protected $iysDataHelper;
    protected $iysRegistrationLogger;
    protected $apiBaseUrl;


    public function __construct(Curl $curl,Data $jsonHelper,IysDataHelper $iysDataHelper,IysRegistration $iysRegistrationLogger)
    {
        $this->curl=$curl;
        $this->jsonHelper=$jsonHelper;
        $this->iysDataHelper = $iysDataHelper;
        $this->iysRegistrationLogger=$iysRegistrationLogger;



    }

    public function writeLog($logText)
    {
        $this->iysRegistrationLogger->info($logText);

    }

    public function getAuthToken($path)
    {
        $url = "api.ivtlite.testdrive.club/auth/auth";

        $this->curl->setCredentials("8244613191","60iknn6e9ri8mvq7snqa7369zvtvtk");

        $this->curl->get($url);

        $result = $this->curl->getBody();

        $token = $this->jsonHelper->jsonDecode($result)['access_token'];
        return $token;
    }

    public abstract function registerCustomer($message);
    public abstract function readCustomer();

    public function jsonEncode($message)
    {
        return $this->jsonHelper->jsonEncode($message);
    }

    public function jsonDecode($message)
    {
        return $this->jsonHelper->jsonDecode($message);
    }

    public function getApiBaseUrl()
    {
        if(!$this->apiBaseUrl)
            return $this->apiBaseUrl=$this->iysDataHelper->getApiBaseUrl();

        return $this->apiBaseUrl;
    }

}
