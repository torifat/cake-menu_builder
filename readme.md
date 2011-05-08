# MenuBuilder Plugin

A dynamic menu building helper for CakePHP

## Background

This is a menu building helper with lot of customization options. Check out the [[Usage]] section.

## Requirements

* Generate menu based on current user type/group/permission/level
* Provide various useful CSS class
* Multi-level menu support

## Requirements

* Built for PHP 5.* I'm not interested about PHP 4
* CakePHP 1.3.*. Untested with the 1.2.x series, but should work fine

## Installation

### Manual

# Download this: http://github.com/torifat/cake-menu_builder/zipball/master
# Unzip that download.
# Copy the resulting folder to app/plugins
# Rename the folder you just copied to @menu_builder@

### GIT Submodule

In your app directory type:

    git submodule add git://github.com/torifat/cake-menu_builder.git plugins/menu_builder
    git submodule init
    git submodule update

###GIT Clone

In your plugin directory type
    git clone git://github.com/torifat/cake-menu_builder.git menu_builder

# Usage

## Minimal Setup
To use this helper add the following to your AppController:

    <?php
    var $helpers = array('MenuBuilder.MenuBuilder');
    
    function beforeFilter() {
        // Define your menu
        $menu = array(
            'main-menu' => array(
                array(
                    'title' => 'Home',
                    'url' => array('controller' => 'pages', 'action' => 'home'),
                ),
                array(
                    'title' => 'About Us',
                    'url' => array('controller' => 'pages', 'action' => 'about-us'),
                ),
            ),
            'left-menu' => array(
                array(
                    'title' => 'Item 1',
                    'url' => array('controller' => 'items', 'action' => 'view', 1),
                    'submenu' => array(
                        array(
                            'title' => 'Item 3',
                            'url' => array('controller' => 'items', 'action' => 'view', 3),
                        ),
                        array(
                            'title' => 'Item 4',
                            'url' => array('controller' => 'items', 'action' => 'view', 4),
                        ),
                    )
                ),
                array(
                    'title' => 'Item 2',
                    'url' => array('controller' => 'items', 'action' => 'view', 2),
                ),
            ),
        );
        
        // For default settings name must be menu
        $this->set(compact('menu'));
    }
    ?>

Now to build your main-menu use the following code in the View:

    <?php 
        echo $menuBuilder->build('main-menu'); 
    ?>

You'll get the following output in the Home (/pages/home) page:
    
    <ul id="main-menu"> 
        <li class="first-item active"><a title="Home" href="/pages/home">Home</a></li> 
        <li><a title="About Us" href="/pages/about-us">About Us</a></li> 
    </ul> 

And to build your left-menu use the following code in the View:

    <?php 
        echo $menuBuilder->build('left-menu'); 
    ?>

You'll get the following output in your 'Item 4' (/items/view/4) page:

    <ul id="left-menu"> 
        <li class="first-item active has-sub-menu"> 
            <a title="Item 1" href="/items/view/1">Item 1</a> 
            <ul> 
                <li class="first-item"> 
                    <a title="Item 3" href="/items/view/3">Item 3</a> 
                </li> 
                <li class="active"> 
                    <a title="Item 4" href="/items/view/4">Item 4</a> 
                </li> 
            </ul> 
        </li> 
        <li> 
            <a title="Item 2" href="/items/view/2">Item 2</a> 
        </li> 
    </ul>

## Advance Setup

You can provide advance options in the array like the following:

    <?php
    var $helpers = array(
        'MenuBuilder.MenuBuilder' => array(/* array of settings */)
    );
    ?>

### Default Settings
if you do not provide any settings then the following settings will work.

    $settings = array(
        'activeClass' => 'active', 
        'firstClass' => 'first-item', 
        'subMenuClass' => 'has-sub-menu', 
        'evenOdd' => false, 
        'itemFormat' => '<li%s>%s%s</li>',
        'wrapperFormat' => '<ul%s>%s</ul>',
        'noLinkFormat' => '<a href="#">%s</a>',
        'menuVar' => 'menu',
        'authVar' => 'user',
        'authModel' => 'User',
        'authField' => 'group',
    );

### Settings Details

**activeClass**
CSS classname for the selected/current item and it's successors.

**firstClass**
CSS classname for the first item of each level.

**subMenuClass**
CSS classname for an item containing sub menu.

**evenOdd**
If it is set to `true` then even/odd classname will be provided with each item.
    
    <ul id="main-menu"> 
        <li class="first-item odd"> 
            <a title="Home" href="/pages/home">Home</a> 
        </li> 
        <li class="even"> 
            <a title="About Us" href="/pages/about-us">About Us</a> 
        </li> 
    </ul>

**itemFormat**
if you want to use other tag than `li` for menu items

**wrapperFormat**
if you want to use other tag than `ul` for menu items container

**noLinkFormat**
Format for empty link item

*Example Setting*

    'MenuBuilder.MenuBuilder' => array(
        'itemFormat' => '<div%s>%s%s</div>',
        'wrapperFormat' => '<div%s>%s</div>',
        'noLinkFormat' => '<div>%s</div>',
    ),

*Example Output*

    <div id="main-menu"> 
        <div class="first-item"> 
            <a title="Home" href="/adn/pages/home">Home</a> 
        </div> 
        <div> 
            <a title="About Us" href="/adn/pages/about-us">About Us</a> 
        </div>
        <div> 
            Empty
        </div> 
    </div>

**menuVar**
Name of the variable that contains all menus

*Following settings will be used for permission based menu (see below)*

**authVar**
Name of the variable that contains the `User` data

**authModel**
Name of the authentication model

**authField**
Name of the field that contains the user's type/group/permission/level

## Permission Based Menu

# License

Copyright (c) 2011 M. M. Rifat-Un-Nabi

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.