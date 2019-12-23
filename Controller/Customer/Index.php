<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Controller\Customer;

use Boolfly\ProductQuestion\Controller\AbstractCustomer;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\Page;

class Index extends AbstractCustomer
{
    /**
     * Render my product reviews
     *
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        if ($navigationBlock = $resultPage->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('bf_question/customer');
        }
        if ($block = $resultPage->getLayout()->getBlock('question_customer_list')) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());
        }
        $resultPage->getConfig()->getTitle()->set(__('My Questions'));
        return $resultPage;
    }
}
