<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Block\Product\View;

use Magento\Customer\Model\Context;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Http\Context as HttpContext;

class Question extends Template
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var HttpContext
     */
    protected $httpContext;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var EncoderInterface
     */
    protected $urlEncoder;

    /**
     * Question constructor.
     * @param Template\Context $context
     * @param Registry $registry
     * @param HttpContext $httpContext
     * @param UrlInterface $urlBuilder
     * @param EncoderInterface $urlEncoder
     * @param ResourceConnection $resource
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        HttpContext $httpContext,
        UrlInterface $urlBuilder,
        EncoderInterface $urlEncoder,
        ResourceConnection $resource,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->httpContext = $httpContext;
        $this->urlBuilder = $urlBuilder;
        $this->urlEncoder = $urlEncoder;
        $this->resource = $resource;
    }

    /**
     * @return Product
     */
    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * @return mixed|null
     */
    public function isLoggedIn()
    {
        return $this->httpContext->getValue(Context::CONTEXT_AUTH);
    }

    /**
     * Get URL for ajax call
     *
     * @return string
     */
    public function getProductQuestionUrl()
    {
        return $this->getUrl(
            'bf_question/question/listAjax',
            [
                '_secure' => $this->getRequest()->isSecure(),
                'id' => $this->getCurrentProduct()->getId(),
            ]
        );
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return string
     */
    public function getBase64CurrentUrl()
    {
        $referer = $this->urlBuilder->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
        $referer = $this->urlEncoder->encode($referer);
        return $referer;
    }
}
