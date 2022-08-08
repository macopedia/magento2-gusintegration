<?php

declare(strict_types=1);

namespace Macopedia\GusIntegration\ViewModel;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CompanyDataViewModel implements ArgumentInterface
{
    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    public function __construct(
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    public function getCompanyDataPostActionUrl()
    {
        return $this->urlBuilder->getUrl('gusintegration/account/companyDataPost');
    }
}
