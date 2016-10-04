<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Coupon Import for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CouponImport\Controller\Adminhtml\Import;

/**
 * Class ImportAbstract
 * @package PluginCompany\CouponImport\Controller\Adminhtml\Import
 */
abstract class ImportAbstract extends \Magento\Framework\App\Action\Action
{
    /**
     * @var array
     */
    protected $coupons;
    /**
     * @var \PluginCompany\CouponImport\Model\Import
     */
    protected $couponImporter;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $loggerInterface;

    /**
     * ImportAbstract constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \PluginCompany\CouponImport\Model\Import $couponImporter
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \PluginCompany\CouponImport\Model\Import $couponImporter,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Psr\Log\LoggerInterface $loggerInterface
    ) {
        $this->couponImporter = $couponImporter;
        $this->resultPageFactory = $resultPageFactory;
        $this->messageManager = $context->getMessageManager();
        $this->jsonHelper = $jsonHelper;
        $this->loggerInterface = $loggerInterface;
        parent::__construct($context);
    }

    /**
     * Import coupons based on textarea value
     */
    public function execute()
    {
        try {
            $this->couponImporter
                ->importCoupons($this->getCouponArray(), $this->getRuleId());

            $this->generateResultMessages();
            $this->sendResponse();
        } catch (\Exception $e) {
            $this->generateErrorMessage();
            $this->loggerInterface->critical($e);
            $this->sendResponse();
        }
    }

    /**
     * Get array of coupons from user submitted data
     *
     * @return array
     */
    abstract protected function getCouponArray();

    /**
     * Get current rule ID from request parameters
     *
     * @return int
     */
    protected function getRuleId()
    {
        return $this->getRequest()->getParam('id');
    }

    /**
     * Generate success / notification messages
     *
     * @return $this
     */
    private function generateResultMessages()
    {
        if ($this->couponImporter->hasDuplicateCoupons()) {
            $this->addDuplicatesNoticeMessage();
        }
        if ($this->couponImporter->hasAlreadyExistingCoupons()) {
            $this->addAlreadyExistsNoticeMessage();
        }
        if ($this->couponImporter->hasSuccessfullyImportedCoupons()) {
            $this->addImportSuccessCountMessage();
        }
        return $this;
    }

    /**
     * Add duplicate coupon message if duplicate coupons are submitted
     *
     * @return $this
     */
    private function addDuplicatesNoticeMessage()
    {
        $this->messageManager
            ->addNoticeMessage(__('%1 duplicate codes not added', 
                $this->_couponImporter->getDuplicateCouponCount()));
        return $this;
    }

    /**
     * Add notice if coupons already exist in system
     *
     * @return $this
     */
    private function addAlreadyExistsNoticeMessage()
    {
        $this->messageManager
            ->addNoticeMessage(__('%1 already existing codes not added', 
                $this->couponImporter->getAlreadyExistingCouponCount()));
        return $this;
    }

    /**
     * Add success message for sucessfully imported coupons
     *
     * @return $this
     */
    private function addImportSuccessCountMessage()
    {
        $this->messageManager
            ->addSuccessMessage(__('%1 coupon codes successfully imported',
                $this->couponImporter->getSuccessfullyImportedCouponCount()));
        return $this;
    }

    /**
     * Send JSON response messageblock
     */
    protected function sendResponse()
    {
        $this->getResponse()->representJson(
            $this->getMessageBlockJson()
        );
    }

    /**
     * Converts message block HTML to JSON
     *
     * @return string
     */
    protected function getMessageBlockJson()
    {
        $result = [];
        $result['messages'] = $this->getMessageBlockHtml();
        return $this->jsonHelper->jsonEncode($result);
    }

    /**
     * @return string
     */
    protected function getMessageBlockHtml()
    {
        return $this->_view->getLayout()->getMessagesBlock()->getGroupedHtml();
    }

    /**
     * Error message when exception is thrown
     *
     * @return $this
     */
    protected function generateErrorMessage()
    {
        $this->messageManager
            ->addErrorMessage(__('Something went wrong during import. Please review error logs for details.'));
        return $this;
    }
    
    protected function getMessageManager()
    {
        return $this->messageManager;
    }
}
