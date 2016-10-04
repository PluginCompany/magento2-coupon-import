<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Coupon Import for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CouponImport\Controller\Adminhtml\Import;

/**
 * Class File
 * @package PluginCompany\CouponImport\Controller\Adminhtml\Import
 */
class File extends ImportAbstract
{
    private $csvProcessor;

    /**
     * File constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \PluginCompany\CouponImport\Model\Import $couponImporter
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \PluginCompany\CouponImport\Model\Import $couponImporter,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Psr\Log\LoggerInterface $loggerInterface,
        \Magento\Framework\File\Csv $csvProcessor
    ) {
        $this->csvProcessor = $csvProcessor;
        return parent::__construct(
            $context,
            $resultPageFactory,
            $couponImporter,
            $jsonHelper,
            $loggerInterface
        );
    }
    
    /**
     * Import coupons based on textarea value
     */
    public function execute()
    {
        try {
            $this->runExecute();
        } catch(\Exception $e) {
            $this->handleError($e);
        }
    }
    
    protected function runExecute()
    {
        if (!$this->isUploadedFileAllowed()) {
            $this->sendInvalidFileResponse();
            return;
        }
        parent::execute();
    }

    protected function handleError($e)
    {
        $this->generateErrorMessage();
        $this->loggerInterface->critical($e);
        $this->sendResponse();
    }

    /**
     * Check if uploaded file is plain text
     *
     * @return bool
     */
    private function isUploadedFileAllowed()
    {
        if ($this->getUploadType() == 'text/plain') {
            return true;
        }
        return false;
    }

    /**
     * retrieve coupon array from user submitted data
     *
     * @return array
     */
    protected function getCouponArray()
    {
        return $this->getUploadedCoupons();
    }

    /**
     * Get coupon array from uploaded file
     *
     * @return array
     */
    private function getUploadedCoupons()
    {
        return $this->getCouponArrayFromUploadedFile();
    }

    /**
     * @param $fh
     * @return array
     */
    private function getCouponArrayFromUploadedFile()
    {
        $coupons = $this->csvProcessor->getData($this->getUploadTmpName());
        return $this->unwrapCouponArray($coupons);
    }

    /**
     * @return mixed
     */
    private function getUploadTmpName()
    {
        return $this->getPostedFileInfo()['tmp_name'];
    }
    
    private function getPostedFileInfo()
    {
        return $this->getRequest()->getFiles()['coupon_upload'];
    }
    
    /**
     * @return mixed
     */
    private function getUploadType()
    {
        return $this->getPostedFileInfo()['type'];
    }
    
    private function unwrapCouponArray($array)
    {
        $func = function($value) {
            return $value[0];
        };
        return array_map($func, $array);
    }


    /**
     * Sends error message if file is invalid
     */
    private function sendInvalidFileResponse()
    {
        $this
            ->generateInvalidFileMessage()
            ->sendResponse();
    }

    private function generateInvalidFileMessage()
    {
        $this
            ->getMessageManager()
            ->addErrorMessage(__('File type not allowed, please select a plain text file.'));
        return $this;
    }
}
