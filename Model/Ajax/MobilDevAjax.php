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

        echo "hi";
        $token= $this->getAuthToken("api_path");
        print_r($token);
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


        $this->writeRegistrationLog("Receiver:Mobildev"." Request:Post ". "url:" . $url.  " Client Ip:" . $ipAddress . " Body:" . $message . " Response:" .$result);




    }

    public function readCustomer($data)
    {

        $recordId=$data['subscriberId'];
        $email=$data['email'];
        $emailRecord='';
        $phoneRecord='';

        $token= $this->getAuthToken("api_path");
        $url = "api.ivtlite.testdrive.club/check/record/";
        $this->curl->addHeader("Authorization","Bearer " . $token);

        $body = '["'.$recordId.'"]';
        $this->curl->addHeader("Content-Type","application/json");
        $this->curl->post($url,$body);

        $result = $this->curl->getBody();

        $resultObj = $this->jsonHelper->jsonDecode($result);
        $recordCount=$resultObj['RecordCount'];

        if(!$recordCount)
            return;

        $recordResult = $resultObj['Result'][0];

        $phoneRecordArr= $recordResult['detail']['msisdn'];
        $emailRecordArr = $recordResult['detail']['email'];



        foreach($emailRecordArr as $key => $val)
        {
            if($key==$email)
            {
                $emailRecord=$val;
                break;
            }
        }



        $emailPerm = $emailRecord[0]['sendmail'];

        $emailPerm = $emailPerm==2?0:$emailPerm;
        $param = [
            'is_subscribed'=>$emailPerm,
            'is_sms_confirmed'=>0,
            'is_email_confirmed'=>0
        ];

        $this->statusCheck->setSubscriberId($recordId);
        $this->statusCheck->setParamData($param);
        $this->statusCheck->updateTable();

        $this->writeLoginLog($result);




    }


}
