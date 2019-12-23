<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class QuestionActions extends Column
{
    const EDIT_URL = 'bf_question/question/edit';
    const DELETE_URL = 'bf_question/question/delete';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * QuestionActions constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['question_id'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::EDIT_URL,
                                [
                                    'question_id' => $item['question_id']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::DELETE_URL,
                                [
                                    'question_id' => $item['question_id']
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete ${ $.$data.title }'),
                                'message' => __('Are you sure you wan\'t to delete a ${ $.$data.title } record?')
                            ]
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }
}
