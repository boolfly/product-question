<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Ui\DataProvider;

use Boolfly\ProductQuestion\Model\ResourceModel\Question\Collection;
use Boolfly\ProductQuestion\Model\ResourceModel\Question\CollectionFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class QuestionDataProvider extends AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Render context
     *
     * @var ContextInterface
     */
    protected $context;

    /**
     * QuestionDataProvider constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param Registry $registry
     * @param ProductRepositoryInterface $productRepository
     * @param UrlInterface $urlBuilder
     * @param ContextInterface $context
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        Registry $registry,
        ProductRepositoryInterface $productRepository,
        UrlInterface $urlBuilder,
        ContextInterface $context,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->coreRegistry = $registry;
        $this->productRepository = $productRepository;
        $this->urlBuilder = $urlBuilder;
        $this->context = $context;
    }

    /**
     * @return array
     * @throws NoSuchEntityException
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $question = $this->coreRegistry->registry('current_question');

        if ($question->getId()) {
            $questionData = $question->getData();
            $questionId = $question->getId();
            $this->loadedData[$questionId] = $questionData;

            $product = $this->productRepository->getById((int)$question->getProductId());
            $this->loadedData[$questionId]['product_link'] = [
                'link' => $this->urlBuilder->getUrl(
                    'catalog/product/edit',
                    [
                        'id' => $product->getEntityId(),
                        'store' => $this->context->getRequestParam('store')
                    ]
                ),
                'label' => $product->getName(),
                'hidden' => false,
            ];

            /*set replies*/
            $listReplies = $question->getData('replies');
            if (!empty($listReplies)) {
                $i = 0;
                foreach ($listReplies as $item) {
                    $this->loadedData[$questionId]['list_replies'][$i] = [
                        'record_id' => $i,
                        'question_id' => $item['question_id'],
                        'title' => $item['title'],
                        'content' => $item['content'],
                        'is_active' => $item['is_active'],
                        'author_email' => $item['author_email'],
                        'author_name' => $item['author_name'],
                        'position' => $i
                    ];
                    $i++;
                }
            }
        } else {
            $this->loadedData = [];
        }

        return $this->loadedData;
    }
}
