<?php
App::import('Helper', 'MenuBuilder.MenuBuilder');
App::import('Core', 'View');

class MenuBuilderHelperTest extends CakeTestCase {

/**
 * Start Test
 *
 * @return void
 **/
    function startTest(){
        $this->MenuBuilder = new MenuBuilderHelper();
        //$this->View = new View($this->Controller);
    }

/**
 * End Test
 *
 * @return void
 **/
    function endTest(){
        unset($this->MenuBuilder, $this->view);
    }
    
/**
 * testNoLink Menu with no URL
 *
 * @access public
 * @return void
 */
    function testNoLink() {
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
    function testWithLink() {
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
    function testActiveClass() {
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

}
