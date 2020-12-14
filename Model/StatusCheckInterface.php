<?php


namespace Mnm\Iys\Model;


interface StatusCheckInterface
{
    public function isSubscriberRecorded();

    public function isSMSPermChanged();
    public function isCallPermChanged();
    public function isPhonePermChanged();
    public function isRecordUpdated();

    public function setPhonePermFlag();
    public function setCallPermFlag();
    public function setSmsPermFlag();


}
