<?php
/**
 * @author Tom Protogerakis <tom.protogerakis@gmail.com>
 * @copyright 2022 Tom Protogerakis
 * @package Primehawk_SuppressCommunications
 */

namespace Primehawk\SuppressCommunications\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class AbstractSuppressor
{
    const CONFIG_PATH = 'suppressors';

    /** @var int */
    protected int $_scopeId;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfigInterface;

    /**
     * @param ScopeConfigInterface $scopeConfigInterface
     */
    public function __construct(ScopeConfigInterface $scopeConfigInterface)
    {
        $this->scopeConfigInterface = $scopeConfigInterface;
    }

    /**
     * @param string $path
     * @return mixed
     */
    protected function getConfigValue(string $path)
    {
        return $this->scopeConfigInterface->getValue(self::CONFIG_PATH . '/' . $path, ScopeInterface::SCOPE_WEBSITE, $this->_scopeId);
    }

    /**
     * @param string $groupValue
     * @return bool
     */
    protected function isSuppressorGroupEnabled(string $groupValue): bool
    {
        return (bool)$this->getConfigValue($groupValue . '/is_enabled');
    }

    /**
     * @param string $groupValue
     * @param string $fieldValue
     * @return bool
     */
    protected function isSuppressorEnabled(string $groupValue, string $fieldValue): bool
    {
        return (bool)$this->getConfigValue($groupValue . '/' . $fieldValue);
    }

    /**
     * @param string $suppressor
     * @param string $suppressorGroup
     * @param int $scopeId
     * @return bool
     */
    protected function mustSuppress(string $suppressor, string $suppressorGroup , int $scopeId): bool
    {
        $this->_scopeId = $scopeId;
        if (!$this->isSuppressorGroupEnabled($suppressorGroup) || !$this->isSuppressorEnabled($suppressorGroup, $suppressor)) {
            return false;
        }
        return true;
    }

}
