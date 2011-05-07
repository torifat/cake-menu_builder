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

App::import('Helper', 'Html', 'Router');

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
 * @access protected
 */
    protected $menu = array();
    
/**
 * Current user group
 *
 * @var String
 * @access protected
 */
    protected $group = null;
    
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
        'alias' => array(),
        'partialMatch' => false,
        'permissions' => array(),
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
        'authVar' => 'user',
        'authModel' => 'User',
        'authField' => 'group',
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
        
        if(isset($view->viewVars[$this->settings['authVar']])):
            $tmp = $view->viewVars[$this->settings['authVar']];
            if(isset($tmp[$this->settings['authModel']][$this->settings['authField']]))
                $this->group = $tmp[$this->settings['authModel']][$this->settings['authField']];
        endif;
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
        $token = array();
        $status = false;
        if(is_array($data[$id])) :
            foreach($data[$id] as $pos => $item) :
                $token = $this->_buildItem($item, $pos);
                $out .= $token[0];
                $status = $status || $token[1];
            endforeach;
        endif;
        
        $class = '';
        if($id!='submenu') $class = ' id="'.$id.'"';
        
        $out = sprintf($this->settings['wrapperFormat'], $class, $out);
        if($id=='submenu') return array($out, $status);
        return $out;
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
        
        $ret = array('', false);
        $item = am($this->defaults, $item);
        
        if($item['separator']) return array($item['separator'], false);
        if(is_null($item['title'])) return $ret;
        
        if(!empty($item['permissions'])):
            if(!in_array($this->group, (array)$item['permissions'])) return $ret;
        endif;
        
        $token = array('', false);
        if($hasSubMenu = is_array($item['submenu'])) $token = $this->build('submenu', $item);
        $subMenu = $token[0];
        
        $check = false;
        if($item['partialMatch']):
            $check = (strpos(Router::normalize($this->here), Router::normalize($item['url']))===0);
        else :
            $check = Router::normalize($this->here) === Router::normalize($item['url']);
        endif;
        
        $isActive = $token[1] || (!is_null($item['url']) && $check);
        
        $arrClass = array();
        if($pos===0) $arrClass[] = $this->settings['firstClass'];
        if($isActive) $arrClass[] = $this->settings['activeClass'];
        if($hasSubMenu) $arrClass[] = $this->settings['subMenuClass'];
        if($this->settings['evenOdd']) $arrClass[] = (($pos&1)?'even':'odd');
        
        $class = '';
        $arrClass = array_filter($arrClass);
        if(isset($item['class'])):
            if(is_array($item['class'])) $arrClass = am($arrClass, $item['class']);
            else $arrClass[] = $item['class'];
        endif;
        if(!empty($arrClass)) $class = ' class="'.implode(' ', $arrClass).'"';
        
        if(is_null($item['url'])) $url = sprintf($this->settings['emptyLinkFormat'], $item['title']);
        else $url = '<a title="'.$item['title'].'" href="'.Router::url($item['url']).'">'.$item['title'].'</a>';
        
        return array(sprintf($this->settings['itemFormat'], $class, $url, $subMenu), $isActive);
    }
    
}
