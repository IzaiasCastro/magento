<?php

namespace Vendor\Unienvios\Setup;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'delivery_date',
            [
                'type' => 'datetime',
                'nullable' => false,
                'comment' => 'Delivery Date',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'delivery_date',
            [
                'type' => 'datetime',
                'nullable' => false,
                'comment' => 'Delivery Date',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'delivery_date',
            [
                'type' => 'datetime',
                'nullable' => false,
                'comment' => 'Delivery Date',
            ]
        );

 $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'shipping_token_quotation_unienvios',
            [
                'type' => 'text',
                'nullable' => false,
                'comment' => 'shipping_token_quotation_unienvios',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'shipping_token_quotation_unienvios',
            [
                'type' => 'text',
                'nullable' => false,
                'comment' => 'shipping_token_quotation_unienvios',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'shipping_token_quotation_unienvios',
            [
                'type' => 'text',
                'nullable' => false,
                'comment' => 'shipping_token_quotation_unienvios',
            ]
        );

        $setup->endSetup();
    }
}
