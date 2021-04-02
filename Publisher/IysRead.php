<?php


namespace Mnm\Iys\Publisher;

use Magento\Framework\MessageQueue\PublisherInterface;

class IysRead
{
    const TOPIC_NAME = "iysRead.topic";

    private $publisher;

    public function __construct(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    public function publish(array $data)
    {
        return $this->publisher->publish(IysRead::TOPIC_NAME,\GuzzleHttp\json_encode($data));
    }

}
