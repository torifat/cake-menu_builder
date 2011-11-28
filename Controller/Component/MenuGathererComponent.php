<?php
App::uses('Component', 'Controller');
class MenuGathererComponent extends Component {
	protected $_controller;

	protected $_menu = array();

	/**
	 * Constructor
	 *
	 * @param ComponentCollection $collection A ComponentCollection this component can use to lazy load its components
	 * @param array $settings Array of configuration settings.
	 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->_controller = $collection->getController();



		parent::__construct($collection, $settings);
	}

	/**
	 * Initialize component
	 *
	 * @param Controller $controller Instantiating controller
	 * @return void
	 */
	public function initialize(Controller $controller) {

	}

	public function controllerMenu($menu, $controller = null, $actions = array(), $index = null) {
		if (is_null($controller)) {
			foreach (App::objects('Controller') as $controller) {
				$this->controllerMenu($controller);

				return;
			}
		}

		if (is_null($actions)) {
			// List all public actions
		}

		$item = array();

		$this->item($menu, $item, $index);
	}

	public function get($menu = null) {
		if (is_null($menu)) {
			return $this->_menu;
		}

		return $this->_menu[$menu];
	}

	/**
	 * Add an item to a menu at the specified position
	 */
	public function item($menu, $item = array(), $index = null) {
		$this->_checkMenu($menu);

		if (is_null($index)) {
			$this->_menu[$menu][] = $item;

			return;
		}

		$this->_menu = array_splice($this->_menu, $index, 0, $item);
	}

	public function menu($name, $menu = array()) {
		if (is_array($name)) {
			foreach ($name as $key => $val) {
				$this->setMenu($key, $val);
			}
			return;
		}

		$this->_menu[$name] = $menu;
	}

	public function set($menu = array()) {
		$this->_menu = (array) $menu;
	}

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
?>