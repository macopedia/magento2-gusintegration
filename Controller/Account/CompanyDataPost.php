<?php

declare(strict_types=1);

namespace Macopedia\GusIntegration\Controller\Account;

use GusApi\Exception\InvalidUserKeyException;
use GusApi\Exception\NotFoundException;
use InvalidArgumentException;
use Macopedia\GusIntegration\Model\CompanyData;
use Macopedia\GusIntegration\Model\VatIdValidator;
use Magento\Framework\Message\Warning;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class CompanyDataPost implements HttpPostActionInterface
{
    /**
     * @var string
     */
    private string $errorMessage;

    /**
     * @var Context
     */

    private Context $context;

    /**
     * @var JsonFactory
     */
    private JsonFactory $jsonPageFactory;

    /**
     * @var VatIdValidator
     */
    private VatIdValidator $vatIdValidator;

    /**
     * @var CompanyData
     */
    private CompanyData $companyData;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param Context $context
     * @param VatIdValidator $vatIdValidator
     * @param CompanyData $companyData
     * @param LoggerInterface $logger
     * @param JsonFactory $jsonPageFactory
     */
    public function __construct(
        Context $context,
        VatIdValidator $vatIdValidator,
        CompanyData $companyData,
        LoggerInterface $logger,
        JsonFactory $jsonPageFactory
    ) {
        $this->context = $context;
        $this->vatIdValidator = $vatIdValidator;
        $this->companyData = $companyData;
        $this->logger = $logger;
        $this->jsonPageFactory = $jsonPageFactory;
    }

    /**
     * {@inheritdoc}
     * @throws InvalidArgumentException
     * @throws LocalizedException
     */
    public function execute()
    {
        $resultJson = $this->jsonPageFactory->create();

        $companyData = [];

        if (!$this->validateRequest()) {
            $this->context->getMessageManager()->addErrorMessage($this->errorMessage);
            $resultJson->setHttpResponseCode(400);
            return $resultJson;
        }

        try {
            $companyData = $this->companyData->getCompanyDataByVatId(
                $this->context->getRequest()->getParam('gus_vat_id')
            );
            $this->context->getMessageManager()->addSuccessMessage(
                __('Company data loaded from GUS')
            );
        } catch (InvalidUserKeyException | NotFoundException $e) {
            $this->logger->error('Error occurred while trying to get company data: ' . $e->getMessage());
            $this->context->getMessageManager()->addWarningMessage(
                __('Error occurred while trying to get company data, please fill fields manually')
            );
        }

        $resultJson->setData([
            'company_data' => $companyData
        ]);

        return $resultJson;
    }

    /**
     * Validate request
     *
     * @return bool
     * @throws LocalizedException
     */
    private function validateRequest(): bool
    {
        if (!$this->context->getRequest()->isPost()) {
            $this->errorMessage = (string) __('An error occurred on the server. Your changes have not been saved.');

            return false;
        }

        if (!$this->vatIdValidator->isVatIdNumberValid($this->context->getRequest()->getParam('gus_vat_id'))) {
            $this->errorMessage = (string) __('Vat ID number format is invalid, please insert correct value');

            return false;
        }

        return true;
    }
}
