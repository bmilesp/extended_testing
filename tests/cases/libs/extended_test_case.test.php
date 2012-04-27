<?php
/**
 * ExtendedTestCaseTest file
 *
 * Test Case for ExtendedTestCase class
 *
 * PHP versions 4 and 5
 *
 * Brandon Plasters (bmilesp)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2012, Brandon Platers
 * @link          http://blog.brandonplasters.com
 * @package       extended_testing
 * @subpackage    extended_testing.libs.
 * @since         CakePHP v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Libs', 'ExtendedTesting.ExtendedTestCase');

if (!class_exists('AppController')) {
	require_once LIBS . 'controller' . DS . 'app_controller.php';
} elseif (!defined('APP_CONTROLLER_EXISTS')) {
	define('APP_CONTROLLER_EXISTS', true);
}

Mock::generate('CakeHtmlReporter');
Mock::generate('ExtendedTestCase', 'CakeDispatcherMockTestCase');

SimpleTest::ignore('SubjectExtendedTestCase');
SimpleTest::ignore('CakeDispatcherMockTestCase');

/**
 * SubjectExtendedTestCase
 *
 */
class SubjectExtendedTestCase extends ExtendedTestCase {

/**
 * Feed a Mocked Reporter to the subject case
 * prevents its pass/fails from affecting the real test
 *
 * @param string $reporter
 * @access public
 * @return void
 */
	function setReporter(&$reporter) {
		$this->_reporter = &$reporter;
	}

/**
 * testDummy method
 *
 * @return void
 * @access public
 */
	function testDummy() {
	}
}

/**
 * ExtendedTestCaseTest
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs
 */
class ExtendedTestCaseTest extends ExtendedTestCase {

/**
 * setUp
 *
 * @access public
 * @return void
 */
	function setUp() {
		$this->_debug = Configure::read('debug');
		$this->Case =& new SubjectExtendedTestCase();
		$reporter =& new MockCakeHtmlReporter();
		$this->Case->setReporter($reporter);
		$this->Reporter = $reporter;
	}

/**
 * tearDown
 *
 * @access public
 * @return void
 */
	function tearDown() {
		Configure::write('debug', $this->_debug);
		unset($this->Case);
		unset($this->Reporter);
	}

/**
 * endTest
 *
 * @access public
 * @return void
 */
	function endTest() {
		App::build();
	}

/**
 * testAssertGoodTags
 *
 * @access public
 * @return void
 */

	function testAssertIsSubsetVariations(){
		//test args are not arrays
		//1:
		$result = $this->assertIsSubsetWrapped('', array());
		$this->assertNotNull($result['errorMsg']);
		//2:
		$result = $this->assertIsSubsetWrapped(array(), 'xx');
		$this->assertNotNull($result['errorMsg']);
		//both:
		$result = $this->assertIsSubsetWrapped('x', 'xx');
		$this->assertNotNull($result['errorMsg']);
		//empty
		$result = $this->assertIsSubsetWrapped(array(), array());
		$this->assertEqual($result['match'],1);
		
		//test unNested arrays
		$array1 = array(
			'id' => '112',
		);
		
		$array2 = array(
			'id' => '112',
			'created' => '2012-12-12'
		);
		$this->assertIsSubset($array1, $array2);
		
		//test unNested arrays false
		$array1 = array(
			'id' => '111',
		);
		
		$array2 = array(
			'id' => '112',
			'created' => '2012-12-12'
		);
		
		$result = $this->assertIsSubsetWrapped($array1, $array2);
		$this->assertNotEqual($result['errorVals']['first'], $result['errorVals']['second']);
		
		//test nested arrays
		$array1 = array(
			'id' => '112',
			'subArray' =>array(
				'id' => '223',
				'label' => 'yes'
			)
		);
		
		$array2 = array(
			'id' => '112',
			'created' => '2012-12-12',
			'subArray' =>array(
				'id' => '223',
				'label' => 'yes',
				'created' => '2010-11-12',
				'modified' => '2012-10-20'
			)
		);	
		
		$this->assertIsSubset($array1, $array2);
		
		//test nested arrays error
		$array1 = array(
			'id' => '112',
			'subArray' =>array(
				'id' => '223',
				'label' => 'no'
			)
		);
		
		$array2 = array(
			'id' => '112',
			'created' => '2012-12-12',
			'subArray' =>array(
				'id' => '223',
				'label' => 'yes',
				'created' => '2010-11-12',
				'modified' => '2012-10-20'
			)
		);	
		
		$result = $this->assertIsSubsetWrapped($array1, $array2);
		$this->assertNotEqual($result['errorVals']['first'], $result['errorVals']['second']);
		
		//test nested arrays numeric keys
		$array1 = array(
			'id' => '112',
			'subArray' =>array(
				'id' => '223',
				'label' => 'yes',
				'lists' => array(
					array('ok','ok','yes')
				)
			)
		);
		
		$array2 = array(
			'id' => '112',
			'created' => '2012-12-12',
			'subArray' =>array(
				'id' => '223',
				'label' => 'yes',
				'created' => '2010-11-12',
				'modified' => '2012-10-20',
				'lists' => array(
					array('ok','ok','yes')
				)
			)
		);	
		
		$this->assertIsSubset($array1, $array2);
		
		//test nested arrays numeric keys
		$array1 = array(
			'id' => '112',
			'subArray' =>array(
				'id' => '223',
				'label' => 'yes',
				'lists' => array(
					array('ok','ok')
				)
			)
		);
		
		$array2 = array(
			'id' => '112',
			'created' => '2012-12-12',
			'subArray' =>array(
				'id' => '223',
				'label' => 'yes',
				'created' => '2010-11-12',
				'modified' => '2012-10-20',
				'lists' => array(
					array('ok','ok','yes')
				)
			)
		);	
		
		$this->assertIsSubset($array1, $array2);
		
		//test ERROR nested arrays gap in numeric keys
		$array1 = array(
			'id' => '112',
			'subArray' =>array(
				'id' => '223',
				'label' => 'yes',
				'lists' => array(
					array('ok','yes')
				)
			)
		);
		
		$array2 = array(
			'id' => '112',
			'created' => '2012-12-12',
			'subArray' =>array(
				'id' => '223',
				'label' => 'yes',
				'created' => '2010-11-12',
				'modified' => '2012-10-20',
				'lists' => array(
					array('ok','ok','yes')
				)
			)
		);	
		
		$result = $this->assertIsSubsetWrapped($array1, $array2);
		$this->assertNotEqual($result['errorVals']['first'], $result['errorVals']['second']);
		
		//test nested arrays numeric keys then deeper nesting
		$array1 = array(
			'id' => '112',
			'subArray' =>array(
				'id' => '223',
				'label' => 'yes',
				'lists' => array(
					array('ok','ok','yes', array(
						'deep' => true,
						'false' => false,
						'null' => NULL,
						'array' => array(1,2,3)
					))
				)
			)
		);
		
		$array2 = array(
			'id' => '112',
			'created' => '2012-12-12',
			'subArray' =>array(
				'id' => '223',
				'label' => 'yes',
				'created' => '2010-11-12',
				'modified' => '2012-10-20',
				'lists' => array(
					array('ok','ok','yes', array(
						'deep' => true,
						'false' => false,
						'null' => NULL,
						'array' => array(1,2,3)
					))
				)
			)
		);	
		
		$this->assertIsSubset($array1, $array2);
		
		//test ERROR nested arrays numeric keys then deeper nesting with deep error
		$array1 = array(
			'id' => '112',
			'subArray' =>array(
				'id' => '223',
				'label' => 'yes',
				'lists' => array(
					array('ok','ok','yes', array(
						'deep' => true,
						'false' => false,
						'null' => NULL,
						'array' => array(1,2,'error')
					))
				)
			)
		);
		
		$array2 = array(
			'id' => '112',
			'created' => '2012-12-12',
			'subArray' =>array(
				'id' => '223',
				'label' => 'yes',
				'created' => '2010-11-12',
				'modified' => '2012-10-20',
				'lists' => array(
					array('ok','ok','yes', array(
						'deep' => true,
						'false' => false,
						'null' => NULL,
						'array' => array(1,2,3)
					))
				)
			)
		);	
		
		$result = $this->assertIsSubsetWrapped($array1, $array2);
		$this->assertNotEqual($result['errorVals']['first'], $result['errorVals']['second']);
		
	}
 
}