<?php


namespace Mnm\Iys\Model;


use Mnm\Iys\Publisher\IysRegister;


class IysSubscriptionManager implements IysSubscriptionManagerInterface
{
    protected $_iysRegister;

    public function __construct(IysRegister $iysRegister)
    {
        $this->_iysRegister = $iysRegister;
    }


    public function subscribe(IysRecordInterface $iysRecord)
    {



        $this->_iysRegister->publish($iysRecord->convertToArray());


    }
}
