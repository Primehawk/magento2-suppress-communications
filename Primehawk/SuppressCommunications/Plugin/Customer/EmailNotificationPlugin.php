<?php
/**
 * @author Tom Protogerakis <tom.protogerakis@gmail.com>
 * @copyright 2022 Tom Protogerakis
 * @package Primehawk_SuppressCommunications
 */

namespace Primehawk\SuppressCommunications\Plugin\Customer;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\EmailNotificationInterface;
use Primehawk\SuppressCommunications\Model\AbstractSuppressor;


class EmailNotificationPlugin extends AbstractSuppressor
{
    const SUPPRESSOR_GROUP = 'customer';

    const  SUPPRESSORS = [
        'newAccount' => 'new_account',
        'passwordResetConfirmation' => 'password_reset_confirmation',
        'passwordReminder' => 'password_reminder',
        'credentialsChanged' => 'credentials_changed'
    ];


    /**
     * @param EmailNotificationInterface $subject
     * @param callable $proceed
     * @param CustomerInterface $customer
     * @param $type
     * @param $backUrl
     * @param $storeId
     * @param $sendemailStoreId
     * @return void
     */
    public function aroundNewAccount(
        EmailNotificationInterface $subject,
        callable                   $proceed,
        CustomerInterface          $customer,
                                   $type = EmailNotificationInterface::NEW_ACCOUNT_EMAIL_REGISTERED,
                                   $backUrl = '',
                                   $storeId = 0,
                                   $sendemailStoreId = null)
    {
        if (!$this->mustSuppress(self::SUPPRESSORS['newAccount'], self::SUPPRESSOR_GROUP, $customer->getWebsiteId())) {
            $proceed($customer, $type, $backUrl, $storeId, $sendemailStoreId);
        }
    }

    /**
     * @param EmailNotificationInterface $subject
     * @param callable $proceed
     * @param CustomerInterface $customer
     * @return void
     */
    public function aroundPasswordResetConfirmation(
        EmailNotificationInterface $subject,
        callable                   $proceed,
        CustomerInterface          $customer)
    {
        if (!$this->mustSuppress(self::SUPPRESSORS['passwordResetConfirmation'], self::SUPPRESSOR_GROUP, $customer->getWebsiteId())) {
            $proceed($customer);
        }
    }


    /**
     * @param EmailNotificationInterface $subject
     * @param callable $proceed
     * @param CustomerInterface $customer
     * @return void
     */
    public function aroundPasswordReminder(
        EmailNotificationInterface $subject,
        callable                   $proceed,
        CustomerInterface          $customer)
    {
        if (!$this->mustSuppress(self::SUPPRESSORS['passwordReminder'], self::SUPPRESSOR_GROUP, $customer->getWebsiteId())) {
            $proceed($customer);
        }
    }

    /**
     * @param EmailNotificationInterface $subject
     * @param callable $proceed
     * @param CustomerInterface $savedCustomer
     * @param $origCustomerEmail
     * @param $isPasswordChanged
     * @return void
     */
    public function aroundCredentialsChanged(
        EmailNotificationInterface $subject,
        callable                   $proceed,
        CustomerInterface          $savedCustomer,
                                   $origCustomerEmail,
                                   $isPasswordChanged = false)
    {
        $this->_scopeId = $savedCustomer->getWebsiteId();
        if (!$this->mustSuppress(self::SUPPRESSORS['credentialsChanged'], self::SUPPRESSOR_GROUP, $savedCustomer->getWebsiteId())) {
            $proceed($savedCustomer, $origCustomerEmail, $isPasswordChanged);
        }
    }


}
