<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Block\Customer;

use Boolfly\ProductQuestion\Model\ResourceModel\Question\Collection;
use Boolfly\ProductQuestion\Model\ResourceModel\Question\CollectionFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Block\Account\Dashboard;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Customer\Model\Session;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template\Context;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Theme\Block\Html\Pager;

class QuestionList extends Dashboard
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * QuestionList constructor.
     * @param Context $context
     * @param Session $customerSession
     * @param CollectionFactory $collectionFactory
     * @param SubscriberFactory $subscriberFactory
     * @param CurrentCustomer $currentCustomer
     * @param ProductRepositoryInterface $productRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        CollectionFactory $collectionFactory,
        SubscriberFactory $subscriberFactory,
        CurrentCustomer $currentCustomer,
        ProductRepositoryInterface $productRepository,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->productRepository = $productRepository;
        parent::__construct(
            $context,
            $customerSession,
            $subscriberFactory,
            $customerRepository,
            $customerAccountManagement,
            $data
        );
        $this->currentCustomer = $currentCustomer;
    }

    /**
     * Get html code for toolbar
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    /**
     * Initializes toolbar
     *
     * @return AbstractBlock
     * @throws LocalizedException
     */
    protected function _prepareLayout()
    {
        if ($this->getQuestions()) {
            $toolbar = $this->getLayout()->createBlock(
                Pager::class,
                'customer_question_list.toolbar'
            )->setCollection(
                $this->getQuestions()
            );

            $this->setChild('toolbar', $toolbar);
        }
        return parent::_prepareLayout();
    }

    /**
     * Get question list
     *
     * @return bool|Collection
     */
    public function getQuestions()
    {
        if (!($customerId = $this->currentCustomer->getCustomerId())) {
            return false;
        }
        if (!$this->collection) {
            $this->collection = $this->collectionFactory->create();
            $this->collection
                ->addFieldToFilter('customer_id', $customerId)
                ->setOrder('question_id');
        }
        return $this->collection;
    }

    /**
     *Get product
     *
     * @param $productId
     * @return ProductInterface|null
     */
    public function getProduct($productId)
    {
        try {
            $product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $exception) {
            //Do no thing
            $product = null;
        }
        return $product;
    }

    /**
     * @param $question
     * @return DataObject|null
     */
    public function getProductInfo($question)
    {
        $id = $question->getProductId();
        $url = null;
        $product = $this->getProduct($id);
        if ($product) {
            $url = new DataObject([
                'name' => $product->getName(),
                'url' => $product->getProductUrl()
            ]);
        }
        return $url;
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
