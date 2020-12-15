<?php


namespace Mnm\Iys\Model;


use Mnm\Iys\Model\IysRecordInterface;

class RecordMobilDev implements IysRecordInterface
{
    private $customerId;
    private $phoneNumber;
    private $smsPermission;
    private $emailPermission;
    private $callPermission;
    private $emailAddress;
    private $firstName;
    private $lastName;
    private $sourceId;
    private $date;
    private $individual;
    private $corporate;
    private $note;




    public function __construct($customerId,$phoneNumber,$smsPermission,$emailPermission,$callPermission,$emailAddress,$firstName,$lastName,$sourceId,$date,$individual,$corporate,$note)

    {
        $this->customerId=$customerId;
        $this->phoneNumber=$phoneNumber;
        $this->smsPermission=$smsPermission;
        $this->emailPermission=$emailPermission;
        $this->callPermission=$callPermission;
        $this->emailAddress = $emailAddress;
        $this->firstName=$firstName;
        $this->lastName=$lastName;
        $this->sourceId=$sourceId;
        $this->date=$date;
        $this->individual=$individual;
        $this->corporate=$corporate;
        $this->note=$note;



    }


    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;

    }

    public function getSmsPermission()
    {
        return $this->smsPermission;
    }

    public function getEmailPermission()
    {
        return $this->emailPermission;
    }

    public function getCallPermission()
    {
       return $this->callPermission;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getSourceId()
    {
        return $this->sourceId;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getIndividual()
    {
       return $this->individual;
    }

    public function getCorporate()
    {
        return $this->corporate;
    }

    public function getNote()
    {
       return $this->note;
    }



    public function convertToArray()
    {
        return[
            "recordId"=>$this->getCustomerId(),
            "msisdn"=>$this->getPhoneNumber(),
            "sms"=>$this->getSmsPermission(),
            "call"=>$this->getCallPermission(),
            "email"=>$this->getEmailAddress(),
            "sendmail"=>$this->getEmailPermission(),
            "firstName"=>$this->getFirstName(),
            "lastName"=>$this->getLastName(),
            "source"=>$this->getSourceId(),
            "date"=>$this->getDate(),
            "individual"=>$this->getIndividual(),
            "corporate"=>$this->getCorporate(),
            "note"=>$this->getNote()
        ];
    }
}
