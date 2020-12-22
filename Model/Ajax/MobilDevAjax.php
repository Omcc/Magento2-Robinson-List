<?php


namespace Mnm\Iys\Model\Ajax;


class MobilDevAjax extends IysAjax
{

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

    public function getBrands($token)
    {
        $url= $this->getApiBaseUrl()."/brands";

        $this->curl->addHeader("Authorization","Bearer " . $token);
        $this->curl->get($url);
        $result = $this->curl->getBody();
        $brandArray = $this->jsonHelper->jsonDecode($result)["Result"];
        $brandArray = $this->parseBrands($brandArray);
        return $brandArray;


    }

    public function getCollecterId($token)
    {
        $url = $this->getApiBaseUrl() . "/";
        $this->curl->addHeader("Authorization","Bearer " . $token);
        $this->curl->get($url);
        $this->curl->getBody();
        $result = $this->curl->getBody();
        $collecterId = $this->jsonHelper->jsonDecode($result)["Result"][0]["collectorId"];

        return $collecterId;
    }

    public function registerCustomer($message)
    {


        $token= $this->getAuthToken("api_path");
        $brands = $this->getBrands($token);

        $messageObj = $this->jsonDecode($message);
        $messageObj['brands']=$brands;
        $ipAddress = $messageObj['ipAddress'];
        unset($messageObj['ipAddress']);
        $message = $this->jsonEncode($messageObj);


        $collecterId = $this->getCollecterId($token);

        $url = $this->getApiBaseUrl(). "/data/" . $collecterId;

        $this->curl->addHeader("Content-Type","application/json");

        $this->curl->post($url,$message);

        $result = $this->curl->getBody();


        $this->writeLog("Receiver:Mobildev"." Request:Post ". "url:" . $url.  " Client Ip:" . $ipAddress . " Body:" . $message . " Response:" .$result);




    }

    public function readCustomer()
    {


        $token= $this->getAuthToken("api_path");

        $url = "api.ivtlite.testdrive.club/portfoy/read";
        $this->curl->addHeader("Authorization","Bearer " . $token);
        $this->curl->get($url);

        $result = $this->curl->getBody();





    }


}
