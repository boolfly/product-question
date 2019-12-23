<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Controller\Question;

use Boolfly\ProductQuestion\Api\Data\QuestionInterface;
use Boolfly\ProductQuestion\Api\Data\QuestionInterfaceFactory;
use Boolfly\ProductQuestion\Model\Question;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;

class Submit extends Action implements HttpPostActionInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var QuestionInterfaceFactory
     */
    protected $questionFactory;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * Core form key validator
     *
     * @var Validator
     */
    protected $formKeyValidator;

    /**
     * Submit constructor.
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param QuestionInterfaceFactory $questionFactory
     * @param StoreManagerInterface $storeManager
     * @param ObjectManagerInterface $objectManager
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param Escaper $escaper
     * @param Validator $formKeyValidator
     * @param Session $customerSession
     */
    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        QuestionInterfaceFactory $questionFactory,
        StoreManagerInterface $storeManager,
        ObjectManagerInterface $objectManager,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        Validator $formKeyValidator,
        Session $customerSession
    ) {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->questionFactory = $questionFactory;
        $this->storeManager = $storeManager;
        $this->objectManager = $objectManager;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
    }

    /**
     * Init a question model
     * @return QuestionInterface
     */
    protected function initQuestion()
    {
        $currentCustomer = $this->customerSession->getCustomer();
        $questionModel = $questionModel = $this->questionFactory->create();
        $data = $this->getRequest()->getPostValue();
        if (isset($data['title'])) {
            $questionModel->setTitle($data['title']);
        }
        $questionModel->setContent($data['content']);
        $questionModel->setData('product_id', $data['product_id']);
        $questionModel->setCustomerId($currentCustomer->getId());
        $questionModel->setAuthorEmail($currentCustomer->getEmail());
        $questionModel->setAuthorName($currentCustomer->getName());
        $questionModel->setIsActive(Question::STATUS_ENABLED);
        if (isset($data['parent_id'])) {
            $questionModel->setType(Question::TYPE_REPLY);
            $questionModel->setParentId($data['parent_id']);
        }
        return $questionModel;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return ResultInterface|ResponseInterface
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->getRequest()) || !$this->customerSession->isLoggedIn()) {
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }
        $model = $this->initQuestion()->save();
        $this->_eventManager->dispatch(
            'controller_after_submitting_question',
            ['question'=>$model]
        );
        //$this->_sendNotificationMail();
        $this->messageManager->addSuccessMessage(
            __('You have submitted the question.')
        );
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
