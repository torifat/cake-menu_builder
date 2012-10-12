# MenuBuilder Helper

A dynamic menu building helper for CakePHP

## Background

This is a menu building helper with lot of customization options. Check out the **Usage** section.

Now it supports menus built with [ACL Menu Component](http://mark-story.com/posts/view/acl-menu-component) by [Mark Story](http://mark-story.com/)

## Features

* Generate menu based on current user type/group/permission/level (Can be used with Auth, Authsome, etc Components)
* Provide various useful CSS class
* Multi-level menu support
* Supports [ACL Menu Component](http://mark-story.com/posts/view/acl-menu-component) by [Mark Story](http://mark-story.com/)
* CakePHP Unit Test (100% Code coverage)

## Requirements

* Built for PHP 5.* I'm not interested about PHP 4 but you can modify it easily :)
* CakePHP 2.0.0

## Installation

### Manual

* Download this: http://github.com/torifat/cake-menu_builder/zipball/2.0
* Unzip that download.
* Copy the resulting folder to `app/plugins`
* Rename the folder you just copied to `menu_builder`

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
Load the Plugin by modifying your app/Config/bootstrap.php

    <?php
    ...
    CakePlugin::load('MenuBuilder');
    ?>

or

    <?php
    ...
    CakePlugin::loadAll();
    ?>

To use this helper add the following to your AppController:

    <?php
    ...
    var $helpers = array(..., 'MenuBuilder.MenuBuilder');
    
    function beforeFilter() {
        ...
        // Define your menu
        $menu = array(
            'main-menu' => array(
                array(
                    'title' => 'Home',
                    'url' => array('controller' => 'pages', 'action' => 'home'),
                ),
                array(
                    'title' => 'About Us',
                    'url' => '/pages/about-us',
                ),
            ),
            'left-menu' => array(
                array(
                    'title' => 'Item 1',
                    'url' => array('controller' => 'items', 'action' => 'view', 1),
                    'children' => array(
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
        ...
    }
    ?>

Now to build your `main-menu` use the following code in the View:

    <?php 
        echo $this->MenuBuilder->build('main-menu');
    ?>

You'll get the following output in the Home (/pages/home) page:
    
    <ul id="main-menu"> 
        <li class="first-item active"><a title="Home" href="/pages/home">Home</a></li> 
        <li><a title="About Us" href="/pages/about-us">About Us</a></li> 
    </ul> 

And to build your `left-menu` use the following code in the View:

    <?php 
        echo $this->MenuBuilder->build('left-menu'); 
    ?>

You'll get the following output in your 'Item 4' (/items/view/4) page:

    <ul id="left-menu"> 
        <li class="first-item active has-children"> 
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
    
You can pass optional parameter in `build` function like -

    <?php
        echo $this->MenuBuilder->build('main-menu', array('class' => array('fun', 'red'));
        // OR
        echo $this->MenuBuilder->build('main-menu', array('class' => 'fun green');
    ?>

## Advance Setup

You can provide advance options in the array like the following:

    <?php
    ...
    var $helpers = array(
        ...
        'MenuBuilder.MenuBuilder' => array(/* array of settings */)
    );
    ?>

### Default Settings
if you do not provide any settings then the following settings will work.

    $settings = array(
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

### Settings Details

**activeClass**
CSS classname for the selected/current item and it's successors. *(default - `'active'`)*

**firstClass**
CSS classname for the first item of each level. *(default - `'first-item'`)*

**childrenClass**
CSS classname for an item containing sub menu. *(default - `'has-children'`)*

**evenOdd**
If it is set to `true` then even/odd classname will be provided with each item. *(default - `false`)*
    
    <ul id="main-menu"> 
        <li class="first-item odd"> 
            <a title="Home" href="/pages/home">Home</a> 
        </li> 
        <li class="even"> 
            <a title="About Us" href="/pages/about-us">About Us</a> 
        </li> 
    </ul>

**itemFormat**
if you want to use other tag than `li` for menu items *(default - `'<li%s>%s%s</li>'`)*

**wrapperFormat**
if you want to use other tag than `ul` for menu items container *(default - `'<ul%s>%s</ul>'`)*

**noLinkFormat**
Format for empty link item *(default - `'<a href="#">%s</a>'`)*

*Example Setting*

    'MenuBuilder.MenuBuilder' => array(
        'itemFormat' => '<div%s>%s%s</div>',
        'wrapperFormat' => '<div%s>%s</div>',
        'noLinkFormat' => '<div>%s</div>',
    ),

*Example Output (an extra item added to explain `noLinkFormat`)*

    <div id="main-menu"> 
        <div class="first-item"> 
            <a title="Home" href="/pages/home">Home</a> 
        </div> 
        <div> 
            <a title="About Us" href="/pages/about-us">About Us</a> 
        </div>
        <div> 
            Empty
        </div> 
    </div>

**menuVar**
Name of the variable that contains all menus *(default - `'menu'`)*

*Following settings will be used for permission based menu (see below)*

**authVar**
Name of the variable that contains the `User` data *(default - `'user'`)*

**authModel**
Name of the authentication model *(default - `'User'`)*

**authField**
Name of the field that contains the user's type/group/permission/level *(default - `'group'`)*

## Permission Based Menu

Suppose you have a `users` table like the following one:

    CREATE TABLE `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `email` varchar(255) NOT NULL,
        `password` char(40) NOT NULL,
        `group` enum('user','manager','admin') NOT NULL DEFAULT 'user',
        `name` varchar(255) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

Now suppose you are using the CakePHP auth component for authentication so add the following to your AppController:

    <?php
    ...
    function beforeFilter() {
        ...
        $user = $this->Auth->user();
        $this->set(compact('user'));
    }
    ?>

But, If you are using [Authsome](https://github.com/felixge/cakephp-authsome) component for authentication then add the following to your AppController:

    <?php
    ...
    function beforeFilter() {
        ...
        $user = Authsome::get();
        $this->set(compact('user'));
    }
    ?>

Now we have to define permissions in our menu like this:

    <?php
    ...
    function beforeFilter() {
        ...
        // Define your menu
        $menu = array(
            'main-menu' => array(
                // Anybody can see this
                array(
                    'title' => 'Home',
                    'url' => array('controller' => 'pages', 'action' => 'home'),
                ),
                // Users and Admins can see this, Guests and Managers can't
                array(
                    'title' => 'About Us',
                    'url' => array('controller' => 'pages', 'action' => 'about-us'),
                    'permissions' => array('user','admin'),
                ),
                // Only Guests can see this
                array(
                    'title' => 'Login',
                    'url' => array('controller' => 'users', 'action' => 'login'),
                    'permissions' => array(''),
                ),
            ),
            ...
        );        
        // For default settings name must be menu
        $this->set(compact('menu'));
        ...
    }
    ?>

**You're Done!**

### Other Menu Options
**permissions**
Array of type/group/permission/level whose can view that item *(default - `array()`)*

**partialMatch**
Normally `url` matching are strict. Suppose you are in `/items/details` and your menu contains an entry for `/items` then by default it'll not set active. But if you set `partialMatch` to `true` then it'll set active . *(default - `false`)*

**id**
Provide CSS id to the item *(default - `null`)*

**class**
Provide CSS class to the item *(default - `null`)*

**separator**
If you want to define some separator in your menu, below is a nice example of what you can do with it. *(default - `false`)*

*Example Setting*

    'MenuBuilder.MenuBuilder' => array(
        'itemFormat' => '<dd%s>%s%s</dd>',
        'wrapperFormat' => '<dl%s>%s</dl>',
        'noLinkFormat' => '<dd>%s</dd>',
    ),

*Example Menu*

    <?php
    ...
    var $helpers = array(..., 'MenuBuilder.MenuBuilder');
    
    function beforeFilter() {
        ...
        // Define your menu
        $menu = array(
            'main-menu' => array(
                array(
                    'separator' => '<dt>Main Menu</dt>',
                ),
                array(
                    'title' => 'Home',
                    'url' => array('controller' => 'pages', 'action' => 'home'),
                ),
                array(
                    'title' => 'About Us',
                    'url' => '/pages/about-us',
                ),
            )
        );
        
        // For default settings name must be menu
        $this->set(compact('menu'));
        ...
    }
    ?>


*Example Output*

    <dl id="main-menu"> 
        <dt>Main Menu</dt> 
        <dd class="first-item"> 
            <a title="Home" href="/pages/home">Home</a> 
        </dd> 
        <dd> 
            <a title="About Us" href="/pages/about-us">About Us</a> 
        </dd> 
    </dl>
    
**More to come :)**

## ToDo

*Add More Test Cases*


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
