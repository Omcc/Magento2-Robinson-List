<?php

namespace Mnm\Iys\Publisher;

use Magento\Framework\MessageQueue\PublisherInterface;

class IysRegister
{

    const TOPIC_NAME = "iysRegister.topic";

    private $publisher;

    public function __construct(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    public function publish(array $data)
    {
        return $this->publisher->publish(IysRegister::TOPIC_NAME,\GuzzleHttp\json_encode($data));
    }
}
