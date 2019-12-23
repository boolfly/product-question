<?php

namespace Boolfly\ProductQuestion\Observer;

use Boolfly\ProductQuestion\Model\ResourceModel\Question;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ProcessQuestionAfterDeleteProductEvent implements ObserverInterface
{
    /**
     * @var Question
     */
    protected $resourceQuestion;

    /**
     * ProcessQuestionAfterDeleteProductEvent constructor.
     * @param Question $resourceQuestion
     */
    public function __construct(Question $resourceQuestion)
    {
        $this->resourceQuestion = $resourceQuestion;
    }

    /**
     * Cleanup product questions after product delete
     *
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $eventProduct = $observer->getEvent()->getProduct();
        if ($eventProduct && $eventProduct->getId()) {
            $this->resourceQuestion->deleteQuestionsByProductId($eventProduct->getId());
        }

        return $this;
    }
}
