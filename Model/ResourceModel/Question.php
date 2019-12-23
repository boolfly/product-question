<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Model\ResourceModel;

use Boolfly\ProductQuestion\Model\Config\Data;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Question extends AbstractDb
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * Question constructor.
     * @param Context $context
     * @param Data $helperData
     */
    public function __construct(
        Context $context,
        Data $helperData
    ) {
        parent::__construct($context);
        $this->helperData = $helperData;
    }

    protected function _construct()
    {
        $this->_init('bf_question', 'question_id');
    }

    /**
     * @param $id
     * @return array
     */
    public function lookupReplies($id)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('bf_question')
        )->where('parent_id = ?', (int)$id);

        return $connection->fetchAll($select);
    }

    /**
     * @param $id
     * @return array
     */
    public function lookupRepliesId($id)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('bf_question'),
            'question_id'
        )->where('parent_id = ?', (int)$id);

        return $connection->fetchCol($select);
    }

    /**
     * @param $objectId
     * @param $replies
     */
    protected function updateReplies($objectId, $replies)
    {
        $oldRepliesId = [];
        $newReplies = [];
        $updateReplies = [];

        foreach ((array)$replies as $reply) {
            switch (empty($reply['is_delete'])) {
                case true:
                    if (!$reply['question_id']) {
                        array_push($newReplies, $reply);
                    } else {
                        array_push($updateReplies, $reply);
                    }
                    break;

                case false:
                    if ($reply['question_id']) {
                        array_push($oldRepliesId, $reply['question_id']);
                    }
                    break;

            }
        }

        /*prepare data for deleting*/
        $table = $this->getTable('bf_question');
        if (count($oldRepliesId)) {
            $where = ['parent_id = ?' => (int)$objectId, 'question_id IN (?)' => $oldRepliesId];
            $this->getConnection()->delete($table, $where);
        }

        /*prepare data for inserting*/
        if ($newReplies) {
            $data = [];
            foreach ($newReplies as $reply) {
                $data[] = [
                    'parent_id' => (int)$objectId,
                    'content' => $reply['content'],
                    'author_name' => $this->helperData->getSupportName(),
                    'author_email' => $this->helperData->getSupportEmail(),
                    'is_active' => $reply['is_active'],
                    'product_id' => $reply['product_id'],
                    'type' => \Boolfly\ProductQuestion\Model\Question::TYPE_REPLY
                ];
            }
            $this->getConnection()->insertMultiple($table, $data);
        }

        /*prepare data for updating*/
        if ($updateReplies) {
            foreach ($updateReplies as $reply) {
                $data = [
                    'parent_id' => (int)$objectId,
                    'content' => $reply['content'],
                    'author_name' => $reply['author_name'],
                    'author_email' => $reply['author_email'],
                    'is_active' => $reply['is_active'],
                    'type' => \Boolfly\ProductQuestion\Model\Question::TYPE_REPLY
                ];
                $this->getConnection()->update(
                    $table,
                    $data,
                    $where = ['question_id = ?' => (int)$reply['question_id']]
                );
            }
        }
    }

    /**
     * @param AbstractModel $object
     * @return AbstractDb
     */
    protected function _afterSave(AbstractModel $object)
    {
        $replies = [];
        foreach ((array)$object->getData('replies') as $reply) {
            $reply['product_id'] = $object->getData('product_id');
            $replies[] = $reply;
        }
        $this->updateReplies($object->getId(), $replies);
        return parent::_afterSave($object);
    }

    /**
     * @param AbstractModel $object
     * @return AbstractDb
     */
    protected function _afterLoad(AbstractModel $object)
    {
        if ($object->getId()) {
            $replies = $this->lookupReplies($object->getId());
            $object->setData('replies', $replies);
        }

        return parent::_afterLoad($object);
    }

    /**
     * @param AbstractModel $object
     * @return AbstractDb
     */
    protected function _afterDelete(AbstractModel $object)
    {
        $oldRepliesId = $this->lookupRepliesId($object->getId());

        /*delete old replies*/
        $table = $this->getTable('bf_question');
        if ($oldRepliesId) {
            $where = ['question_id IN (?)' => $oldRepliesId];
            $this->getConnection()->delete($table, $where);
        }
        return parent::_afterDelete($object);
    }

    /**
     * Delete questions by product id.
     *
     * @param int $productId
     * @return $this
     */
    public function deleteQuestionsByProductId($productId)
    {
        $this->getConnection()->delete(
            $this->getTable('bf_question'),
            ['product_id = ?' => $productId]
        );
        return $this;
    }
}
