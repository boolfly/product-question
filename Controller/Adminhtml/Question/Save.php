<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Controller\Adminhtml\Question;

use Boolfly\ProductQuestion\Controller\Adminhtml\AbstractQuestion;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends AbstractQuestion
{
    /**
     * @return ResultInterface|ResponseInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->questionFactory->create();
            if (!empty($data['question_id'])) {
                $model->load($data['question_id']);
                if ($data['question_id'] != $model->getId()) {
                    throw new LocalizedException(__('Wrong question ID: %1.', $data['question_id']));
                }
            }
            $model->addData($data);
            $model->prepareRepliesData($data);
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the question.'));
                return  $this->processReturn($model, $data, $resultRedirect);
            } catch (\Exception $exception) {
                $this->messageManager->addExceptionMessage($exception, __('Something went wrong while saving the question.'));
            }
            return $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Boolfly_ProductQuestion::question_save');
    }

    /**
     * @param $model
     * @param $data
     * @param $resultRedirect
     * @return mixed
     */
    public function processReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';
        if ($redirect ==='continue') {
            $resultRedirect->setPath('*/*/edit', ['question_id' => $model->getQuestionId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect;
    }
}
