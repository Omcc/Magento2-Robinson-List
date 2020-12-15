<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mnm\Iys\Block;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use Magento\Newsletter\Model\SubscriberFactory;
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

    public function __construct(Context $context, Session $customerSession, SubscriberFactory $subscriberFactory, CustomerRepositoryInterface $customerRepository, AccountManagementInterface $customerAccountManagement, array $data = [],SubscriptionInformation $subscriptionInfoFetcher)
    {
        parent::__construct($context, $customerSession, $subscriberFactory, $customerRepository, $customerAccountManagement, $data);
        $this->subscriptionInfoFetcher = $subscriptionInfoFetcher;
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

    /**
     * Return the save action Url.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->getUrl('newsletter/manage/save');
    }

    public function isSmsPermConfirmed()
    {
        return $this->subscriptionInfoFetcher->getSmsRecord()->getData()[0]['status'];
    }

    public function isCallPermConfirmed()
    {
        return $this->subscriptionInfoFetcher->getCallRecord()->getData()[0]['status'];
    }

}
