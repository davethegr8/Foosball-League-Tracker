<?php
/**
 * Short description for file.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package       cake
 * @subpackage    cake.tests.fixtures
 * @since         CakePHP(tm) v 1.2.0.4667
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 *
 * @package       cake
 * @subpackage    cake.tests.fixtures
 */
class MyCategoriesMyProductsFixture extends CakeTestFixture {

/**
 * name property
 *
 * @var string 'MyCategoriesMyProducts'
 * @access public
 */
	var $name = 'MyCategoriesMyProducts';

/**
 * fields property
 *
 * @var array
 * @access public
 */
	var $fields = array(
		'my_category_id' => array('type' => 'integer'),
		'my_product_id' => array('type' => 'integer'),
	);

/**
 * records property
 *
 * @var array
 * @access public
 */
	var $records = array(
		array('my_category_id' => 1, 'my_product_id' => 1),
		array('my_category_id' => 2, 'my_product_id' => 1),
		array('my_category_id' => 2, 'my_product_id' => 2),
		array('my_category_id' => 3, 'my_product_id' => 2),
	);
}

?>