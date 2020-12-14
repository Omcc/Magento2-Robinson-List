<?php


namespace Mnm\Iys\Model;


use Mnm\Iys\Model\IysRecordInterface;

Interface IysSubscriptionManagerInterface
{
    /**
     * Subscribe to newsletters by email
     *
     * @param IysRecordInterface $iysRecord
     * @return void
     */
    public function subscribe(IysRecordInterface $iysRecord);

}
