<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Coupon Import for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CouponImport\Block\Adminhtml\Promo\Quote\Edit\Tab\Coupons;

/**
 * Class Import
 * Import form block for coupon import
 *
 * @package PluginCompany\CouponImport\Block\Adminhtml\Promo\Quote\Edit\Tab\Coupons
 */
class Import extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory
    ) {
        parent::__construct($context, $registry, $formFactory, []);
    }

    /**
     * Generates coupon import form
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $form = $this->_formFactory->create();
        
        $form->setHtmlIdPrefix('coupons_');

        $gridBlock = $this->getLayout()->getBlock('promo_quote_edit_tab_coupons_grid');
        $gridBlockJsObject = '';
        if ($gridBlock) {
            $gridBlockJsObject = $gridBlock->getJsObjectName();
        }

        $fieldset = $form->addFieldset(
            'import_file_fieldset',
            [
                "legend" => 'Bulk Import Coupon Codes',
                "class" => 'fieldset-small'
            ]
        );
        $fieldset->addClass('ignore-validate');


        $fieldset->addField(
            'coupon_upload',
            'file',
            [
                'name' => 'coupon_upload',
                'label' => __('Import .txt File'),
                'title' => __('Import .txt File'),
                'required' => true
            ]
        );

        $idPrefix = $form->getHtmlIdPrefix();
        $fieldsetId = $fieldset->getId();
        $fileImportUrl = $this->getFileImportUrl();
        
        $fieldset->addField(
            'import_file_button',
            'note',
            [
                'text' => $this->getButtonHtml(
                    __('Import File'),
                    "importCoupons('{$idPrefix}', '{$fieldsetId}', '{$fileImportUrl}', '{$gridBlockJsObject}')",
                    'import_coupon_file'
                )
            ]
        );
        
        $fieldset = $form->addFieldset(
            'import_pasted_fieldset',
            [
                "legend" => 'Import Coupon Code List',
                "class" => 'fieldset-small'
            ]
        );
        $fieldset->addClass('ignore-validate');


        $fieldset->addField(
            'pasted_coupons',
            'textarea',
            [
                'name' => 'pasted_coupons',
                'label' => __('Import Coupons'),
                'title' => __('Import Coupons'),
                'required' => true,
                'note' => __('Enter or paste coupon code list with one coupon per line.')
            ]
        );
        
        $fieldSetId = $fieldset->getId();
        $pasteImportUrl = $this->getPasteImportUrl();
        
        $fieldset->addField(
            'import_pasted_coupons',
            'note',
            [
                'text' => $this->getButtonHtml(
                    __('Import Coupons'),
                    "importCoupons('{$idPrefix}', '{$fieldSetId}' ,'{$pasteImportUrl}', '{$gridBlockJsObject}')",
                    'import_coupon_pasted'
                )
            ]
        );

        $this->setForm($form);

        $this->_eventManager->dispatch(
            'plugincompany_couponimport_prepare_import_form_before',
            ['form' => $form]
        );

        return parent::_prepareForm();
    }

    /**
     * Retrieve URL to Import Coupon File Action
     *
     * @return string
     */
    public function getFileImportUrl()
    {
        return $this->getUrl('couponimport/import/file', ['_current' => true]);
    }
    
    /**
     * Retrieve URL to Import Pasted Coupons Action
     *
     * @return string
     */
    public function getPasteImportUrl()
    {
        return $this->getUrl('couponimport/import/postdata', ['_current' => true]);
    }
}
