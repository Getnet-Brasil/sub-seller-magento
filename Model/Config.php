<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

/**
 * Configuration paths storage.
 */

namespace Getnet\SubSellerMagento\Model;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\Store;

//phpcs:disable Generic.Files.LineLength

/**
 * Class to set flags for sub seller display setting.
 *
 * @SuppressWarnings(PHPCS.Generic.Files.LineLength)
 */
class Config
{
    /**
     * Uri Production.
     */
    public const URI_PRODUCTION = 'https://api.getnet.com.br/';

    /**
     * Uri Homolog.
     */
    public const URI_HOMOLOG = 'https://api-homologacao.getnet.com.br/';

    /**
     * Uri Sandbox.
     */
    public const URI_SANDBOX = 'https://api-sandbox.getnet.com.br/';

    /**
     * Environment.
     */
    public const XML_PATH_GETNET_ENVIRONMENT = 'getnet_sub_seller/general/credentials/environment';

    /**
     * Merchant Id Production.
     */
    public const XML_PATH_GETNET_MERCHANT_ID_PRODUCTION = 'getnet_sub_seller/general/credentials/merchant_id_production';

    /**
     * Merchant Id Homolog.
     */
    public const XML_PATH_GETNET_MERCHANT_ID_HOMOLOG = 'getnet_sub_seller/general/credentials/merchant_id_homolog';

    /**
     * Merchant Id Sandbox.
     */
    public const XML_PATH_GETNET_MERCHANT_ID_SANDBOX = 'getnet_sub_seller/general/credentials/merchant_id_sandbox';

    /**
     * Client Id Production.
     */
    public const XML_PATH_GETNET_CLIENT_ID_PRODUCTION = 'getnet_sub_seller/general/credentials/client_id_production';

    /**
     * Client Id Homolog.
     */
    public const XML_PATH_GETNET_CLIENT_ID_HOMOLOG = 'getnet_sub_seller/general/credentials/client_id_homolog';

    /**
     * Client Id Sandbox.
     */
    public const XML_PATH_GETNET_CLIENT_ID_SANDBOX = 'getnet_sub_seller/general/credentials/client_id_sandbox';

    /**
     * Client Secret Production.
     */
    public const XML_PATH_GETNET_CLIENT_SECRET_PRODUCTION = 'getnet_sub_seller/general/credentials/client_secret_production';

    /**
     * Client Secret Homolog.
     */
    public const XML_PATH_GETNET_CLIENT_SECRET_HOMOLOG = 'getnet_sub_seller/general/credentials/client_secret_homolog';

    /**
     * Client Secret Sandbox.
     */
    public const XML_PATH_GETNET_CLIENT_SECRET_SANDBOX = 'getnet_sub_seller/general/credentials/client_secret_sandbox';

    /**
     * Auth Id Production.
     */
    public const XML_PATH_GETNET_AUTH_PRODUCTION = 'getnet_sub_seller/general/credentials/access_token_production';

    /**
     * Auth Id Homolog.
     */
    public const XML_PATH_GETNET_AUTH_HOMOLOG = 'getnet_sub_seller/general/credentials/access_token_homolog';

    /**
     * Auth Id Sandbox.
     */
    public const XML_PATH_GETNET_AUTH_SANDBOX = 'getnet_sub_seller/general/credentials/access_token_sandbox';

    /**
     * List Commissions.
     */
    public const XML_PATH_GETNET_LIST_COMMISSIONS = 'getnet_sub_seller/general/list_commisions/commisions';

    /**
     * Core store config.
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Core Json.
     *
     * @var Json
     */
    protected $json;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Json                                               $json
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Json $json
    ) {
        $this->json = $json;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Get Environment.
     *
     * @param null|string|bool|int|Store $store
     *
     * @return string
     */
    public function getEnvironment($store = null)
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_GETNET_ENVIRONMENT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Get Uri.
     *
     * @param null|string|bool|int|Store $store
     *
     * @return string
     */
    public function getUri($store = null)
    {
        if ($this->getEnvironment($store) === 'sandbox') {
            return self::URI_SANDBOX;
        }

        if ($this->getEnvironment($store) === 'homolog') {
            return self::URI_HOMOLOG;
        }

        return self::URI_PRODUCTION;
    }

    /**
     * Get Merchant Gateway Client Id.
     *
     * @param null|string|bool|int|Store $store
     *
     * @return string
     */
    public function getMerchantGatewayClientId($store = null)
    {
        if ($this->getEnvironment($store) === 'sandbox') {
            return $this->_scopeConfig->getValue(
                self::XML_PATH_GETNET_CLIENT_ID_SANDBOX,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store
            );
        }

        if ($this->getEnvironment($store) === 'homolog') {
            return $this->_scopeConfig->getValue(
                self::XML_PATH_GETNET_CLIENT_ID_HOMOLOG,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store
            );
        }

        return $this->_scopeConfig->getValue(
            self::XML_PATH_GETNET_CLIENT_ID_PRODUCTION,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Get Merchant Gateway Client Secret.
     *
     * @param null|string|bool|int|Store $store
     *
     * @return string
     */
    public function getMerchantGatewayClientSecret($store = null)
    {
        if ($this->getEnvironment($store) === 'sandbox') {
            return $this->_scopeConfig->getValue(
                self::XML_PATH_GETNET_CLIENT_SECRET_SANDBOX,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store
            );
        }

        if ($this->getEnvironment($store) === 'homolog') {
            return $this->_scopeConfig->getValue(
                self::XML_PATH_GETNET_CLIENT_SECRET_HOMOLOG,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store
            );
        }

        return $this->_scopeConfig->getValue(
            self::XML_PATH_GETNET_CLIENT_SECRET_PRODUCTION,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Get Uri.
     *
     * @param null|string|bool|int|Store $store
     *
     * @return string
     */
    public function getAuth($store = null)
    {
        if ($this->getEnvironment($store) === 'homolog') {
            return $this->_scopeConfig->getValue(
                self::XML_PATH_GETNET_AUTH_HOMOLOG,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store
            );
        }

        if ($this->getEnvironment($store) === 'sandbox') {
            return $this->_scopeConfig->getValue(
                self::XML_PATH_GETNET_AUTH_SANDBOX,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store
            );
        }

        return $this->_scopeConfig->getValue(
            self::XML_PATH_GETNET_AUTH_PRODUCTION,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Get Merchant Id.
     *
     * @param null|string|bool|int|Store $store
     *
     * @return string
     */
    public function getMerchantId($store = null)
    {
        if ($this->getEnvironment($store) === 'sandbox') {
            return $this->_scopeConfig->getValue(
                self::XML_PATH_GETNET_MERCHANT_ID_SANDBOX,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store
            );
        }

        if ($this->getEnvironment($store) === 'homolog') {
            return $this->_scopeConfig->getValue(
                self::XML_PATH_GETNET_MERCHANT_ID_HOMOLOG,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store
            );
        }

        return $this->_scopeConfig->getValue(
            self::XML_PATH_GETNET_MERCHANT_ID_PRODUCTION,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Get List Commissions.
     *
     * @param null|string|bool|int|Store $store
     *
     * @return array|string
     */
    public function getListCommissions($store = null)
    {
        $listCommissions = $this->_scopeConfig->getValue(
            self::XML_PATH_GETNET_LIST_COMMISSIONS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );

        if (is_array($listCommissions)) {
            return $listCommissions;
        }

        return $this->json->unserialize($listCommissions);
    }
}
