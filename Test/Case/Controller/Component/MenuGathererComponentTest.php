<?php
/* MenuGatherer Test cases generated on: 2011-11-28 01:44:52 : 1322444692*/
App::uses('MenuGathererComponent', 'MenuBuilder.Controller/Component');

class TestMenuGathererComponent extends MenuGathererComponent {
	public $cacheKey = 'test_menu_storage';

/**
 * testStop property
 *
 * @var bool false
 */
	public $testStop = false;

/**
 * Fake getControllers to reflect things in TestCase
 *
 * @return void
 **/
	public function getControllers() {
		return array('Controller1', 'Controller2');
	}

}


class TestMenuGathererController extends AppController {
	public $components = array('MenuBuilder.MenuGatherer');
}

class AuthUser extends CakeTestModel {
	public $name = 'AuthUser';
}

class Controller1Controller extends Controller {
	public function action1() {}
	public function action2() {}
}
class Controller2Controller extends Controller {
	public function action1() {}
	public function action2() {}
	public function admin_action() {}
}

/**
 * MenuGathererComponent Test Case
 *
 */
class MenuGathererComponentTestCase extends CakeTestCase {
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->_admin = Configure::read('Routing.prefixes.0');
		Configure::write('Routing.prefixes.0', 'admin');
		$_SESSION = null;
		$this->Controller = new TestMenuGathererController();
		$this->Collection = new ComponentCollection();
		$this->MenuGatherer = new MenuGathererComponent($this->Collection);
		$this->MenuGatherer->startup($this->Controller);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MenuGatherer);

		parent::tearDown();
	}

/**
 * testControllerMenu method
 *
 * @return void
 */
	public function testControllerMenu() {
		//$this->MenuGatherer->controllerMenu('main');
		//$expected = '';
		//"<code><pre>" . h($result) . '</pre></code>';
		//$this->assertEquals($expected, $result);
	}

/**
 * testGet method
 *
 * @return void
 */
	public function testGet() {
		$result = $this->MenuGatherer->get();
		$expected = array();
		"<code><pre>" . h($result) . '</pre></code>';
		$this->assertEquals($expected, $result);
	}

/**
 * testItem method
 *
 * @return void
 */
	public function testItem() {
		$this->MenuGatherer->item('main', array('item1' => array('controller' => 'pages', 'action' => 'display', 'item1')));
		$result = $this->MenuGatherer->get('main');
		//$expected = array('main' => array('item1' => array('controller' => 'pages', 'action' => 'display', 'item1')));
		$expected = array(array('item1' => array('controller' => 'pages', 'action' => 'display', 'item1'))); //?
		"<code><pre>" . h($result) . '</pre></code>';
		$this->assertEquals($expected, $result);
	}

/**
 * testMenu method
 *
 * @return void
 */
	public function testMenu() {
		$this->MenuGatherer->menu('smasher', array(
			array(
				'separator' => '<dt>smasher</dt>',
			),
			array(
				'title' => 'Home',
				'url' => array('controller' => 'pages', 'action' => 'home'),
			),
			array(
				'title' => 'About Me - I am a Smashing Menu system',
				'url' => '/pages/about-menu-builder',
			),
			array(
				'title' => 'Contact the Menu Builder',
				'url' => '/contact',
			),
		));
		$expected = $this->MenuGatherer->get('smasher');
		$result = array(
			array(
				'separator' => '<dt>smasher</dt>',
			),
			array(
				'title' => 'Home',
				'url' => array('controller' => 'pages', 'action' => 'home'),
			),
			array(
				'title' => 'About Me - I am a Smashing Menu system',
				'url' => '/pages/about-menu-builder',
			),
			array(
				'title' => 'Contact the Menu Builder',
				'url' => '/contact',
			),
		);
		"<code><pre>" . h($result) . '</pre></code>';
		$this->assertEquals($expected, $result);
	}

/**
 * testSet method
 *
 * @return void
 */
	public function testSet() {
		$this->MenuGatherer->set(array('item1' => array('controller' => 'pages', 'action' => 'display', 'item1'), 'item1' => array('controller' => 'pages', 'action' => 'display', 'item1')));
		$expected = $this->MenuGatherer->get();
		$result = array('item1' => array('controller' => 'pages', 'action' => 'display', 'item1'));
		"<code><pre>" . h($result) . '</pre></code>';
		$this->assertEquals($expected, $result);
	}

}
