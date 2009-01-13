<?php
/** Storefront_Resource_Product */
require_once dirname(__FILE__) . '/Product.php';

/** Storefront_Resource_ProductImageMap */
require_once dirname(__FILE__) . '/ProductImageMap.php';

/**
 * Storefront_Resource_ProductImage
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Resource_ProductImage extends Zend_Db_Table_Abstract
{
    protected $_name = 'productImage';
    protected $_primary = 'imageId';

    protected $_referenceMap = array(
        'Image' => array(
            'columns' => 'productId',
            'refTableClass' => 'Storefront_Resource_Product',
            'refColumns' => 'productId',
        )
    );
}