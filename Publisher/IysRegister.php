<?php

namespace Mnm\Iys\Publisher;

use Magento\Framework\MessageQueue\PublisherInterface;
use Mnm\Iys\Logger\IysRegistration;

class IysRegister
{


    const TOPIC_NAME = "iysRegister.topic";

    private $publisher;
    private $iysRegistrationLogger;

    public function __construct(PublisherInterface $publisher,IysRegistration $iysRegistrationLogger)
    {
        $this->publisher = $publisher;
        $this->iysRegistrationLogger=$iysRegistrationLogger;
    }

    public function publish(array $data)
    {
        $this->iysRegistrationLogger->info(\GuzzleHttp\json_encode($data));
        return;

        return $this->publisher->publish(IysRegister::TOPIC_NAME,\GuzzleHttp\json_encode($data));
    }
}
