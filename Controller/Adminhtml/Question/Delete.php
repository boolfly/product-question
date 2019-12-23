<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Controller\Adminhtml\Question;

use Boolfly\ProductQuestion\Controller\Adminhtml\AbstractQuestion;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class Delete extends AbstractQuestion
{
    /**
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('question_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $model = $this->questionFactory->create();
            try {
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The question has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while deleted the question.'));
                $this->logger->critical($e->getMessage());
            }
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Boolfly_ProductQuestion::question_delete');
    }
}
