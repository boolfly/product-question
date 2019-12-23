<?php

namespace Boolfly\ProductQuestion\Controller\Adminhtml;

use Boolfly\ProductQuestion\Api\Data\QuestionInterfaceFactory;
use Boolfly\ProductQuestion\Model\ResourceModel\Question\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;

abstract class AbstractQuestion extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var QuestionInterfaceFactory
     */
    protected $questionFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * Question constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param QuestionInterfaceFactory $questionFactory
     * @param LoggerInterface $logger
     * @param Registry $coreRegistry
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        QuestionInterfaceFactory $questionFactory,
        LoggerInterface $logger,
        Registry $coreRegistry,
        CollectionFactory $collectionFactory,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->questionFactory = $questionFactory;
        $this->logger = $logger;
        $this->coreRegistry = $coreRegistry;
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
    }
}
