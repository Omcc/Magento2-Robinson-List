<?php

namespace Mnm\Iys\Consumer;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Json\Helper\Data;

class IysRegister
{
    protected $curl;
    protected $jsonHelper;

    public function __construct(Curl $curl,Data $jsonHelper)
    {
        $this->curl = $curl;
        $this->jsonHelper = $jsonHelper;


    }

    //utility function
    public function parseBrands($brandArray)
    {
        $brands = array();
        foreach($brandArray as $brand)
        {
            array_push($brands,$brand["brandId"]);
        }

        return $brands;

    }


    public function getAuthToken()
    {
        $url = "api.ivtlite.testdrive.club/auth/auth";

        $this->curl->setCredentials("8244613191","60iknn6e9ri8mvq7snqa7369zvtvtk");

        $this->curl->get($url);

        $result = $this->curl->getBody();

        $token = $this->jsonHelper->jsonDecode($result)['access_token'];
        return $token;

    }

    public function getBrands($token)
    {
        $url= "api.ivtlite.testdrive.club/brands";

        $this->curl->addHeader("Authorization","Bearer " . $token);
        $this->curl->get($url);
        $result = $this->curl->getBody();
        $brandArray = $this->jsonHelper->jsonDecode($result)["Result"];
        $brandArray = $this->parseBrands($brandArray);
        return $brandArray;


    }

    public function getCollecterId($token)
    {
        $url = "api.ivtlite.testdrive.club/";
        $this->curl->get($url);
        $this->curl->getBody();
        $result = $this->curl->getBody();
        $collecterId = $this->jsonHelper->jsonDecode($result)["Result"][0]["collectorId"];

        return $collecterId;
    }

    public function postRegister($body,$token)
    {
        $collecterId = $this->getCollecterId($token);

        $url = "api.ivtlite.testdrive.club/data/" . $collecterId;

        $this->curl->addHeader("Content-Type","application/json");

        $this->curl->post($url,$body);

        $result = $this->curl->getBody();

        return $result;




    }

    public function processRegister(string $message)
    {


        $path = "/var/www/html/var/log/iys-reg-requests.log";
        $path2 = "/var/www/html/var/log/iys-reg-responses.log";
        $token= $this->getAuthToken();
        $brands = $this->getBrands($token);






        $messageObj = $this->jsonHelper->jsonDecode($message);
        $messageObj['brands']=$brands;
        $message = $this->jsonHelper->jsonEncode($messageObj);

        $result = $this->postRegister($message,$token);




        file_put_contents($path,$message."\n",FILE_APPEND);
        file_put_contents($path2,$result ."\n",FILE_APPEND);




    }

}
