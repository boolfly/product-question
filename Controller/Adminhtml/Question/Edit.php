<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Controller\Adminhtml\Question;

use Boolfly\ProductQuestion\Controller\Adminhtml\AbstractQuestion;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class Edit extends AbstractQuestion
{
    /**
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $questionModel = $this->questionFactory->create();
        $questionId = $this->getRequest()->getParam('question_id');
        if ($questionId) {
            $questionModel->load($questionId);
            if (!$questionModel->getId()) {
                $this->messageManager->addErrorMessage('This question no longer exists!');
                return $resultRedirect->setPath('*/*/');
            }
        }
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $questionModel->addData($data);
        }
        $this->coreRegistry->register('current_question', $questionModel);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Boolfly_ProductQuestion::product_question');
        $resultPage->getConfig()->getTitle()->prepend($questionId ? __('Edit Question') : __('Add Question'));
        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Boolfly_ProductQuestion::question_edit');
    }
}
