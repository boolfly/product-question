<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Model;

use Boolfly\ProductQuestion\Api\Data\QuestionInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Question extends AbstractModel implements IdentityInterface, QuestionInterface
{
    /**#@+
     * Question's statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    const TYPE_QUESTION = 0;
    const TYPE_REPLY = 1;
    const CACHE_TAG = 'bf_question';

    protected $_cacheTag = 'bf_question';

    protected $_eventObject = 'question';

    protected $_eventPrefix = 'bf_question';

    protected function _construct()
    {
        $this->_init('Boolfly\ProductQuestion\Model\ResourceModel\Question');
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::QUESTION_ID);
    }

    /**
     * Get Title
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * Get Author Email
     *
     * @return string|null
     */
    public function getAuthorEmail()
    {
        return $this->getData(self::AUTHOR_EMAIL);
    }

    /**
     * Get Author Name
     *
     * @return string|null
     */
    public function getAuthorName()
    {
        return $this->getData(self::AUTHOR_NAME);
    }

    /**
     * Get Creation Time
     *
     * @return string|null
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Get Update Time
     *
     * @return string|null
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Check Is Active
     *
     * @return int|null
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * Set Title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritDoc
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * Set Author Email
     *
     * @param string $email
     * @return $this
     */
    public function setAuthorEmail($email)
    {
        return $this->setData(self::AUTHOR_EMAIL, $email);
    }

    /**
     * Set Author Name
     *
     * @param string $name
     * @return $this
     */
    public function setAuthorName($name)
    {
        return $this->setData(self::AUTHOR_NAME, $name);
    }

    /**
     * Set Creation Time
     *
     * @param string $creationTime
     * @return $this
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Set Update Time
     *
     * @param string $updateTime
     * @return $this
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * Set Is Active
     *
     * @param int $isActive
     * @return $this
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::QUESTION_ID, $id);
    }

    /**
     * Check Type
     *
     * @return int|null
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Check If have parent_id
     *
     * @return int|null
     */
    public function getParentId()
    {
        return $this->getData(self::PARENT_ID);
    }

    /**
     * Set Type
     *
     * @param int $type
     * @return $this
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * Set Parent ID
     *
     * @param int $parentId
     * @return $this
     */
    public function setParentId($parentId)
    {
        return $this->setData(self::PARENT_ID, $parentId);
    }

    /**
     * @param $data
     * @return $this
     */
    public function prepareRepliesData($data)
    {
        $listReplies = [];
        if (isset($data['list_replies']) && is_array($data['list_replies'])) {
            foreach ($data['list_replies'] as $value) {
                if ($value['content']) {
                    unset($value['record_id']);
                    unset($value['initialize']);
                    array_push($listReplies, $value);
                }
            }
        }
        $this->setData('replies', $listReplies);
        return $this;
    }

    /**
     * Get Product ID
     *
     * @return int|null
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * Set Product ID
     *
     * @param int $productId
     * @return $this
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Get short content
     *
     * @return string
     */
    public function getShortContent()
    {
        $content = $this->getContent();
        return (strlen($content) > 30) ? substr($content, 0, strpos($content, ' ', 30)).'...' : $content;
    }
}
