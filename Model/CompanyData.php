<?php

namespace Macopedia\GusIntegration\Model;

use GusApi\Exception\NotFoundException;
use GusApi\GusApi;
use Magento\Directory\Model\RegionFactory;

class CompanyData
{
    const COUNTRY_CODE = 'PL';

    /**
     * @var Config $config
     */
    private Config $config;
    private RegionFactory $regionFactory;

    /**
     * CompanyData constructor.
     * @param Config $config
     */
    public function __construct(
        Config $config,
        RegionFactory $regionFactory
    ) {
        $this->config = $config;
        $this->regionFactory = $regionFactory;
    }

    /**
     * @param string $vatId
     * @return array
     * @throws NotFoundException
     */
    public function getCompanyDataByVatId(string $vatId)
    {
        $gus = new GusApi($this->config->getUserId(), $this->getEnv());
        $gus->login();

        $gusReports = $gus->getByNip($vatId);
        $gusReport = reset($gusReports);

        return [
            'company' => $gusReport->getName(),
            'street_1' => $gusReport->getStreet() . ' ' . trim(
                $gusReport->getPropertyNumber() . '/' . $gusReport->getApartmentNumber(),
                "\t\n\r\0\x0B\/"
            ),
            'zip' => $gusReport->getZipCode(),
            'city' => $gusReport->getCity(),
            'country' => self::COUNTRY_CODE,
            'region_id' => $this->getRegionIdByName($gusReport->getProvince())
        ];
    }

    protected function getRegionIdByName($name)
    {
        $region = $this->regionFactory->create()->loadByName($name, self::COUNTRY_CODE);
        if ($region->getId()) {
            return $region->getId();
        }
        return null;
    }

    protected function getEnv()
    {
        return $this->config->isSandbox() ? 'dev' : 'prod';
    }
}
