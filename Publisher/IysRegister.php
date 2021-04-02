<?php

namespace Mnm\Iys\Publisher;

use Magento\Framework\MessageQueue\PublisherInterface;
use Mnm\Iys\Logger\IysRegistration;
use Mnm\Iys\Helper\Data;

class IysRegister
{


    const TOPIC_NAME = "iysRegister.topic";

    private $publisher;
    private $iysRegistrationLogger;
    protected $dataHelper;

    public function __construct(PublisherInterface $publisher,IysRegistration $iysRegistrationLogger,Data $dataHelper)
    {
        $this->publisher = $publisher;
        $this->iysRegistrationLogger=$iysRegistrationLogger;
        $this->dataHelper = $dataHelper;

    }

    public function publish(array $data)
    {
        if($this->dataHelper->isQueueEnabled())
            return $this->publisher->publish(IysRegister::TOPIC_NAME,\GuzzleHttp\json_encode($data));

        $this->iysRegistrationLogger->info(\GuzzleHttp\json_encode($data));
    }
}
