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
    protected $_menu = array();
    
/**
 * Current user group
 *
 * @var String
 * @access protected
 */
    protected $_group = null;
    
/**
 * Current depth of menu
 *
 * @var Integer
 * @access protected
 */
    protected $_depth = 0;
    
/**
 * defaults property
 *
 * @var array
 * @access public
 */
    protected $_defaults = array(
        'separator' => false, 
        'children' => null,
        'title' => null,
        'url' => null,
        'alias' => array(),
        'partialMatch' => false,
        'permissions' => array(),
        'id' => null,
        'class' => null,
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
        'childrenClass' => 'has-children', 
        'evenOdd' => false, 
        'itemFormat' => '<li%s>%s%s</li>',
        'wrapperFormat' => '<ul%s>%s</ul>',
        'noLinkFormat' => '<a href="#">%s</a>',
        'menuVar' => 'menu',
        'authVar' => 'user',
        'authModel' => 'User',
        'authField' => 'group',
    );
    
/**
 * Constructor.
 *
 * @access public
 */
    function __construct($config=array()) {
        $this->settings = am($this->settings, $config);
        $view =& ClassRegistry::getObject('view');
        if(!isset($view->viewVars[$this->settings['menuVar']])) return;
        $this->_menu = $view->viewVars[$this->settings['menuVar']];
        
        if(isset($view->viewVars[$this->settings['authVar']])):
            $tmp = $view->viewVars[$this->settings['authVar']];
            if(isset($tmp[$this->settings['authModel']][$this->settings['authField']]))
                $this->_group = $tmp[$this->settings['authModel']][$this->settings['authField']];
        endif;
    }
    
/**
 * Returns the whole menu HTML.
 *
 * @param string optional Array key.
 * @param array optional Aditional Options.
 * @param array optional Data which has the key.
 * @return string HTML menu
 * @access public
 */
    public function build($id=null, $options=array(), &$data=null, &$isActive=false) {
        if(is_null($data)) $data =& $this->_menu;
        
        if(!empty($options)):
            $this->settings = am($this->settings, $options);
        endif;
        
        if(!isset($data[$id])):
            $parent = &$data;
        else:
            $parent = &$data[$id];
        endif;
        
        $out = '';
        $offset = 0;
        $nowIsActive = false;
        if(is_array($parent)) :
            foreach($parent as $pos => $item):
                $this->_depth++;
                $ret = $this->_buildItem($item, $pos-$offset, $nowIsActive);
                if($ret==='') $offset++;
                $out .= $ret;
                $this->_depth--;
                $isActive = $isActive || $nowIsActive;
            endforeach;
        endif;
        
        if($out==='') return '';
        $class = '';
        if(isset($id) && $id!='children') $class = ' id="'.$id.'"';
        if(isset($options['class'])) $class .= ' class="'.$options['class'].'"';
        
        $pad = str_repeat("\t", $this->_depth);
        return sprintf('%s'.$this->settings['wrapperFormat']."\n", $pad, $class, "\n".$out.$pad);
    }
    
/**
 * Returns a menu item HTML.
 *
 * @param array Array of menu item
 * @param int optional Position of the item.
 * @return string HTML menu item
 * @access protected
 */
    protected function _buildItem(&$item, $pos=-1, &$isActive=false) {
        
        $item = am($this->_defaults, $item);
        
        if($item['separator']) return $item['separator'];
        if(is_null($item['title'])) return '';
        
        if(!empty($item['permissions'])):
            if(!in_array($this->_group, (array)$item['permissions'])) return '';
        endif;
        
        $children = '';
        $nowIsActive = false;
        if($hasChildren = is_array($item['children'])):
            $this->_depth++;
            $children = $this->build('children', array(), $item, $nowIsActive);
            $this->_depth--;
        endif;
        
        // For Permissions empty child
        if($children==='') $hasChildren = false;
        
        $check = false;
        if(isset($item['url'])):
            if($item['partialMatch']):
                $check = (strpos(Router::normalize($this->here), Router::normalize($item['url']))===0);
            else :
                $check = Router::normalize($this->here) === Router::normalize($item['url']);
            endif;
        endif;
        
        $isActive = ($nowIsActive || $check);
        
        $arrClass = array();
        if($pos===0) $arrClass[] = $this->settings['firstClass'];
        if($isActive) $arrClass[] = $this->settings['activeClass'];
        if($hasChildren) $arrClass[] = $this->settings['childrenClass'];
        if($this->settings['evenOdd']) $arrClass[] = (($pos&1)?'even':'odd');
        
        $class = '';
        $arrClass = array_filter($arrClass);
        if(isset($item['class'])):
            if(is_array($item['class'])) $arrClass = am($arrClass, $item['class']);
            else $arrClass[] = $item['class'];
        endif;
        if(!empty($arrClass)) $class = ' class="'.implode(' ', $arrClass).'"';
        
        if(isset($item['id'])):
            $class = ' id="'.$item['id'].'"'.$class;
        endif;
        
        if(is_null($item['url'])) $url = sprintf($this->settings['noLinkFormat'], $item['title']);
        else $url = '<a title="'.$item['title'].'" href="'.Router::url($item['url']).'">'.$item['title'].'</a>';
        
        $pad = str_repeat("\t", $this->_depth);
        if($hasChildren):
            $urlPad = str_repeat("\t", $this->_depth+1);
            $url = "\n".$urlPad.$url;
            $children = "\n".$children.$pad;
        else:
        endif;
        
        return sprintf('%s'.$this->settings['itemFormat']."\n", $pad, $class, $url, $children);
    }
    
}
