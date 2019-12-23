<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Block\Product\View;

use Boolfly\ProductQuestion\Model\Question;
use Boolfly\ProductQuestion\Model\ResourceModel\Question\Collection;
use Boolfly\ProductQuestion\Model\ResourceModel\Question\CollectionFactory;
use Exception;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Context;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class QuestionList extends Template
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_PAGE_SIZE = 10;

    /**
     * Review collection
     *
     * @var Collection
     */
    protected $questionCollection;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var HttpContext
     */
    protected $httpContext;

    /**
     * Question constructor.
     * @param Template\Context $context
     * @param Registry $registry
     * @param CollectionFactory $collectionFactory
     * @param HttpContext $httpContext
     * @param ResourceConnection $resource
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        CollectionFactory $collectionFactory,
        HttpContext $httpContext,
        ResourceConnection $resource,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->collectionFactory = $collectionFactory;
        $this->httpContext = $httpContext;
        $this->resource = $resource;
    }

    /**
     * @return Product
     */
    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * @return Collection
     */
    public function getCollection()
    {
        if (null === $this->questionCollection) {
            $page = $this->getRequest()->getParam('p') ?: self::DEFAULT_PAGE;
            $pageSize = $this->getRequest()->getParam('limit') ?: self::DEFAULT_PAGE_SIZE;
            $questionCollection = $this->collectionFactory->create();
            $questionCollection->addFieldToFilter('is_active', Question::STATUS_ENABLED)
                ->addFieldToFilter('type', Question::TYPE_QUESTION)
                ->addFieldToFilter('product_id', (int)$this->getCurrentProduct()->getId());
            $questionCollection->setOrder('question_id');
            $questionCollection->setPageSize($pageSize);
            $questionCollection->setCurPage($page);
            $this->questionCollection = $questionCollection->load();
        }

        return $this->questionCollection;
    }

    /**
     * @param $parentId
     * @return mixed
     */
    public function getAnswers($parentId)
    {
        $questionCollection = $this->collectionFactory->create();
        $questionCollection->addFieldToFilter('is_active', Question::STATUS_ENABLED)
            ->addFieldToFilter('parent_id', (int)$parentId)
            ->setOrder('question_id', Collection::SORT_ORDER_ASC);
        return $questionCollection;
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Product questions'));

        if ($this->getCollection()) {
            $toolbar = $this->getLayout()->getBlock('product_question_list.toolbar');
            if ($toolbar) {
                $toolbar->setCollection($this->getCollection());
                $this->setChild('toolbar', $toolbar);
                $this->getCollection()->load();
            }
        }
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function isLoggedIn()
    {
        return $this->httpContext->getValue(Context::CONTEXT_AUTH);
    }

    /**
     * Submit url
     * @return string
     */
    public function getSubmitUrl()
    {
        return $this->getUrl('bf_question/question/submit');
    }

    /**
     * @param $date
     * @return string
     */
    public function getDateFormat($date)
    {
        return $this->formatDate($date, \IntlDateFormatter::MEDIUM, true);
    }
}
