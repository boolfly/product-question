<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Model\Config\Source;

use Boolfly\ProductQuestion\Model\Question;
use Magento\Framework\Option\ArrayInterface;

class Options implements ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' =>  Question::STATUS_ENABLED, 'label' => __('Enabled')],
            ['value' =>  Question::STATUS_DISABLED, 'label' => __('Disabled')]
        ];
    }
}
