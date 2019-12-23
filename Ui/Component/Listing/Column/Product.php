<?php

namespace Boolfly\ProductQuestion\Ui\Component\Listing\Column;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Product extends Column
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Product constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param ProductRepositoryInterface $productRepository
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        ProductRepositoryInterface $productRepository,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
        $this->productRepository = $productRepository;
    }

    /**
     * @param array $dataSource
     * @return array
     * @throws NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $name = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if (!empty($item['product_id'])) {
                    try {
                        $product = $this->productRepository->getById((int)$item['product_id']);
                        $item[$name]['product_link'] = [
                            'href' => $this->urlBuilder->getUrl(
                                'catalog/product/edit',
                                [
                                    'id' => $product->getEntityId(),
                                    'store' => $this->context->getRequestParam('store')
                                ]
                            ),
                            'label' => $product->getName(),
                            'hidden' => false,
                        ];
                    } catch (NoSuchEntityException $e) {
                        $item[$name]['product_link'] = ' ';
                    }
                }
            }
        }

        return $dataSource;
    }
}
