<?php

namespace Mnm\Iys\Consumer;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Json\Helper\Data;
use Mnm\Iys\Model\Ajax\IysAjax;

class IysRegister
{


    protected $ajax;
    public function __construct(IysAjax $ajax)
    {
       $this->ajax = $ajax;


    }


    public function processRegister(string $message)
    {

        $this->ajax->registerCustomer($message);



    }

}
