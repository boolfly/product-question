<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Model\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    const QUESTION_SETTING = 'question_setting/';

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Data constructor.
     * @param Context $context
     * @param SerializerInterface $serializer
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        SerializerInterface $serializer,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->serializer = $serializer;
        $this->storeManager = $storeManager;
    }

    /**
     * Retrieve Store Config
     * @param string $field
     * @return mixed|null
     */
    public function getConfig($field = '')
    {
        if ($field) {
            $storeScope = ScopeInterface::SCOPE_STORE;
            return $this->scopeConfig->getValue(self::QUESTION_SETTING . $field, $storeScope);
        }
        return null;
    }

    /**
     * @return mixed|null
     */
    public function isEnable()
    {
        return $this->getConfig('general/enable');
    }

    /**
     * @return mixed
     */
    public function getSupportEmail()
    {
        return $this->scopeConfig->getValue('trans_email/ident_support/email', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getSupportName()
    {
        return $this->scopeConfig->getValue('trans_email/ident_support/name', ScopeInterface::SCOPE_STORE);
    }
}
