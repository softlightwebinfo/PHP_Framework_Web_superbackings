<?php

/*
 * -------------------------------------
 * www.interactivesweb.com | Rafa Gonzalez Muñoz
 * framework mvc basico
 * Registry.php
 * -------------------------------------
 */

class Registry {

    private static $_instancia;
    private $_data;

    /**
     * Asegura que no se pueda crear una instancia de la clase
     */
    private function __construct() {
        
    }

    /**
     * Singleton, va a contener la instancia.
     * si este atributo no contiene una instancia del registo, va a crear la instancia del registro y lo va a crear, 
     * si la instancia ya esta creada solo lo va a return.
     */
    public static function getInstancia() {
        //si este atributo no contiene una instancia del registo, va a crear la instancia del registro y lo va a crear, 
        //si la instancia ya esta creada solo lo va a return
        if (!self::$_instancia instanceof self) {
            self::$_instancia = new Registry();
        }
        return self::$_instancia;
    }

    public function __set($name, $value) {
        $this->_data[$name] = $value;
    }

    public function __get($name) {
        if (isset($this->_data[$name])) {
            return $this->_data[$name];
        }
        return false;
    }

}


?>
