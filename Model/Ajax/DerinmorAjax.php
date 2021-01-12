<?php


namespace Mnm\Iys\Model\Ajax;


class DerinmorAjax extends IysAjax
{








    public function registerCustomer($message)
    {




        $messageObj = $this->jsonDecode($message);
        $ipAddress = $messageObj['ipAddress'];

        $message = $this->jsonEncode($messageObj);



        $this->writeRegistrationLog("Client Ip:" . $ipAddress . " Body:" . $message);




    }

    public function readCustomer($data)
    {

        return;




    }


}
