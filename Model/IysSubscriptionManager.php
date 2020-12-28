<?php


namespace Mnm\Iys\Model;


use Mnm\Iys\Publisher\IysRegister;
use Mnm\Iys\Publisher\IysRead;


class IysSubscriptionManager implements IysSubscriptionManagerInterface
{
    protected $_iysRegister;
    protected $_iysRead;

    public function __construct(IysRegister $iysRegister,IysRead $iysRead)
    {
        $this->_iysRegister = $iysRegister;
        $this->_iysRead=$iysRead;
    }


    public function subscribe(IysRecordInterface $iysRecord)
    {

        $this->_iysRegister->publish($iysRecord->convertToArray());


    }

    public function readSubscription($body)
    {
        $this->_iysRead->publish($body);
    }

}
