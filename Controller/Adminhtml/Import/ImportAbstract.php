<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Coupon Import for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CouponImport\Controller\Adminhtml\Import;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\View\Result\PageFactory;
use Magento\SalesRule\Model\RuleRepository;
use PluginCompany\CouponImport\Model\Import;
use Psr\Log\LoggerInterface;

/**
 * Class ImportAbstract
 * @package PluginCompany\CouponImport\Controller\Adminhtml\Import
 */
abstract class ImportAbstract extends \Magento\Backend\App\Action
{
    /**
     * Cart Price Rules permission (the coupon import is part of the cart price rule edit page)
     */
    const ADMIN_RESOURCE = 'Magento_SalesRule::quote';

    /**
     * @var array
     */
    protected $coupons;
    /**
     * @var Import
     */
    protected $couponImporter;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Data
     */
    protected $jsonHelper;
    /**
     * @var LoggerInterface
     */
    protected $loggerInterface;
    /**
     * @var RuleRepository
     */
    protected $ruleRepository;

    /**
     * ImportAbstract constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Import $couponImporter
     * @param Data $jsonHelper
     * @param LoggerInterface $loggerInterface
     * @param RuleRepository $ruleRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Import $couponImporter,
        Data $jsonHelper,
        LoggerInterface $loggerInterface,
        RuleRepository $ruleRepository
    ) {
        $this->couponImporter = $couponImporter;
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->loggerInterface = $loggerInterface;
        $this->ruleRepository = $ruleRepository;
        parent::__construct($context);
    }

    /**
     * Explicit permission check, independent of how the parent class reads ADMIN_RESOURCE
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }

    /**
     * Import coupons based on textarea value
     */
    public function execute()
    {
        try {
            if(!$this->ruleAllowsAutoGeneration()){
                return $this->sendAutoGenerationError();
            }
            $this->executeImport();
        } catch (NoSuchEntityException $e) {
            $this->handleNoSuchEntityError();
        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }

    private function ruleAllowsAutoGeneration()
    {
        return $this->getRule()->getUseAutoGeneration();
    }

    private function getRule()
    {
        return $this->ruleRepository
            ->getById($this->getRuleId());
    }

    /**
     * Get current rule ID from request parameters
     *
     * @return int
     */
    protected function getRuleId()
    {
        return $this->getRequest()->getParam('id');
    }


    private function sendAutoGenerationError()
    {
        $this->generateErrorMessage("In order to import coupons, please first enable coupon auto generation and save the rule");
        $this->sendResponse();
    }

    private function executeImport()
    {
        $this->importCoupons();
        $this->generateResultMessages();
        $this->sendResponse();
        return $this;
    }

    private function importCoupons()
    {
        $this->couponImporter
            ->importCoupons($this->getCouponArray(), $this->getRuleId());
        return $this;
    }

    /**
     * Get array of coupons from user submitted data
     *
     * @return array
     */
    abstract protected function getCouponArray();

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
                $this->couponImporter->getDuplicateCouponCount()));
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

    protected function handleNoSuchEntityError()
    {
        $this->generateErrorMessage(
            "Rule not found. In order to import coupon codes, please save the rule first."
        );
        $this->sendResponse();
    }

    protected function handleError($e)
    {
        $this->generateErrorMessage();
        $this->loggerInterface->critical($e);
        $this->sendResponse();
    }

    /**
     * Error message when exception is thrown
     *
     * @param string $message
     * @return $this
     */
    protected function generateErrorMessage($message = "")
    {
        if(!$message){
            $message = 'Something went wrong during import. Please review error logs for details.';
        }
        $this->messageManager
            ->addErrorMessage(__($message));
        return $this;
    }
    
    protected function getMessageManager()
    {
        return $this->messageManager;
    }
}
