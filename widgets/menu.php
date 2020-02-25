<?php

/*
 * -------------------------------------
 * www.interactivesweb.com | Rafa Gonzalez Muñoz
 * framework mvc basico
 * menu.php
 * -------------------------------------
 */

class menuWidget extends Widget {

    private $modelo;

    public function __construct() {
        $this->modelo = $this->loadModel('menu');
    }

    public function getMenu($menu, $view, $inverse = null) {
        $data['menu'] = $this->modelo->getMenu($menu);
        $data['inverse'] = $inverse;
        return $this->render($view, $data);
    }

    public function getConfig($menu) {
        /* Sidebar */
        $menus['sidebar'] = array(
            'position' => 'sidebar',
            'show' => array('inicio')
        );
        $menus['sidebar_howtobuy'] = array(
            'position' => 'sidebar',
            'show' => array('inicio')
        );
        $menus['sidebar_pitchandshift'] = array(
            'position' => 'sidebar',
            'show' => array('inicio')
        );
        $menus['sidebar_search'] = array(
            'position' => 'sidebar',
            'show' => '',
            'hide' => ''
        );
        $menus['sidebar_custombackings'] = array(
            'position' => 'sidebar',
            'show' => array('inicio')
        );
        $menus['sidebar_tags'] = array(
            'position' => 'sidebar',
            'show' => '',
        );
        $menus['custom_backings'] = array(
            'position' => 'footer',
            'show' => "all",
        );
        $menus['sidebar_social'] = array(
            'position' => 'footer',
            'show' => "all",
        );
        $menus['contact'] = array(
            'position' => 'footer',
            'show' => "all",
        );
        /* HEADER */
        $menus['top'] = array(
            'position' => 'top',
            'show' => 'all'
        );
        /* BANNER */
        $menus['banner'] = array(
            'position' => 'banner',
            'show' => array("inicio")
        );
        /* SEARCH */
        $menus['search'] = array(
            'position' => 'menu_top',
            'show' => 'all'
        );
        $menus['titlebar'] = array(
            'position' => 'titlebar',
            'show' => 'all'
        );
        $menus['titlebar_search'] = array(
            'position' => 'titlebar',
            'show' => 'all',
            'hide' => array('panel'),
        );
        $menus['body'] = array(
            'position' => 'body',
            'show' => array('panel')
        );
        $menus['menu_user'] = array(
            'position' => 'body',
            'show' => array('panel'),
        );

        return $menus[$menu];
    }

}

?>