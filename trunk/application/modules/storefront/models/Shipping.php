<?php
/**
 * Storefront_Model_Shipping
 *
 * @category   Storefront
 * @package    Storefront_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Model_Shipping extends SF_Model_Abstract
{
    protected $_shippingData = array(
        'Standard' => 1.99,
        'Special'  => 5.99,
    );

    public function getShippingOptions()
    {
        return $this->_shippingData;
    }
}