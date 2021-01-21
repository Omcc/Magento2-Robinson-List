<?php
namespace Mnm\Iys\Plugin\Footer;
use Magento\Framework\Message\Manager;
use Magento\Framework\Event\ManagerInterface as EventManager;


class IysNew
{

    protected $messageManager;
    protected $_eventManager;
    public function __construct(Manager $messageManager,EventManager $_eventManager)
    {
        $this->messageManager = $messageManager;
        $this->_eventManager=$_eventManager;
    }
    public function afterExecute(\Magento\Newsletter\Controller\Subscriber\NewAction $subject,$result)
    {




        if(!$this->messageManager->getMessages()->getErrors())
        {
            $params = $subject->getRequest()->getParams();

            $subscriber = $params["email"];

            $this->_eventManager->dispatch("footer_subscription_succeeded",["subscriber"=>$subscriber]);



        }

        return $result;






    }
}
