<?php

namespace Mnm\Iys\Consumer;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Json\Helper\Data;
use Mnm\Iys\Model\Ajax\Factory\IysAjaxFactory;
use Mnm\Iys\Model\Ajax\IysAjax;


class IysRegister
{


    protected $ajaxFactory;
    public function __construct(IysAjaxFactory $ajaxFactory)
    {
       $this->ajaxFactory = $ajaxFactory;


    }


    public function processRegister(string $message)
    {


        $this->ajaxFactory->create()->registerCustomer($message);

    }

}
