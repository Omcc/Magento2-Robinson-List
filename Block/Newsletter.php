<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Customer\Block;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use Magento\Newsletter\Model\SubscriberFactory;
use Mnm\Iys\Model\StatusCheck;

use Mnm\Iys\Model\SubscriptionInformation;




/**
 * Customer front  newsletter manage block
 *
 * @api
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 * @since 100.0.2
 */
class Newsletter extends \Magento\Customer\Block\Account\Dashboard
{
    /**
     * @var string
     */

    private $subscriptionInfoFetcher;

    public function __construct(Context $context, Session $customerSession, SubscriberFactory $subscriberFactory, CustomerRepositoryInterface $customerRepository, AccountManagementInterface $customerAccountManagement, array $data = [],SubscriptionInformation $subscriptionInformation)
    {
        parent::__construct($context, $customerSession, $subscriberFactory, $customerRepository, $customerAccountManagement, $data);
        $this->subscriptionInfoFetcher=$subscriptionInformation;
    }

    protected $_template = 'Magento_Customer::form/newsletter.phtml';

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsSubscribed()
    {
        return $this->getSubscriptionObject()->isSubscribed();
    }

    public function getSmsPermConfirmed()
    {
        return $this->subscriptionInfoFetcher->getMailRecord()->getData()[0]['status'];

    }

    /**
     * Return the save action Url.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->getUrl('newsletter/manage/save');
    }
}
