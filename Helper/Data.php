<?php

namespace Wise\BuyExample\Helper;


class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const CONFIG_IS_MODULE_ENABLE_PATH = 'wise_buy_example/general/enable';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param string $config_path
     * @return mixed
     */
    protected function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return (bool)$this->getConfig(self::CONFIG_IS_MODULE_ENABLE_PATH);
    }
}