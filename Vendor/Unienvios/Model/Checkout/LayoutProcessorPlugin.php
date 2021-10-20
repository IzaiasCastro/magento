<?php

namespace Vendor\Unienvios\Model\Checkout;

class LayoutProcessorPlugin
{

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */

    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ) {



//Get Object Manager Instance
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//Get checkout Session by Object Manager Instance
$checkoutSession = $objectManager->create('\Magento\Checkout\Model\Session');
//get shipping method
$token_quotation = $checkoutSession->getMyTokenQuotation();

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['shipping_token_quotation_unienvios'] = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/textarea',
                'options' => [],
                'id' => 'shipping_token_quotation_unienvios'
            ],
            'dataScope' => 'shippingAddress.shipping_token_quotation_unienvios',
            'label' => 'Token Unienvios',
            'provider' => 'checkoutProvider',
            'visible' => true,
	    'value'=>$token_quotation,
            'validation' => [],
            'sortOrder' => 200,
            'id' => 'shipping_token_quotation_unienvios'
        ];


        return $jsLayout;
    }
}
