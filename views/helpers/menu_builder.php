<?php
/**
 * MenuBuilder Helper
 *
 * This helper will build Dynamic Menu
 *
 * @author M. M. Rifat-Un-Nabi <to.rifat@gmail.com>
 * @package MenuBuilder
 * @subpackage MenuBuilder.views.helpers
 */
App::import('Helper', 'Html');

class MenuBuilderHelper extends AppHelper {
/**
 * Helper dependencies
 *
 * @var array
 * @access public
 */    
    var $helpers = array('Html');

 /**
 * Array of global menu
 *
 * @var array
 * @access public
 */    
    protected $menu = array();
    
/**
 * defaults property
 *
 * @var array
 * @access public
 */
    protected $defaults = array(
        'separator' => false, 
        'submenu' => null,
        'title' => null,
        'url' => null,
        'alias' => null,
    );
    
/**
 * settings property
 *
 * @var array
 * @access public
 */
    public $settings = array(
        'activeClass' => 'active', 
        'firstClass' => 'first-item', 
        'subMenuClass' => 'has-sub-menu', 
        'evenOdd' => false, 
        'itemFormat' => '<li%s>%s%s</li>',
        'wrapperFormat' => '<ul%s>%s</ul>',
        'emptyLinkFormat' => '<a href="#">%s</a>',
        'menuVar' => 'menu',
    );
    
/**
 * Constructor.
 *
 * @access private
 */
    function __construct($config=array()) {
        $this->settings = am($this->settings, $config);
        $view =& ClassRegistry::getObject('view');
        if(!isset($view->viewVars[$this->settings['menuVar']])) return;
        $this->menu = $view->viewVars[$this->settings['menuVar']];
    }
    
/**
 * Returns the whole menu HTML.
 *
 * @param string Array key.
 * @param array optional Data which has the key.
 * @return string HTML menu
 * @access public
 */
    public function build($id, &$data=null) {
        if(is_null($data)) $data =& $this->menu;
        if(!isset($data[$id])) return;
        
        $out = '';
        foreach($data[$id] as $pos => $item) :
            $out .= $this->_buildItem($item, $pos);
        endforeach;
        
        $class = '';
        if($id!='submenu') $class = ' id="'.$id.'"';
        
        return sprintf($this->settings['wrapperFormat'], $class, $out);
    }
    
/**
 * Returns a menu item HTML.
 *
 * @param array Array of menu item
 * @param int optional Position of the item.
 * @return string HTML menu item
 * @access protected
 */
    protected function _buildItem(&$item, $pos=-1) {
        
        $item = am($this->defaults, $item);
        if(is_null($item['title'])) return;
        if($item['separator']) return '';
        
        $subMenu = '';
        if($hasSubMenu=is_array($item['submenu'])) $subMenu = $this->build('submenu', $item);
        $isActive = ($this->here === Router::normalize($item['url']));
        
        $arrClass = array();
        if($pos===0) $arrClass[] = $this->settings['firstClass'];
        if($isActive) $arrClass[] = $this->settings['activeClass'];
        if($hasSubMenu) $arrClass[] = $this->settings['subMenuClass'];
        if($this->settings['evenOdd']) $arrClass[] = (($pos&1)?'even':'odd');
        
        $class = '';
        $arrClass = array_filter($arrClass);
        if(!empty($arrClass)) $class = ' class="'.implode(' ', $arrClass).'"';
        
        if(is_null($item['url'])) $url = sprintf($this->settings['emptyLinkFormat'], $item['title']);
        else $url = '<a title="'.$item['title'].'" href="'.Router::url($item['url']).'">'.$item['title'].'</a>';
        
        return sprintf($this->settings['itemFormat'], $class, $url, $subMenu);
    }
    
}
?>