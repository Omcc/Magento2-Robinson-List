<?php


namespace Mnm\Iys\Publisher;

use Magento\Framework\MessageQueue\PublisherInterface;
use Mnm\Iys\Helper\Data;

class IysRead
{
    const TOPIC_NAME = "iysRead.topic";

    private $publisher;
    protected $dataHelper;

    public function __construct(PublisherInterface $publisher,Data $dataHelper)
    {
        $this->publisher = $publisher;
        $this->dataHelper = $dataHelper;
    }

    public function publish(array $data)
    {

        if($this->dataHelper->isQueueEnabled())
            return $this->publisher->publish(IysRead::TOPIC_NAME,\GuzzleHttp\json_encode($data));
    }

}
