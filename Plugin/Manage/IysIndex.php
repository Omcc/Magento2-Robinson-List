<?php


namespace Mnm\Iys\Plugin\Manage;
use Mnm\Iys\Model\Ajax\IysAjax;

class IysIndex
{
    protected $ajax;
    public function __construct(IysAjax $ajax)
    {
        $this->ajax=$ajax;

    }

    public function beforeExecute()
    {


        $this->ajax->readCustomer();

    }

}
