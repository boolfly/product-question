<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Model\ResourceModel\Question;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'question_id';
    protected $_eventPrefix = 'boolfly_question_collection';
    protected $_eventObject = 'question_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Boolfly\ProductQuestion\Model\Question', 'Boolfly\ProductQuestion\Model\ResourceModel\Question');
    }
}
