<?php
App::uses('AppHelper', 'View/Helper');

/**
 * MenuBuilder Helper
 *
 * This helper will build Dynamic Menu
 *
 * @author M. M. Rifat-Un-Nabi <to.rifat@gmail.com>
 */
class MenuBuilderHelper extends AppHelper {

/**
 * Helper dependencies
 *
 * @var array
 */
	public $helpers = array('Html');

/**
 * Array of global menu
 *
 * @var array
 */
	protected $_menu = array();

/**
 * Current user group
 *
 * @var string
 */
	protected $_group = null;

/**
 * Current depth of menu
 *
 * @var integer
 */
	protected $_depth = 0;

/**
 * Defaults property
 *
 * @var array
 */
	public $defaults = array(
		'separator' => false,
		'children' => null,
		'title' => null,
		'url' => null,
		'ulId' => null,
		'alias' => array(),
		'partialMatch' => false,
		'permissions' => array(),
		'id' => null,
		'class' => null,
	);

/**
 * Settings property
 *
 * @var array
 */
	public $settings = array(
		'activeClass' => 'active',
		'firstClass' => 'first-item',
		'childrenClass' => 'has-children',
		'evenOdd' => false,
		'itemFormat' => '<li%s>%s%s</li>',
		'wrapperFormat' => '<ul%s>%s</ul>',
		'noLinkFormat' => '<a href="#">%s</a>',
		'menuVar' => 'menu',
		'authVar' => 'user',
		'authModel' => 'User',
		'authField' => 'group',
		'indentHtmlOutput' => true,
	);

/**
 * Constructor.
 *
 */
	public function __construct(View $View, $settings = array()) {
		if (isset($settings['defaults'])) {
			$this->defaults = array_merge($this->defaults, $settings['defaults']);
			unset($settings['defaults']);
		}

		$this->settings = array_merge($this->settings, (array)$settings);

		if (!isset($View->viewVars[$this->settings['menuVar']])) {
			return;
		}

		$this->_menu = $View->viewVars[$this->settings['menuVar']];

		if (isset($View->viewVars[$this->settings['authVar']]) &&
				isset($View->viewVars[$this->settings['authVar']][$this->settings['authModel']]) &&
				isset($View->viewVars[$this->settings['authVar']][$this->settings['authModel']][$this->settings['authField']])) {
			$this->_group = $View->viewVars[$this->settings['authVar']][$this->settings['authModel']][$this->settings['authField']];
		}

		parent::__construct($View, (array)$settings);
	}

/**
 * Returns the whole menu HTML.
 *
 * @param string optional Array key.
 * @param array optional Aditional Options.
 * @param array optional Data which has the key.
 * @return string HTML menu
 */
	public function build($id = null, $options = array(), &$data = null, &$isActive = false) {
		if ($data === null) {
			$data =& $this->_menu;
		}

		if (!empty($options)) {
			$this->settings = array_merge($this->settings, $options);
		}

		if (isset($data[$id])) {
			$parent =& $data[$id];
		} else {
			$parent =& $data;
		}

		$out = '';
		$offset = 0;
		$nowIsActive = false;
		if (is_array($parent)) {
			foreach ($parent as $pos => $item) {
				$this->_depth++;

				$ret = $this->_buildItem($item, $pos - $offset, $nowIsActive);

				if ($ret === '') {
					$offset++;
				}

				$out .= $ret;

				$this->_depth--;

				$isActive = $isActive || $nowIsActive;
			}
		}

		if ($out === '') {
			return '';
		}

		$ulId = (isset($this->settings['ulId'])) ? $this->settings['ulId'] : $id;

		$class = array();
		if ($ulId && !$this->_depth) {
			$class[] = $ulId;
		}
		if (!empty($options['class'])) {
			$class[] = $options['class'];
		}
		$class = !empty($class) ? ' class="' . implode(' ', $class) . '"' : '';

		if ($ulId && !$this->_depth) {
			$class .= ' id="' . $ulId . '"';
		}

		if ($this->settings['indentHtmlOutput']) {
			$pad = str_repeat("\t", $this->_depth);
			$ret = "\n";
		} else {
			$pad = $ret = '';
		}

		return sprintf('%s' . $this->settings['wrapperFormat'] . $ret, $pad, $class, $ret . $out . $pad);
	}

/**
 * Returns a menu item HTML.
 *
 * @param array Array of menu item
 * @param integer optional Position of the item.
 * @return string HTML menu item
 */
	protected function _buildItem(&$item, $pos = -1, &$isActive = false) {
		$item = array_merge($this->defaults, $item);

		if ($item['separator']) {
			return $item['separator'];
		}

		if ($item['title'] === null) {
			return '';
		}

		if (!empty($item['permissions']) && !in_array($this->_group, (array)$item['permissions'])) {
			return '';
		}

		$children = '';
		$nowIsActive = false;
		if ($hasChildren = is_array($item['children'])) {
			$this->_depth++;

			$children = $this->build('children', array(), $item, $nowIsActive);

			$this->_depth--;
		}

		// For Permissions empty child
		if ($children === '') {
			$hasChildren = false;
		}

		$check = false;
		if (isset($item['url'])) {
			if ($item['partialMatch']) {
				$check = (strpos(Router::normalize($this->here), Router::normalize($item['url'])) === 0);
			} else {
				$check = Router::normalize($this->here) === Router::normalize($item['url']);
			}
		}

		$isActive = $nowIsActive || $check;

		$arrClass = array();

		if ($pos === 0) {
			$arrClass[] = $this->settings['firstClass'];
		}

		if ($isActive) {
			$arrClass[] = $this->settings['activeClass'];
		}

		if ($hasChildren) {
			$arrClass[] = $this->settings['childrenClass'];
		}

		if ($this->settings['evenOdd']) {
			$arrClass[] = (($pos & 1) ? 'even' : 'odd');
		}

		$class = '';
		$arrClass = array_filter($arrClass);
		if (isset($item['class'])) {
			if (is_array($item['class'])) {
				$arrClass = array_merge($arrClass, $item['class']);
			} else {
				$arrClass[] = $item['class'];
			}
		}

		if (!empty($arrClass)) {
			$class = ' class="' . implode(' ', $arrClass) . '"';
		}

		if (isset($item['id'])) {
			$class = ' id="' . $item['id'] . '"' . $class;
		}

		if ($item['url'] === null) {
			$url = sprintf($this->settings['noLinkFormat'], $item['title']);
		} else {
			$url = '<a title="' . $item['title'] . '" href="' . $this->Html->url($item['url']) . '">' . $item['title'] . '</a>';
		}

		if ($this->settings['indentHtmlOutput']) {
			$pad = str_repeat("\t", $this->_depth);
			$ret = "\n";
		} else {
			$pad = $ret = '';
		}
		if ($hasChildren) {
			$url = $ret . $pad . "\t" . $url;
			$children = $ret . $children . $pad;
		}

		return sprintf('%s' . $this->settings['itemFormat'] . $ret, $pad, $class, $url, $children);
	}

}
