<?php
App::uses('MenuGathererComponent', 'MenuBuilder.Controller/Component');
App::uses('Controller', 'Controller');

/**
 * MenuGathererComponent Test Case
 *
 */
class MenuGathererComponentTestCase extends CakeTestCase {

/**
 * SetUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->_admin = Configure::read('Routing.prefixes.0');
		Configure::write('Routing.prefixes.0', 'admin');
		CakeSession::destroy();
		$this->Controller = new TestMenuGathererController(new CakeRequest(), new CakeResponse());
		$this->Controller->constructClasses();
		$this->Controller->startupProcess();

		$this->MenuGatherer = new TestMenuGathererComponent(new ComponentCollection());
	}

/**
 * TearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MenuGatherer);

		parent::tearDown();
	}

/**
 * TestGet method
 *
 * @return void
 */
	public function testGet() {
		$result = $this->MenuGatherer->get();
		$expected = array();
		$this->assertEquals($expected, $result);
	}

/**
 * TestItem method
 *
 * @return void
 */
	public function testItem() {
		$this->MenuGatherer->item('main', array('item1' => array('controller' => 'pages', 'action' => 'display', 'item1')));
		$result = $this->MenuGatherer->get('main');
		$expected = array(array('item1' => array('controller' => 'pages', 'action' => 'display', 'item1'))); //?
		$this->assertEquals($expected, $result);
	}

/**
 * TestMenu method
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
		$this->assertEquals($expected, $result);
	}

/**
 * TestSet method
 *
 * @return void
 */
	public function testSet() {
		$this->MenuGatherer->set(array('item1' => array('controller' => 'pages', 'action' => 'display', 'item1'), 'item1' => array('controller' => 'pages', 'action' => 'display', 'item1')));
		$expected = $this->MenuGatherer->get();
		$result = array('item1' => array('controller' => 'pages', 'action' => 'display', 'item1'));
		$this->assertEquals($expected, $result);
	}

}

class TestMenuGathererComponent extends MenuGathererComponent {

	public $name = 'MenuGatherer';

	public $cacheKey = 'test_menu_storage';

/**
 * TestMenuGathererComponent::getMenu()
 *
 * @return array
 */
	public function getMenu() {
		return $this->_menu;
	}

}

class TestMenuGathererController extends Controller {

	public $components = array('MenuBuilder.TestMenuGatherer');
}

class AuthUser extends CakeTestModel {

	public $name = 'AuthUser';

}

class Controller1Controller extends Controller {

/**
 * Controller1Controller::action1()
 *
 * @return void
 */
	public function action1() {
	}

/**
 * Controller1Controller::action2()
 *
 * @return void
 */
	public function action2() {
	}

}

class Controller2Controller extends Controller {

/**
 * Controller2Controller::action1()
 *
 * @return void
 */
	public function action1() {
	}

/**
 * Controller2Controller::action2()
 *
 * @return void
 */
	public function action2() {
	}

/**
 * Controller2Controller::admin_action()
 *
 * @return void
 */
	public function admin_action() {
	}

}