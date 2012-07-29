<?php
App::uses('MenuBuilderHelper', 'MenuBuilder.View/Helper');
App::uses('Controller', 'Controller');
App::uses('View', 'View');

class MenuBuilderHelperTest extends CakeTestCase {

/**
 * Start Test
 *
 * @return void
 **/
	public function startTest() {
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

		$this->Conroller = new Controller();
		$this->Conroller->set(compact('menu'));
		$this->Conroller->set(compact('guest'));
		$this->Conroller->set(compact('user'));
		$this->Conroller->set(compact('admin'));
		$this->View = new View($this->Conroller);
		$this->View->request = new CakeRequest;
		$this->MenuBuilder = new MenuBuilderHelper($this->View);
	}

/**
 * End Test
 *
 * @return void
 **/
	public function endTest() {
		unset($this->MenuBuilder, $this->view);
	}

/**
 * testBuildDefault Default build test
 *
 * @access public
 * @return void
 */
	public function testBuildDefault() {
		$result = $this->MenuBuilder->build();
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a', '</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a', '</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);
	}

/**
 * testNoLink Menu with no URL
 *
 * @access public
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
				array('li' => array('class' => 'first-item')), array('a' => array('href' => '#')),'Item 1', '</a', '</li',
				'<li', array('a' => array('href' => '#')),'Item 2', '</a', '</li',
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
					array('a' => array('href' => '#')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '#')),'Item 1.1', '</a', '</li',
						'<li', array('a' => array('href' => '#')),'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				'<li', array('a' => array('href' => '#')),'Item 2', '</a', '</li',
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
					array('a' => array('href' => '#')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '#')),'Item 1.1', '</a', '</li',
						'<li', array('a' => array('href' => '#')),'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '#')),'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '#')),'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '#')),'Item 2.2', '</a', '</li',
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
					array('a' => array('href' => '#')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '#')),'Item 1.1', '</a', '</li',
						array('li' => array('class' => 'has-children')),
							array('a' => array('href' => '#')),'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '#')),'Item 1.2.1', '</a', '</li',
								'<li', array('a' => array('href' => '#')),'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '#')),'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '#')),'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '#')),'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

	}

/**
 * testWithLink Menu with URL
 *
 * @access public
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
				array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a', '</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a', '</li',
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
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')),'Item 1.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')),'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a', '</li',
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
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')),'Item 1.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')),'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')),'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')),'Item 2.2', '</a', '</li',
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
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')),'Item 1.1', '</a', '</li',
						array('li' => array('class' => 'has-children')),
							array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')),'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.2.1', 'title' => 'Item 1.2.1')),'Item 1.2.1', '</a', '</li',
								'<li', array('a' => array('href' => '/item-1.2.2', 'title' => 'Item 1.2.2')),'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')),'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')),'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

	}

/**
 * testActiveClass Current Page Active class check
 *
 * @access public
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
				array('li' => array('class' => 'first-item active')), array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a', '</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a', '</li',
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
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')),'Item 1.1', '</a', '</li',
						array('li' => array('class' => 'active')), array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')),'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a', '</li',
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
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')),'Item 1.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')),'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'active has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item active')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')),'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')),'Item 2.2', '</a', '</li',
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
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')),'Item 1.1', '</a', '</li',
						array('li' => array('class' => 'active has-children')),
							array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')),'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.2.1', 'title' => 'Item 1.2.1')),'Item 1.2.1', '</a', '</li',
								array('li' => array('class' => 'active')), array('a' => array('href' => '/item-1.2.2', 'title' => 'Item 1.2.2')),'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')),'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')),'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

	}

 /**
 * testId Test Id
 *
 * @access public
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
				array('li' => array('id' => 'item-1', 'class' => 'first-item')), array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a', '</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a', '</li',
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
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')),'Item 1.1', '</a', '</li',
						array('li' => array('id' => 'item-1.2')), array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')),'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				array('li' => array('id' => 'item-2')), array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a', '</li',
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
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')),'Item 1.1', '</a', '</li',
						array('li' => array('id' => 'item-1.2')), array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')),'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				array('li' => array('id' => 'item-2', 'class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a',
					'<ul',
						array('li' => array('id' => 'item-2.1', 'class' => 'first-item')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')),'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')),'Item 2.2', '</a', '</li',
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
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')),'Item 1.1', '</a', '</li',
						array('li' => array('id' => 'item-1.2', 'class' => 'has-children')),
							array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')),'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.2.1', 'title' => 'Item 1.2.1')),'Item 1.2.1', '</a', '</li',
								array('li' => array('id' => 'item-1.2.2')), array('a' => array('href' => '/item-1.2.2', 'title' => 'Item 1.2.2')),'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				array('li' => array('id' => 'item-2', 'class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a',
					'<ul',
						array('li' => array('id' => 'item-2.1', 'class' => 'first-item')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')),'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')),'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

	}

/**
 * testClass Test Class
 *
 * @access public
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
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item three')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')),'Item 1.1', '</a', '</li',
						array('li' => array('class' => 'active has-children')),
							array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')),'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.2.1', 'title' => 'Item 1.2.1')),'Item 1.2.1', '</a', '</li',
								array('li' => array('class' => 'four')), array('a' => array('href' => '/item-1.2.2', 'title' => 'Item 1.2.2')),'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item five six seven')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')),'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')),'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

	}

/**
 * testWithLink Menu with URL
 *
 * @access public
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
			array('ul' => array('id' => 'first-menu')),
				array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a', '</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a', '</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);


		$result = $this->MenuBuilder->build('second-menu', array(), $menu);
		$expected = array(
			array('ul' => array('id' => 'second-menu')),
				array('li' => array('class' => 'first-item has-children')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')),'Item 1.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')),'Item 1.2', '</a', '</li',
					'</ul',
				'</li',
				'<li', array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a', '</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);
	}

/**
 * testPartialMatch Test Partial URL matching
 *
 * @access public
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
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')),'Item 1.1', '</a', '</li',
						array('li' => array('class' => 'active has-children')),
							array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')),'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.2.1', 'title' => 'Item 1.2.1')),'Item 1.2.1', '</a', '</li',
								'<li', array('a' => array('href' => '/item-1.2.2', 'title' => 'Item 1.2.2')),'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')),'Item 2.1', '</a', '</li',
						'<li', array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')),'Item 2.2', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

	}

/**
 * testPermissions Test URL permission
 *
 * @access public
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
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.1', 'title' => 'Item 1.1')),'Item 1.1', '</a', '</li',
						array('li' => array('class' => 'has-children')),
							array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')),'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.2.1', 'title' => 'Item 1.2.1')),'Item 1.2.1', '</a', '</li',
								'<li', array('a' => array('href' => '/item-1.2.2', 'title' => 'Item 1.2.2')),'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				'<li',
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

		$this->MenuBuilder = new MenuBuilderHelper($this->View, array('authVar' => 'admin'));
		$result = $this->MenuBuilder->build(null, array(), $menu);
		$expected = array(
			'<ul',
				array('li' => array('class' => 'first-item has-children')),
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a',
					'<ul',
						array('li' => array('class' => 'first-item has-children')),
							array('a' => array('href' => '/item-1.2', 'title' => 'Item 1.2')),'Item 1.2', '</a',
							'<ul',
								array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-1.2.1', 'title' => 'Item 1.2.1')),'Item 1.2.1', '</a', '</li',
								'<li', array('a' => array('href' => '/item-1.2.2', 'title' => 'Item 1.2.2')),'Item 1.2.2', '</a', '</li',
							'</ul',
						'</li',
					'</ul',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-2.2', 'title' => 'Item 2.2')),'Item 2.2', '</a', '</li',
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
					array('a' => array('href' => '/item-1', 'title' => 'Item 1')),'Item 1', '</a',
				'</li',
				array('li' => array('class' => 'has-children')),
					array('a' => array('href' => '/item-2', 'title' => 'Item 2')),'Item 2', '</a',
					'<ul',
						array('li' => array('class' => 'first-item')), array('a' => array('href' => '/item-2.1', 'title' => 'Item 2.1')),'Item 2.1', '</a', '</li',
					'</ul',
				'</li',
			'</ul'
		);
		$this->assertTags($result, $expected, true);

	}

}
