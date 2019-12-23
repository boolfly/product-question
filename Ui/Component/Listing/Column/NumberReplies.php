<?php

namespace Boolfly\ProductQuestion\Ui\Component\Listing\Column;

use Boolfly\ProductQuestion\Api\Data\QuestionInterfaceFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class NumberReplies extends Column
{
    /**
     * @var QuestionInterfaceFactory
     */
    protected $questionFactory;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        QuestionInterfaceFactory $questionFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->questionFactory = $questionFactory;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = $this->getNumberOfReplies($item['question_id']);
            }
        }

        return $dataSource;
    }

    /**
     * @param $questionId
     * @return int|void
     */
    protected function getNumberOfReplies($questionId)
    {
        $question = $this->questionFactory->create();
        $question->load($questionId);
        return count((array)$question->getData('replies'));
    }
}
