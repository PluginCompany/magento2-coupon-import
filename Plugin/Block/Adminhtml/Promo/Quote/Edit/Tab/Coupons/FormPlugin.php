<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Coupon Import for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CouponImport\Plugin\Block\Adminhtml\Promo\Quote\Edit\Tab\Coupons;

class FormPlugin
{
    /**
     * Adds a fieldset legend to the coupon generation section
     *
     * @param $subject
     * @param $form
     */
    public function beforeSetForm($subject, $form)
    {
        $infoFieldset = $form->getElement('information_fieldset');
        $infoFieldset
            ->setLegend(__('Generate Coupon Codes'))
            ->setClass('fieldset-small')
        ;
    }
}
