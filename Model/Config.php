<?php


namespace Macopedia\GusIntegration\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    const IS_SANDBOX_CONFIG_PATH = 'gus/general/sandbox';
    const USER_ID_CONFIG_PATH = 'gus/general/user_id';

    /** @var ScopeConfigInterface */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * Configuration constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return bool
     */
    public function isSandbox(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        ?string $scopeCode = null
    ) {
        return $this->scopeConfig->isSetFlag(self::IS_SANDBOX_CONFIG_PATH, $scopeType, $scopeCode);
    }

    /**
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return string|null
     */
    public function getUserId(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        ?string $scopeCode = null
    ) {
        return $this->scopeConfig->getValue(self::USER_ID_CONFIG_PATH, $scopeType, $scopeCode);
    }
}
