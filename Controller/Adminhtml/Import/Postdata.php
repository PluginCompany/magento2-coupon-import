<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Coupon Import for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CouponImport\Controller\Adminhtml\Import;

/**
 * Class Postdata
 * @package PluginCompany\CouponImport\Controller\Adminhtml\Import
 */
class Postdata extends ImportAbstract
{

    /**
     * Get array of coupons from user submitted data
     *
     * @return array
     */
    protected function getCouponArray()
    {
        return explode("\n", (string)$this->getCouponPostData());
    }

    /**
     * Returns submitted coupon form data
     *
     * @return array
     */
    private function getCouponPostData()
    {
        return $this->getRequest()->getParam('coupons');
    }
}
