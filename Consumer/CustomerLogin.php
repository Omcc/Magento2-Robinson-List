<?php


namespace Mnm\Iys\Consumer;
use Mnm\Iys\Model\Ajax\IysAjax;
use Magento\Framework\Json\Helper\Data;
use Mnm\Iys\Model\StatusCheck;
class CustomerLogin
{
    protected $ajax;
    protected $jsonHelper;
    protected $statusCheck;

    public function __construct(IysAjax $ajax,Data $jsonHelper,StatusCheck  $statusCheck)
    {
        $this->ajax=$ajax;
        $this->jsonHelper=$jsonHelper;
        $this->statusCheck=$statusCheck;
    }

    public function processLogin(string $message)
    {


        $messageObj=$this->jsonHelper->jsonDecode($message);

        $subscriberId=$messageObj['subscriberId'];
        $email = $messageObj['email'];



        if($subscriberId=='')
        {
            return $this->statusCheck->recordToDefaultTable();
        }

        $data = [
            "subscriberId"=>$subscriberId,
            "email"=>$email
        ];


        $this->ajax->readCustomer($data);





    }

}
