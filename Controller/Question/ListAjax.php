<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Controller\Question;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;

class ListAjax extends Action implements HttpGetActionInterface
{
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * Catalog product model
     *
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Core model store manager interface
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * ListAjax constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     * @param Registry $registry
     * @param Context $context
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager,
        Registry $registry,
        Context $context
    ) {
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
        $this->coreRegistry = $registry;
        parent::__construct($context);
    }


    public function execute()
    {
        if (!$this->initProduct()) {
            /** @var \Magento\Framework\Controller\Result\Forward $resultForward */
            $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
            return $resultForward->forward('noroute');
        }
        return $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
    }


    private function initProduct()
    {
        $productId = (int)$this->getRequest()->getParam('id');
        $product = $this->loadProduct($productId);
        if (!$product) {
            return false;
        }
        return $product;
    }

    /**
     * Load product model with data by passed id.
     * Return false if product was not loaded or has incorrect status.
     *
     * @param int $productId
     * @return bool| Product
     */
    protected function loadProduct($productId)
    {
        if (!$productId) {
            return false;
        }

        try {
            $product = $this->productRepository->getById($productId);

            if (!in_array($this->storeManager->getStore()->getWebsiteId(), $product->getWebsiteIds())) {
                throw new NoSuchEntityException();
            }

            if (!$product->isVisibleInCatalog() || !$product->isVisibleInSiteVisibility()) {
                throw new NoSuchEntityException();
            }
        } catch (NoSuchEntityException $noEntityException) {
            return false;
        }

        $this->coreRegistry->register('current_product', $product);
        $this->coreRegistry->register('product', $product);

        return $product;
    }
}
