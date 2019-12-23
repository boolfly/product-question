<?php

namespace Boolfly\ProductQuestion\Api\Data;

interface QuestionInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const QUESTION_ID = 'question_id';
    const TITLE = 'title';
    const CONTENT = 'content';
    const AUTHOR_EMAIL = 'author_email';
    const AUTHOR_NAME = 'author_name';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME = 'update_time';
    const IS_ACTIVE = 'is_active';
    const TYPE = 'type';
    const PARENT_ID = 'parent_id';
    const PRODUCT_ID = 'product_id';

    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getContent();

    /**
     * Get Author Email
     *
     * @return string|null
     */
    public function getAuthorEmail();

    /**
     * Get Author Name
     *
     * @return string|null
     */
    public function getAuthorName();

    /**
     * Get Creation Time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Get Update Time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Check Is Active
     *
     * @return int|null
     */
    public function getIsActive();

    /**
     * Check Type
     *
     * @return int|null
     */
    public function getType();

    /**
     * Check If have parent_id
     *
     * @return int|null
     */
    public function getParentId();

    /**
     * Get Product ID
     *
     * @return int|null
     */
    public function getProductId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Set Title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Set content
     * @param string $content
     * @return $this
     */
    public function setContent($content);

    /**
     * Set Author Email
     *
     * @param string $email
     * @return $this
     */
    public function setAuthorEmail($email);

    /**
     * Set Author Name
     *
     * @param string $name
     * @return $this
     */
    public function setAuthorName($name);

    /**
     * Set Creation Time
     *
     * @param string $creationTime
     * @return $this
     */
    public function setCreationTime($creationTime);

    /**
     * Set Update Time
     *
     * @param string $updateTime
     * @return $this
     */
    public function setUpdateTime($updateTime);

    /**
     * Set Is Active
     *
     * @param int $isActive
     * @return $this
     */
    public function setIsActive($isActive);

    /**
     * Set Type
     *
     * @param int $type
     * @return $this
     */
    public function setType($type);

    /**
     * Set Parent ID
     *
     * @param int $parentId
     * @return $this
     */
    public function setParentId($parentId);

    /**
     * Set Product ID
     *
     * @param int $productId
     * @return $this
     */
    public function setProductId($productId);
}
