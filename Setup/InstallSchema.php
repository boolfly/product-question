<?php declare(strict_types=1);

namespace Boolfly\ProductQuestion\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend_Db_Exception;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'bf_question'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('bf_question')
        )->addColumn(
            'question_id',
            Table::TYPE_INTEGER,
            11,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Question ID'
        )->addColumn(
            'title',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Question Title'
        )->addColumn(
            'content',
            Table::TYPE_TEXT,
            65536,
            ['nullable' => false],
            'Question Content'
        )->addColumn(
            'author_email',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Author Email'
        )->addColumn(
            'author_name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Author Name'
        )->addColumn(
            'customer_id',
            Table::TYPE_INTEGER,
            11,
            ['unsigned' => true, 'nullable' => true],
            'Author Name'
        )->addIndex(
            $installer->getIdxName('bf_question', ['customer_id']),
            ['customer_id']
        )->addForeignKey(
            $installer->getFkName('bf_question', 'customer_id', 'customer_entity', 'entity_id'),
            'customer_id',
            $installer->getTable('customer_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addColumn(
            'creation_time',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Question Creation Time'
        )->addColumn(
            'update_time',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            'Question Modification Time'
        )->addColumn(
            'is_active',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false],
            'Is Question Active'
        )->addColumn(
            'type',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false],
            'Type'
        )->addColumn(
            'parent_id',
            Table::TYPE_INTEGER,
            11,
            ['nullable' => true],
            'Parent ID'
        )->addColumn(
            'product_id',
            Table::TYPE_INTEGER,
            11,
            ['nullable' => false],
            'Product ID'
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable('bf_question'),
                ['title'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['title'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'Question Table'
        );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
