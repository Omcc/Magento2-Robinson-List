<?php


namespace Mnm\Iys\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Mnm\Iys\Model\SubscriberFactory;

class Index extends Action
{

    protected $_subscriberFactory;

    public function __construct(Context $context,SubscriberFactory $subscriberFactory)
    {
        $this->_subscriberFactory=$subscriberFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $model = $this->_subscriberFactory->create();
        $data = [
            "idx"=>1,
            "subscriber_id"=>8,
            "perm_type_id"=>2,
            "perm_source_id"=>2,
            "status"=>3,
            "value"=>'asda',
            "change_status_at"=>'2022-05-04'];

        $model->setData($data);


        $saveData = $model->save();







    }
}
