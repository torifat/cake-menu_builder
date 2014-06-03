<?php
App::uses('Component', 'Controller');

class MenuGathererComponent extends Component {

	protected $_controller;

	protected $_menu = array();

/**
 * Initialize component
 *
 * @param Controller $controller Instantiating controller
 * @return void
 */
	public function initialize(Controller $controller) {
		parent::initialize($controller);

		$this->_controller = $controller;
	}

/**
 * MenuGathererComponent::get()
 *
 * @param string $menu Menu
 * @return array Menu data
 */
	public function get($menu = null) {
		if ($menu === null) {
			return $this->_menu;
		}

		return $this->_menu[$menu];
	}

/**
 * Add an item to a menu at the specified position
 *
 * @param string $menu Menu
 * @param array $item Item
 * @param int $index Index
 * @return void
 */
	public function item($menu, $item = array(), $index = null) {
		$this->_checkMenu($menu);

		if ($index === null) {
			$this->_menu[$menu][] = $item;
			return;
		}

		$this->_menu = array_splice($this->_menu, $index, 0, $item);
	}

/**
 * MenuGathererComponent::menu()
 *
 * @param mixed $name Name
 * @param mixed $menu Menu
 * @return void
 */
	public function menu($name, $menu = array()) {
		if (is_array($name)) {
			foreach ($name as $key => $val) {
				$this->setMenu($key, $val);
			}
			return;
		}

		$this->_menu[$name] = $menu;
	}

/**
 * MenuGathererComponent::set()
 *
 * @param mixed $menu Menu
 * @return void
 */
	public function set($menu = array()) {
		$this->_menu = (array)$menu;
	}

/**
 * MenuGathererComponent::_checkMenu()
 *
 * @param mixed $name Name
 * @return void
 */
	protected function _checkMenu($name) {
		if (is_array($name)) {
			foreach ($name as $val) {
				$this->_checkMenu($val);
			}

			return;
		}

		if (!isset($this->_menu[$name])) {
			$this->set($name);
		}
	}

}
