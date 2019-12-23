<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Controller\Adminhtml\Question;

use Boolfly\ProductQuestion\Controller\Adminhtml\AbstractQuestion;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class Index extends AbstractQuestion
{
    /**
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Boolfly_ProductQuestion::product_question');
        $resultPage->getConfig()->getTitle()->prepend(__('Question List'));
        return $resultPage;
    }
}
