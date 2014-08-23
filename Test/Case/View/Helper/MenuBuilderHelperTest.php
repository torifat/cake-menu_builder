<?php
App::uses('MenuBuilderHelper', 'MenuBuilder.View/Helper');
App::uses('Controller', 'Controller');
App::uses('View', 'View');

class MenuBuilderHelperTest extends CakeTestCase {

/**
 * Start Test
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$menu = array(
			array(
				'title' => 'Item 1',
				'url' => '/item-1',
			),
			array(
				'title' => 'Item 2',
				'url' => '/item-2',
			),
		);

		$guest = array(
			'User' => array(
				'group' => '',
			)
		);

		$user = array(
			'User' => array(
				'group' => 'user',
			)
		);

		$admin = array(
			'User' => array(
				'group' => 'admin',
			)
		);

		Configure::delete('Routing.prefixes');

		$this->Controller = new Controller();
		$this->Controller->set(compact('menu'));
		$this->Controller->set(compact('guest'));
		$this->Controller->set(compact('user'));
		$this->Controller->set(compact('admin'));
		$this->View = new View($this->Controller);
		$this->View->request = new CakeRequest(null, false);
		$this->MenuBuilder = new MenuBuilderHelper($this->View);
	}

/**
 * End Test
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MenuBuilder, $this->view);
		parent::tearDown();
	}

/**
 * TestBuildDefault Default build test
 *
 * @return void
 */
	public function testBuildDefault() {
		$result = $this->MenuBuilder->build();
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a', '</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a', '</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);
	}

/**
 * TestNoLink Menu with no URL
 *
 * @return void
 */
	public function testNoLink() {
		// Normal Menu
		$menu = array(
			array(
				'title' => 'Item 1',
			),
			array(
				'title' => 'Item 2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item')), array('a' => array('href' => '#')), 'Item 1', '</a', '</li',
				'<li', array('a' => array('href' => '#')), 'Item 2', '</a', '</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		// With One One Level Sub Menu
		$menu[0]['children'] = array(
			array(
				'title' => 'Item 1.1',
			),
			array(
				'title' => 'Item 1.2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item has-children')),
					array('a' => array('href' => '#')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '#')), 'Item 1.1', '</a', '</li',
						'<li', array('a' => array('href' => '#')), 'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				'<li', array('a' => array('href' => '#')), 'Item 2', '</a', '</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		// With Two One Level Sub Menu
		$menu[1]['children'] = array(
			array(
				'title' => 'Item 2.1',
			),
			array(
				'title' => 'Item 2.2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item has-children')),
					array('a' => array('href' => '#')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '#')), 'Item 1.1', '</a', '</li',
						'<li', array('a' => array('href' => '#')), 'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '#')), 'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '#')), 'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '#')), 'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		// With Multi Level Sub Menu
		$menu[0]['children'][1]['children'] = array(
			array(
				'title' => 'Item 1.2.1',
			),
			array(
				'title' => 'Item 1.2.2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item has-children')),
					array('a' => array('href' => '#')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '#')), 'Item 1.1', '</a', '</li',
						array('li' => array('class' => 'has-children')),
							array('a' => array('href' => '#')), 'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '#')), 'Item 1.2.1', '</a', '</li',
								'<li', array('a' => array('href' => '#')), 'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '#')), 'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '#')), 'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '#')), 'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);
	}

/**
 * TestWithLink Menu with URL
 *
 * @return void
 */
	public function testWithLink() {
		// Normal Menu
		$menu = array(
			array(
				'title' => 'Item 1',
				'url' => '/item-1',
			),
			array(
				'title' => 'Item 2',
				'url' => '/item-2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a', '</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a', '</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		// With One One Level Sub Menu
		$menu[0]['children'] = array(
			array(
				'title' => 'Item 1.1',
				'url' => '/item-1.1',
			),
			array(
				'title' => 'Item 1.2',
				'url' => '/item-1.2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item has-children')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')), 'Item 1.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')), 'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a', '</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		// With Two One Level Sub Menu
		$menu[1]['children'] = array(
			array(
				'title' => 'Item 2.1',
				'url' => '/item-2.1',
			),
			array(
				'title' => 'Item 2.2',
				'url' => '/item-2.2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item has-children')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')), 'Item 1.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')), 'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')), 'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')), 'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		// With Multi Level Sub Menu
		$menu[0]['children'][1]['children'] = array(
			array(
				'title' => 'Item 1.2.1',
				'url' => '/item-1.2.1',
			),
			array(
				'title' => 'Item 1.2.2',
				'url' => '/item-1.2.2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item has-children')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')), 'Item 1.1', '</a', '</li',
						array('li' => array('class' => 'has-children')),
							array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')), 'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.2.1', 'title' => 'Item 1.2.1')), 'Item 1.2.1', '</a', '</li',
								'<li', array('a' => array('href' => '/item-1.2.2', 'title' => 'Item 1.2.2')), 'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')), 'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')), 'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);
	}

/**
 * TestActiveClass Current Page Active class check
 *
 * @return void
 */
	public function testActiveClass() {
		// Normal Menu
		$this->MenuBuilder->here = '/item-1';
		$menu = array(
			array(
				'title' => 'Item 1',
				'url' => '/item-1',
			),
			array(
				'title' => 'Item 2',
				'url' => '/item-2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item active')), array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a', '</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a', '</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		// With One One Level Sub Menu
		$this->MenuBuilder->here = '/item-1.2';
		$menu[0]['children'] = array(
			array(
				'title' => 'Item 1.1',
				'url' => '/item-1.1',
			),
			array(
				'title' => 'Item 1.2',
				'url' => '/item-1.2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item active has-children')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')), 'Item 1.1', '</a', '</li',
						array('li' => array('class' => 'active')), array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')), 'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a', '</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		// With Two One Level Sub Menu
		$this->MenuBuilder->here = '/item-2.1';
		$menu[1]['children'] = array(
			array(
				'title' => 'Item 2.1',
				'url' => '/item-2.1',
			),
			array(
				'title' => 'Item 2.2',
				'url' => '/item-2.2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item has-children')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')), 'Item 1.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')), 'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'active has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item active')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')), 'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')), 'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		// With Multi Level Sub Menu
		$this->MenuBuilder->here = '/item-1.2.2';
		$menu[0]['children'][1]['children'] = array(
			array(
				'title' => 'Item 1.2.1',
				'url' => '/item-1.2.1',
			),
			array(
				'title' => 'Item 1.2.2',
				'url' => '/item-1.2.2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item active has-children')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')), 'Item 1.1', '</a', '</li',
						array('li' => array('class' => 'active has-children')),
							array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')), 'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.2.1', 'title' => 'Item 1.2.1')), 'Item 1.2.1', '</a', '</li',
								array('li' => array('class' => 'active')), array('a' => array('href' => '/item-1.2.2', 'title' => 'Item 1.2.2')), 'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')), 'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')), 'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);
	}

/**
 * TestId Test Id
 *
 * @return void
 */
	public function testId() {
		// Normal Menu
		$menu = array(
			array(
				'title' => 'Item 1',
				'id' => 'item-1',
				'url' => '/item-1',
			),
			array(
				'title' => 'Item 2',
				'url' => '/item-2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('id' => 'item-1', 'class' => 'first-item')), array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a', '</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a', '</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		// With One One Level Sub Menu
		unset($menu[0]['id']);
		$menu[1]['id'] = 'item-2';
		$menu[0]['children'] = array(
			array(
				'title' => 'Item 1.1',
				'url' => '/item-1.1',
			),
			array(
				'title' => 'Item 1.2',
				'url' => '/item-1.2',
				'id' => 'item-1.2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item has-children')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')), 'Item 1.1', '</a', '</li',
						array('li' => array('id' => 'item-1.2')), array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')), 'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				array('li' => array('id' => 'item-2')), array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a', '</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		// With Two One Level Sub Menu
		$menu[1]['children'] = array(
			array(
				'title' => 'Item 2.1',
				'url' => '/item-2.1',
				'id' => 'item-2.1',
			),
			array(
				'title' => 'Item 2.2',
				'url' => '/item-2.2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item has-children')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')), 'Item 1.1', '</a', '</li',
						array('li' => array('id' => 'item-1.2')), array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')), 'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				array('li' => array('id' => 'item-2', 'class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a',
					'<ul',
						array('li' => array('id' => 'item-2.1', 'class' => 'first-item')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')), 'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')), 'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		// With Multi Level Sub Menu
		$menu[0]['children'][1]['children'] = array(
			array(
				'title' => 'Item 1.2.1',
				'url' => '/item-1.2.1',
			),
			array(
				'title' => 'Item 1.2.2',
				'url' => '/item-1.2.2',
				'id' => 'item-1.2.2',
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item has-children')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')), 'Item 1.1', '</a', '</li',
						array('li' => array('id' => 'item-1.2', 'class' => 'has-children')),
							array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')), 'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.2.1', 'title' => 'Item 1.2.1')), 'Item 1.2.1', '</a', '</li',
								array('li' => array('id' => 'item-1.2.2')), array('a' => array('href' => '/item-1.2.2', 'title' => 'Item 1.2.2')), 'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				array('li' => array('id' => 'item-2', 'class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a',
					'<ul',
						array('li' => array('id' => 'item-2.1', 'class' => 'first-item')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')), 'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')), 'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);
	}

/**
 * TestClass Test Class
 *
 * @return void
 */
	public function testClass() {
		// With Multi Level Sub Menu
		$this->MenuBuilder->here = '/item-1.2';
		$menu = array(
			array(
				'title' => 'Item 1',
				'url' => '/item-1',
				'class' => array('one', 'two'),
				'children' => array(
					array(
						'title' => 'Item 1.1',
						'url' => '/item-1.1',
						'class' => array('three'),
					),
					array(
						'title' => 'Item 1.2',
						'url' => '/item-1.2',
						'children' => array(
							array(
								'title' => 'Item 1.2.1',
								'url' => '/item-1.2.1',
							),
							array(
								'title' => 'Item 1.2.2',
								'url' => '/item-1.2.2',
								'class' => 'four',
							),
						),
					),
				),
			),
			array(
				'title' => 'Item 2',
				'url' => '/item-2',
				'children' => array(
					array(
						'title' => 'Item 2.1',
						'url' => '/item-2.1',
						'class' => array('five', 'six', 'seven'),
					),
					array(
						'title' => 'Item 2.2',
						'url' => '/item-2.2',
					),
				),
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item active has-children one two')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item three')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')), 'Item 1.1', '</a', '</li',
						array('li' => array('class' => 'active has-children')),
							array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')), 'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.2.1', 'title' => 'Item 1.2.1')), 'Item 1.2.1', '</a', '</li',
								array('li' => array('class' => 'four')), array('a' => array('href' => '/item-1.2.2', 'title' => 'Item 1.2.2')), 'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item five six seven')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')), 'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')), 'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);
	}

/**
 * TestWithLink Menu with URL
 *
 * @return void
 */
	public function testMultipleMenu() {
		// Normal Menu
		$menu = array(
			'first-menu' => array(
				array(
					'title' => 'Item 1',
					'url' => '/item-1',
				),
				array(
					'title' => 'Item 2',
					'url' => '/item-2',
				),
			),
			'second-menu' => array(
				array(
					'title' => 'Item 1',
					'url' => '/item-1',
					'children' => array(
						array(
							'title' => 'Item 1.1',
							'url' => '/item-1.1',
						),
						array(
							'title' => 'Item 1.2',
							'url' => '/item-1.2',
						),
					),
				),
				array(
					'title' => 'Item 2',
					'url' => '/item-2',
				),
			),
		);

		$result = $this->MenuBuilder->build('first-menu', array(), $menu);
		$expected = array(
			array('ul' => array('class' => 'first-menu', 'id' => 'first-menu')),
				array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a', '</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a', '</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		$result = $this->MenuBuilder->build('second-menu', array(), $menu);
		$expected = array(
			array('ul' => array('class' => 'second-menu', 'id' => 'second-menu')),
				array('li' => array('class' => 'first-item has-children')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')), 'Item 1.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')), 'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a', '</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);
	}

/**
 * TestPartialMatch Test Partial URL matching
 *
 * @return void
 */
	public function testPartialMatch() {
		// With Multi Level Sub Menu
		$this->MenuBuilder->here = '/item-1.2/1.2.3';
		$menu = array(
			array(
				'title' => 'Item 1',
				'url' => '/item-1',
				'children' => array(
					array(
						'title' => 'Item 1.1',
						'url' => '/item-1.1',
					),
					array(
						'title' => 'Item 1.2',
						'url' => '/item-1.2',
						'partialMatch' => true,
						'children' => array(
							array(
								'title' => 'Item 1.2.1',
								'url' => '/item-1.2.1',
							),
							array(
								'title' => 'Item 1.2.2',
								'url' => '/item-1.2.2',
							),
						),
					),
				),
			),
			array(
				'title' => 'Item 2',
				'url' => '/item-2',
				'children' => array(
					array(
						'title' => 'Item 2.1',
						'url' => '/item-2.1',
						'partialMatch' => true,
					),
					array(
						'title' => 'Item 2.2',
						'url' => '/item-2.2',
					),
				),
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item active has-children')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')), 'Item 1.1', '</a', '</li',
						array('li' => array('class' => 'active has-children')),
							array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')), 'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.2.1', 'title' => 'Item 1.2.1')), 'Item 1.2.1', '</a', '</li',
								'<li', array('a' => array('href' => '/item-1.2.2', 'title' => 'Item 1.2.2')), 'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')), 'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')), 'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);
	}

/**
 * TestPermissions Test URL permission
 *
 * @return void
 */
	public function testPermissions() {
		// With Multi Level Sub Menu
		$menu = array(
			array(
				'title' => 'Item 1',
				'url' => '/item-1',
				'children' => array(
					array(
						'title' => 'Item 1.1',
						'url' => '/item-1.1',
						'permissions' => array('user'),
					),
					array(
						'title' => 'Item 1.2',
						'url' => '/item-1.2',
						'permissions' => array('user', 'admin'),
						'children' => array(
							array(
								'title' => 'Item 1.2.1',
								'url' => '/item-1.2.1',
							),
							array(
								'title' => 'Item 1.2.2',
								'url' => '/item-1.2.2',
							),
						),
					),
				),
			),
			array(
				'title' => 'Item 2',
				'url' => '/item-2',
				'children' => array(
					array(
						'title' => 'Item 2.1',
						'url' => '/item-2.1',
						'permissions' => array(''),
					),
					array(
						'title' => 'Item 2.2',
						'url' => '/item-2.2',
						'permissions' => array('admin'),
					),
				),
			),
		);

		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item has-children')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')), 'Item 1.1', '</a', '</li',
						array('li' => array('class' => 'has-children')),
							array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')), 'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.2.1', 'title' => 'Item 1.2.1')), 'Item 1.2.1', '</a', '</li',
								'<li', array('a' => array('href' => '/item-1.2.2', 'title' => 'Item 1.2.2')), 'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				'<li',
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		$this->MenuBuilder = new MenuBuilderHelper($this->View, array('authVar' => 'admin'));
		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item has-children')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item has-children')),
							array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')), 'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.2.1', 'title' => 'Item 1.2.1')), 'Item 1.2.1', '</a', '</li',
								'<li', array('a' => array('href' => '/item-1.2.2', 'title' => 'Item 1.2.2')), 'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')), 'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		$this->MenuBuilder = new MenuBuilderHelper($this->View, array('authVar' => 'guest'));
		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')), 'Item 2.1', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);
	}

/**
 * testBootstrapMenu Test Bootstrap Themed Menu
 *
 * @return void
 */
	public function testBootstrapMenu() {
		//no children
		$options = array(
			'childrenClass' => 'has-children',
			'menuClass' => 'dashboard-menu',
			'wrapperClass' => 'submenu',
			'noLinkFormat' => '<a class="dropdown-toggle" href="#"><i class="fa fa-cog"></i><span>%s</span><i class="fa fa-chevron-down"></i></a>',
		);
		$result = $this->MenuBuilder->build('user', $options);
		$expected = array(
			'ul' => array('class' => 'user dashboard-menu', 'id' => 'user'),
				'li' => array('class' => 'first-item'),
				array('a' => array('title' => 'Item 1', 'href' => '/item-1')), 'Item 1', '</a',
				'</li',
				'<li',
				array('a' => array('title' => 'Item 2', 'href' => '/item-2')), 'Item 2', '</a',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		// With Multi Level Sub Menu
		$menu = array(
			array(
				'title' => 'Item 1',
				'url' => '/item-1',
				'children' => array(
					array(
						'title' => 'Item 1.1',
						'url' => '/item-1.1',
						'permissions' => array('user'),
					),
					array(
						'title' => 'Item 1.2',
						'url' => '/item-1.2',
						'permissions' => array('user', 'admin'),
						'children' => array(
							array(
								'title' => 'Item 1.2.1',
								'url' => '/item-1.2.1',
							),
							array(
								'title' => 'Item 1.2.2',
								'url' => '/item-1.2.2',
							),
						),
					),
				),
			),
			array(
				'title' => 'Item 2',
				'url' => '/item-2',
				'children' => array(
					array(
						'title' => 'Item 2.1',
						'url' => '/item-2.1',
						'permissions' => array(''),
					),
					array(
						'title' => 'Item 2.2',
						'url' => '/item-2.2',
						'permissions' => array('admin'),
					),
				),
			),
		);
		$result = $this->MenuBuilder->build('test', $options, $menu);
		$expected = array(
			array('ul' => array('class' => 'test dashboard-menu', 'id' => 'test')),
			array('li' => array('class' => 'first-item has-children')),
				array('a' => array('title' => 'Item 1', 'href' => '/item-1')), 'Item 1', '</a',
			array('ul' => array('class' => 'submenu')),
			array('li' => array('class' => 'first-item')),
				array('a' => array('title' => 'Item 1.1', 'href' => '/item-1.1')), 'Item 1.1', '</a',
			'</li',
			array('li' => array('class' => 'has-children')),
				array('a' => array('title' => 'Item 1.2', 'href' => '/item-1.2')), 'Item 1.2', '</a',
			array('ul' => array('class' => 'submenu')),
			array('li' => array('class' => 'first-item')),
				array('a' => array('title' => 'Item 1.2.1', 'href' => '/item-1.2.1')), 'Item 1.2.1', '</a',
			'</li',
			'<li',
				array('a' => array('title' => 'Item 1.2.2', 'href' => '/item-1.2.2')), 'Item 1.2.2', '</a',
			'</li',
			'</ul',
			'</li',
			'</ul',
			'</li',
			'<li',
			array('a' => array('title' => 'Item 2', 'href' => '/item-2')), 'Item 2', '</a',
			'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);
	}

/**
 * Test that target attribute works.
 *
 * @return void
 */
	public function testMenuWithTargetLinks() {
		$options = array(
			'menuClass' => 'dashboard-menu',
		);
		// With Multi Level Sub Menu
		$menu = array(
			array(
				'title' => 'Item 1',
				'url' => '/item-1',
				'target' => '_blank',
			),
			array(
				'title' => 'Item 2',
				'url' => '/item-2',
			),
		);
		$result = $this->MenuBuilder->build('test', $options, $menu);
		$expected = array(
			array('ul' => array('class' => 'test dashboard-menu', 'id' => 'test')),
			array('li' => array('class' => 'first-item')),
			array('a' => array('title' => 'Item 1', 'href' => '/item-1', 'target' => '_blank')), 'Item 1', '</a',
			'</li',
			'<li',
			array('a' => array('title' => 'Item 2', 'href' => '/item-2')), 'Item 2', '</a',
			'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);
	}

/**
 * testImageMenu Test Images in Menu
 *
 * @return void
 */
	public function testImageMenu() {
		// Necessary hack to prevent CakePHP from calculating wrong webroot dir for CLI testing on travis.
		$this->MenuBuilder->request->webroot = '/';

		$options = array(
			'menuClass' => 'dashboard-menu',
		);
		// With Multi Level Sub Menu
		$menu = array(
			array(
				'title' => 'Item 1',
				'url' => '/item-1',
			),
			array(
				'title' => 'Item 2',
				'url' => '/item-2',
				'image' => '/path/my-image.jpg',
			),
		);
		$result = $this->MenuBuilder->build('test', $options, $menu);
		$expected = array(
			array('ul' => array('class' => 'test dashboard-menu', 'id' => 'test')),
			array('li' => array('class' => 'first-item')),
			array('a' => array('title' => 'Item 1', 'href' => '/item-1')), 'Item 1', '</a',
			'</li',
			'<li',
			array('a' => array('title' => 'Item 2', 'href' => '/item-2')),
			array('img' => array('src' => '/path/my-image.jpg', 'alt' => 'Item 2')),
			array('span' => array('class' => 'label')),
			'Item 2',
			'</span',
			'</a',
			'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);
	}

/**
 * testSanitizeOfLinkTitle Test clearing link title attribute of undesirable characters
 *
 * @return void
 */
	public function testSanitizeOfLinkTitle() {
		$menu = array(
			array(
				'title' => 'Item 1',
				'url' => '/item-1'
			),
			array(
				'title' => 'Item 2&nbsp;<i class="fa fa-caret-right"></i>',
				'url' => '/item-2'
			)
		);
		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1', 'title' => 'Item 1')), 'Item 1', '</a', '</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')), 'Item 2&amp;nbsp;&lt;i class=&quot;fa fa-caret-right&quot;&gt;&lt;/i&gt;', '</a', '</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);
	}

}
