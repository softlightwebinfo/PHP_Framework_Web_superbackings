<?php

class Functions {

    /**
     * Crear carpeta en el servidor partiendo de /
     * @param type $ruta
     * @param type $carp
     * @return boolean
     */
    static public function CrearCarpeta($ruta = null, $carp) {
        if ($ruta == null) {
            $carpeta = "modules/usuarios/ftp/$carp";
        } else {
            $carpeta = $ruta;
        }
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
            return true;
        } else {
            mkdir($carpeta, 0777, true);
            return true;
        }
    }

    /**
     * 
     * @param type $ruta
     */
    static public function CrearFiles($ruta, $content) {
        $file = fopen($ruta, "w");
        fwrite($file, $content . PHP_EOL);
        fclose($file);
    }

    static public function DeleteFiles($ruta) {
        unlink($ruta);
//        if (file_exists($ruta)) {
//            if (unlink($ruta))
//                return true;
//        } else
//            return false;
    }

    /**
     * Chequeamos si existen los archivos en el sistema pasando la ruta en la variable
     * @param type $ruta
     * @return boolean
     */
    static public function checkFiles($ruta) {
        if (file_exists($ruta)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Detectar sistema Operativo y navegador de quien navega
     * @return string
     */
    static public function detectSistema() {
        /**
         * Función para detectar el sistema operativo, navegador y versión del mismo
         */
        /**
         * Funcion que devuelve un array con los valores:
         * 	os => sistema operativo
         * 	browser => navegador
         * 	version => version del navegador
         */
        $browser = array("IE", "OPERA", "MOZILLA", "NETSCAPE", "FIREFOX", "SAFARI", "CHROME");
        $os = array("WINDOWS", "MAC", "LINUX");

# definimos unos valores por defecto para el navegador y el sistema operativo
        $info['browser'] = "OTHER";
        $info['os'] = "OTHER";
# buscamos el navegador con su sistema operativo
        foreach ($browser as $parent) {
            $s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
            $f = $s + strlen($parent);
            $version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
            $version = preg_replace('/[^0-9,.]/', '', $version);
            if ($s) {
                $info['browser'] = $parent;
                $info['version'] = $version;
            }
        }

# obtenemos el sistema operativo
        foreach ($os as $val) {
            if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $val) !== false)
                $info['os'] = $val;
        }

# devolvemos el array de valores
        return $info;
    }

    /**
     * Download de un archivo
     * @param type $path
     * @param type $speed
     * @return boolean
     */
    static public function Download($path, $speed = null) {
        if (is_file($path) === true) {
            $file = @fopen($path, 'rb');
            $speed = (isset($speed) === true) ? round($speed * 1024) : 524288;

            if (is_resource($file) === true) {
                set_time_limit(0);
                ignore_user_abort(false);

                while (ob_get_level() > 0) {
                    ob_end_clean();
                }

                header('Expires: 0');
                header('Pragma: public');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Content-Type: application/octet-stream');
                header('Content-Length: ' . sprintf('%u', filesize($path)));
                header('Content-Disposition: attachment; filename="' . basename($path) . '"');
                header('Content-Transfer-Encoding: binary');

                while (feof($file) !== true) {
                    echo fread($file, $speed);

                    while (ob_get_level() > 0) {
                        ob_end_flush();
                    }

                    flush();
                    sleep(1);
                }

                fclose($file);
            }

            exit();
        }

        return false;
    }

    /**
     * Devuelve numeros aleatorios entre $inicio y $final
     * @param type $inicio
     * @param type $final
     * @return type
     */
    static public function random($inicio, $final) {
        $random = rand($inicop, $final);
        return $random;
    }

    /**
     * Genera alphanumericos aleatorios con el numero de caracteres 
     * @param type $length
     * @return string
     */
    static public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Te hace una cuenta de los dias que faltan para el evento
     * 
      $minutes_to_add = 10;
      $fecha = new DateTime($fechas);
      $fecha->add(new DateInterval('PT' . $minutes_to_add . 'D'));
      $fecha_data = $fecha->format('Y-m-d H:i:s') . "\n";
      $fecha = Functions::haceTiempo($fecha_data, null, null);
      echo $fecha;
     * 
     * @param type $antes
     * @param type $date
     * @return type
     */
    static public function haceTiempo($antes, $date = null) {
        $dt1 = new DateTime($antes);

        $data = date("Y-m-d H:i:s");
        $dt2 = new DateTime($data);
        $i = $dt1->diff($dt2);

        if ($date) {

            return array(
                "dias" => $i->format('%a'),
                "horas" => $i->format('%h'),
                "minutos" => $i->format('%i'),
                "segundos" => $i->format('%s')
            );
        } else {
            echo $i->format('quedan %a dias %h horas %i minuto(s) %s segundo(s)');
        }
//quedan 2 dias 13 horas 1 minuto(s) 1 segundo(s) 
    }

    /**
     * 
     * @param type $start
     * @param type $end
     * @return string
     */
    static public function _date_diff($start, $end = "NOW") {


        $sdate = strtotime($start);
        $edate = strtotime($end);

        $time = ($edate > $sdate) ? $edate - $sdate : $sdate - $edate;



        if ($time >= 0 && $time <= 59) {
// Seconds
            $timeshift = $time . ' seconds ';
        } elseif ($time >= 60 && $time <= 3599) {
// Minutes + Seconds
            $pmin = ($edate - $sdate) / 60;
            $premin = explode('.', $pmin);

            $presec = $pmin - $premin[0];
            $sec = $presec * 60;

            $timeshift = $premin[0] . ' min ' . round($sec, 0) . ' sec ';
        } elseif ($time >= 3600 && $time <= 86399) {
// Hours + Minutes
            $phour = ($edate - $sdate) / 3600;
            $prehour = explode('.', $phour);

            $premin = $phour - $prehour[0];
            $min = explode('.', $premin * 60);

            $presec = '0.' . $min[1];
            $sec = $presec * 60;

//$timeshift = $prehour[0].' hrs '.$min[0].' min '.round($sec,0).' sec ';
            $timeshift = $prehour[0] . ' hrs ' . $min[0] . ' min ';
        } elseif ($time >= 86400) {
// Days + Hours + Minutes
            $pday = ($edate - $sdate) / 86400;
            $preday = explode('.', $pday);

            $phour = $pday - $preday[0];
            $prehour = explode('.', $phour * 24);

            $premin = ($phour * 24) - $prehour[0];
            $min = explode('.', $premin * 60);

            $presec = '0.' . $min[1];
            $sec = $presec * 60;

            $timeshift = $preday[0] . ' days ' . $prehour[0] . ' hrs';
//$timeshift = $preday[0].' days '.$prehour[0].' hrs '.$min[0].' min '.round($sec,0).' sec ';
        }
        return $timeshift;
    }

    /**
     * Copiamos archivos de una url a otra
     * @param type $type
     */
    static public function copyFiles($type = "http") {
        switch ($type) {
            case "http":
                copy("http://superbackings.com/public/img/1.png", "public/1-copy.png");
                break;
            case "FTP":
                copy("/home/archkzt/KTZS/125.ktz", "public/125.ktz");

                break;
            case "FTP":

                copy("ssh2.sftp://user:pass@host:22/usr/..../archivo", "ssh2.sftp://user:pass@host:22/usr/..../archivo");
                break;
            default:
                break;
        }
    }

    /**
     * FTP
     * @param type $source
     * @param type $destination
     */
    static public function FTP($source, $destination) {
// variables
        $ftp_server = "67.23.247.176";
        $ftp_user_name = "archkzt";
        $ftp_user_pass = "yhwh=777";
        $local_file = 'modules/usuarios/1000.KZT'; //Nombre archivo en nuestro PC
        $server_file = 'KTZS/1000.KZT'; //Nombre archivo en FTP
// establecer una conexión o finalizarla

        $conn_id = ftp_connect($ftp_server) or die("No se pudo conectar a $ftp_server");

// intentar iniciar sesión
        if (@ftp_login($conn_id, $ftp_user_name, $ftp_user_pass)) {
            echo "Conectado como $ftp_user_name@$ftp_server\n";
// archivo a copiar/subir

            if (ftp_get($conn_id, $destination, $source, FTP_BINARY)) {
                echo "Se descargado el archivo con éxito\n";
            } else {
                echo "Ha ocurrido un error\n";
            }
        } else {
            echo "No se pudo conectar como $ftp_user_name\n";
        }

// cerrar la conexión ftp
        ftp_close($conn_id);
    }

    /**
     * DATOS DEL PICH
     * @param type $pitch
     * @param type $name
     * @param type $fin
     * @return boolean
     */
    static public function PITCH($pitch, $name, $fin) {

        switch ($pitch) {
            case "1": $pitchs = 100;
                $type = "_1";
                break;
            case "2": $pitchs = 200;
                $type = "_2";
                break;
            case "3": $pitchs = 300;
                $type = "_3";
                break;
            case "4": $pitchs = 400;
                $type = "_4";
                break;
            case "-1": $pitchs = -100;
                $type = "-1";
                break;
            case "-2": $pitchs = -200;
                $type = "-2";
                break;
            case "-3": $pitchs = -300;
                $type = "-3";
                break;
            case "-4": $pitchs = -400;
                $type = "-4";
                break;
        }
//echo "pitch = $pitch | name=$name | fin = $fin"; 
        /* Primera posicion, 1 Sentimos = 1 semitono,posicion final */
        if ($pitch != 0) {
            if (system("/usr/local/bin/sox $name $fin$type.mp3 --show-progress pitch $pitchs stats", $pitchado)) {
                echo $pitchado;
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Print_p(Array);
     * @param type $datos
     */
    public static function Print_pree($datos) {
        echo "<pre>";
        print_r($datos);
        echo "</pre>";
    }

//    static public function Download($download, $order) {
//        $usuario = Session::get("usuario");
//        $download = basename($download);
//        $ruta = BASE_URL . "modules/usuarios/ftp/$usuario/$order/$download";
//        echo $ruta;
//        if (file_exists($ruta)) {
//            return $ruta;
//        } else {
//            return "false";
//        }
//    }

    /* ARRAYS FUNCTIONS */

    /**
     * Search donde 
     * @param type $array
     * @param type $matching
     * @return boolean
     */
    static public function findWhere($array, $matching) {
        foreach ($array as $item) {
            $is_match = true;
            foreach ($matching as $key => $value) {

                if (is_object($item)) {
                    if (!isset($item->$key)) {
                        $is_match = false;
                        break;
                    }
                } else {
                    if (!isset($item[$key])) {
                        $is_match = false;
                        break;
                    }
                }

                if (is_object($item)) {
                    if ($item->$key != $value) {
                        $is_match = false;
                        break;
                    }
                } else {
                    if ($item[$key] != $value) {
                        $is_match = false;
                        break;
                    }
                }
            }

            if ($is_match) {
                return $item;
            }
        }

        return false;
    }

    static public function paypal_PDT_request($tx, $pdt_identity_token) {
        $request = curl_init();

        // Set request options
        curl_setopt_array($request, array
            (
            CURLOPT_URL => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => http_build_query(array
                (
                'cmd' => '_notify-synch',
                'tx' => $tx,
                'at' => $pdt_identity_token,
                    )
            ),
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER => FALSE,
                // CURLOPT_SSL_VERIFYPEER => TRUE,
                // CURLOPT_CAINFO => 'cacert.pem',
                )
        );

        // Realizar la solicitud y obtener la respuesta
        // y el código de status
        $response = curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);

        // Cerrar la conexión
        curl_close($request);
        return $response;
    }

    static public function SendEmail() {
        
    }

    static public function GetCodeVentas($codigos) {
        $datos = "SELECT * FROM ventas WHERE id_code ='{$codigos}'";
        $data = $this->_db->query($datos);
        $dato = $data->fetchAll(PDO::FETCH_ASSOC);
        $count = count($dato);
        if ($count == 0) {
            $codigo = $codigos;
        } else {
            $codigo = null;
            while ($count != 0) {
                $codigo = Functions::generateRandomString(50);
                $datos = "SELECT * FROM ventas WHERE id_code ='{$codigo}'";
                $data = $this->_db->query($datos);
                $dato = $data->fetchAll(PDO::FETCH_ASSOC);
                $count = count($dato);
            }
        }
        return $codigo;
    }

}
