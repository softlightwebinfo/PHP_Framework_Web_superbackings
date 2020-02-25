<?php

/*
 * -------------------------------------
 * www.interactivesweb.com | Rafa Gonzalez Muñoz
 * framework mvc basico
 * View.php
 * -------------------------------------
 */

class View {

    private $_request;
    private $_js;
    private $_acl;
    private $_rutas;
    private $_jsPlugin;
    private $template_dir;
    private $contenido;
    private $_template;
    private static $_item;
    private $_widget;

    public function __construct(Request $peticion, ACL $_acl) {
        $this->_request = $peticion;
        $this->_js = array();
        $this->_acl = $_acl;
        $this->_rutas = array();
        $this->_jsPlugin = array();
        $this->_template = DEFAULT_LAYOUT;
        self::$_item = null;
        $modulo = $this->_request->getModulo();
        $controlador = $this->_request->get_controlador();
        if ($modulo) {
            $this->_rutas['view'] = ROOT . 'modules' . DS . $modulo . DS . 'views' . DS . $controlador . DS;
            $this->_rutas['js'] = BASE_URL . 'modules/' . $modulo . '/views/' . $controlador . '/js/';
        } else {
            $this->_rutas['view'] = ROOT . 'views' . DS . $controlador . DS;
            $this->_rutas['js'] = BASE_URL . 'views/' . $controlador . '/js/';
        }
    }

    public static function getViewId() {
        return self::$_item;
    }

    public function renderizar($vista, $item = false, $noLayout = false) {
        if ($item) {
            self::$_item = $item;
        }
//        print_r($this->getWidgets());

        $this->template_dir = ROOT . "views" . DS . "layout" . DS . $this->_template . DS;
        $this->contenido = $this->_rutas['view'] . $vista . ".phtml";
        $_layoutParams = array(
            "ruta_css" => BASE_URL . "views/layout/" . $this->_template . "/css/",
            "ruta_img" => BASE_URL . "views/layout/" . $this->_template . "/img/",
            "ruta_js" => BASE_URL . "views/layout/" . $this->_template . "/js/",
            "js" => $this->_js,
            'item' => $item,
            'js_plugin' => $this->_jsPlugin,
            'root' => BASE_URL,
            'configs' => array(
                'app_name' => APP_NAME,
                'app_slogan' => APP_SLOGAN,
                'app_company' => APP_COMPANY
            )
        );
//        echo "<pre>";print_r($this->_rutas);exit;
        if (is_readable($this->_rutas['view'] . $vista . ".phtml")) {
            if ($noLayout) {
                $this->template = $this->_rutas['view'];
                include_once $this->contenido;
                exit();
            }

            $this->_contenido = $this->contenido;

//            include_once $this->_rutas['view'] . $vista . ".phtml";
//            include_once ROOT . "views" . DS . "layout" . DS . DEFAULT_LAYOUT . DS . "footer.php";
        } else {
            throw new Exception("Error de vista");
        }
        $this->_widgets = $this->getWidgets();
        $this->_acl = $this->_acl;
        $this->_layoutParams = $_layoutParams;
        include_once $this->template_dir . "template.php";
    }

    public function setJs(array $js) {
        if (is_array($js) && count($js)) {
            for ($i = 0; $i < count($js); $i++) {
                $this->_js[] = $this->_rutas['js'] . $js[$i] . '.js';
            }
        } else {
            throw new Exception('Error de js');
        }
    }

    public function setJsPlugin(array $js) {
        if (is_array($js) && count($js)) {
            for ($i = 0; $i < count($js); $i++) {
                $this->_jsPlugin[] = BASE_URL . 'public/js/' . $js[$i] . '.js';
            }
        } else {
            throw new Exception('Error de js plugin');
        }
    }

    public function setTemplate($template) {
        $this->_template = (string) $template;
    }

    public function widget($widget, $method, $options = array()) {
        if (!is_array($options)) {
            $options = array($options);
        }
        if (is_readable(ROOT . 'widgets' . DS . $widget . ".php")) {
            include_once ROOT . 'widgets' . DS . $widget . ".php";
            $widgetClass = $widget . 'Widget';
            if (!class_exists($widgetClass)) {
                throw new Exception("Error clase widget");
            }
            if (is_callable($widgetClass, $method)) {
                if (count($options)) {
                    return call_user_func_array(array(new $widgetClass, $method), $options);
                } else {
                    return call_user_func(array(new $widgetClass, $method));
                }
            }
            throw new Exception('Error metodo widget');
        }
        throw new Exception('Error d widget');
    }

    public function getLayoutPositions() {
        if (is_readable(ROOT . 'views' . DS . 'layout' . DS . $this->_template . DS . 'configs.php')) {
            include_once ROOT . 'views' . DS . 'layout' . DS . $this->_template . DS . 'configs.php';
            return get_layout_positions();
        }
        throw new Exception('Error configuracion layout');
    }

    private function getWidgets() {
        /* Configurar Widgets ala vista */
        /* Config: menu widget,metodo getGonfig
         * $this->widget
         * 
         * Content: array(widget,metodo,);
         */
        $widgets = array(
            'menu-sidebar' => array(
                'config' => $this->widget('menu', 'getConfig', array('sidebar')),
                'content' => array('menu', 'getMenu', array('sidebar', 'sidebar'))
            ),
            'menu-top' => array(
                'config' => $this->widget('menu', 'getConfig', array('top')),
                'content' => array('menu', 'getMenu', array('top', 'top'))
            ),
            'menu-sidebar_howtobuy' => array(
                'config' => $this->widget('menu', 'getConfig', array('sidebar_howtobuy')),
                'content' => array('menu', 'getMenu', array('sidebar_howtobuy', 'sidebar_howtobuy'))
            ),
            'menu-sidebar_pitchandshift' => array(
                'config' => $this->widget('menu', 'getConfig', array('sidebar_pitchandshift')),
                'content' => array('menu', 'getMenu', array('sidebar_pitchandshift', 'sidebar_pitchandshift'))
            ),
            'menu-sidebar_custombackings' => array(
                'config' => $this->widget('menu', 'getConfig', array('sidebar_custombackings')),
                'content' => array('menu', 'getMenu', array('sidebar_search', 'sidebar_custombackings'))
            ),
            'menu-sidebar_search' => array(
                'config' => $this->widget('menu', 'getConfig', array('sidebar_search')),
                'content' => array('menu', 'getMenu', array('sidebar_search', 'sidebar_search'))
            ),
            'menu-sidebar_tags' => array(
                'config' => $this->widget('menu', 'getConfig', array('sidebar_tags')),
                'content' => array('menu', 'getMenu', array('sidebar_tags', 'sidebar_tags'))
            ),
            'custom_backings' => array(
                'config' => $this->widget('menu', 'getConfig', array('custom_backings')),
                'content' => array('menu', 'getMenu', array('custom_backings', 'custom_backings'))
            ),
            'contact' => array(
                'config' => $this->widget('menu', 'getConfig', array('contact')),
                'content' => array('menu', 'getMenu', array('contact', 'contact'))
            ),
            'menu-sidebar_social' => array(
                'config' => $this->widget('menu', 'getConfig', array('sidebar_social')),
                'content' => array('menu', 'getMenu', array('sidebar_social', 'sidebar_social'))
            ),
            'menu-banner' => array(
                'config' => $this->widget('menu', 'getConfig', array('banner')),
                'content' => array('menu', 'getMenu', array('banner', 'banner'))
            ),
            'menu-search' => array(
                'config' => $this->widget('menu', 'getConfig', array('search')),
                'content' => array('menu', 'getMenu', array('search', 'search'))
            ),
            'menu-titlebar' => array(
                'config' => $this->widget('menu', 'getConfig', array('titlebar')),
                'content' => array('menu', 'getMenu', array('titlebar', 'titlebar'))
            ),
            'menu-titlebar-search' => array(
                'config' => $this->widget('menu', 'getConfig', array('titlebar_search')),
                'content' => array('menu', 'getMenu', array('titlebar_search', 'titlebar_search'))
            ),
            'menu-body' => array(
                'config' => $this->widget('menu', 'getConfig', array('body')),
                'content' => array('menu', 'getMenu', array('body', 'body'))
            ),
            'menu-body-menuser' => array(
                'config' => $this->widget('menu', 'getConfig', array('menu_user')),
                'content' => array('menu', 'getMenu', array('menu_user', 'menu_user'))
            ),
        );

        $positions = $this->getLayoutPositions();
        $keys = array_keys($widgets);

        foreach ($keys as $k) {
            /* verificar si la posicion del widget esta presente */
            if (isset($positions[$widgets[$k]['config']['position']])) {
                /* verificar si esta deshabilitado para la vista */
                if (!isset($widgets[$k]['config']['hide']) || !in_array(self::$_item, $widgets[$k]['config']['hide'])) {
                    /* verificar si esta habilitado para la vista */
                    if ($widgets[$k]['config']['show'] === 'all' || in_array(self::$_item, $widgets[$k]['config']['show'])) {
                        if (isset($this->_widget[$k])) {
                            $widgets[$k]['content'][2] = $this->_widget[$k];
                        }

                        /* llenar la posicion del layout */
                        $positions[$widgets[$k]['config']['position']][] = $this->getWidgetContent($widgets[$k]['content']);
                    }
                }
            }
        }

        return $positions;
    }

    public function getWidgetContent(array $content) {
        if (!isset($content[0]) || !isset($content[1])) {
            throw new Exception('Error contenido widget');
            return;
        }
        if (!isset($content[2])) {
            $content[2] = array();
        }
        return $this->widget($content[0], $content[1], $content[2]);
    }

    public function setWidgetOptions($key, $options) {
        $this->_widget[$key] = $options;
    }

}

?>
