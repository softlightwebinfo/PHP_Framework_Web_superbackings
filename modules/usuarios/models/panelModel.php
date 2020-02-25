<?php

class panelModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function ipReal() {

//Ip del visitante
        if ($_SERVER['REMOTE_ADDR'] == '::1')
            $ipuser = '';
        else
            $ipuser = $_SERVER['REMOTE_ADDR'];

        $geoPlugin_array = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ipuser));

        return array(
            "cont" => $geoPlugin_array['geoplugin_continentCode'],
            "pais" => $geoPlugin_array['geoplugin_countryName'],
            "moneda" => $geoPlugin_array['geoplugin_currencyCode']
        );
    }

    public function ViewMoneda($ip, $precio) {
        if (($ip['moneda'] == "EUR")):
            return $precio . "€";
        elseif ($ip['moneda'] == "GBP"):
            return "£$precio";
        else:
            return "$$precio";
        endif;
    }

    /**
     * Selecciona todo los datos del usuario Logeado
     * @param type $usuario
     * Nombre de usuario
     * @return typeGet Usuarios
     */
    public function getUsuario($usuario) {
        $datos = $this->_db->query(
                "SELECT * FROM usuarios " .
                "WHERE usuario = '{$usuario}' "
        );
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Selecciona el id del usuario logeado
     * @param type $usuario
     * Nombre de usuario
     * @return type
     */
    public function GetUsuarioId($usuario) {
        $datos = $this->_db->query(
                "SELECT id FROM usuarios " .
                "WHERE usuario = '{$usuario}' "
        );
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verifica si existe un email que no tenga el usuario logeado
     * @param type $ids
     * id del usuario
     * @param type $email
     * email del usuario
     * @return boolean
     */
    public function verificarEmail($ids, $email) {
        $id = $this->_db->query(
                "SELECT id FROM usuarios WHERE email = '$email' and id !='{$ids}'"
        );
        if ($id->fetch()) {
            return true;
        }
        return false;
    }

    /**
     * Actualizar datos de usuario
     * @param type $id
     * @param type $nombre
     * @param type $apellido
     * @param type $direccion
     * @param type $whatsapp
     * @param type $pais
     * @param type $ciudad
     * @param type $codigoPostal
     * @param type $facebookAccount
     */
    public function editarDatosUsuarios($id, $nombre, $apellido, $direccion, $whatsapp, $pais, $ciudad, $codigoPostal, $facebookAccount) {
        $id = (int) $id;
        $this->_db->prepare(
                        "UPDATE usuarios SET nombre=:nombre WHERE id=:id"
                )
                ->execute(array(
                    "id" => $id,
                    ":nombre" => $nombre,
        ));

        $this->_db->query(
                "UPDATE datosUsuarios SET apellido='{$apellido}', direccion='{$direccion}', whatsapp='{$whatsapp}', pais='{$pais}', ciudad='{$ciudad}', codigoPostal='{$codigoPostal}', facebookAccount='{$facebookAccount}' WHERE id='{$id}'"
        );
    }

    /**
     * Editar password usuario
     * @param type $id
     * @param type $password
     */
    public function editarPassword($id, $password) {
        $id = (int) $id;
        $pass = Hash::encrypt($password, HASH_KEY);
        $this->_db->prepare(
                        "UPDATE usuarios SET password=:password,keysp=:keys WHERE id=:id"
                )
                ->execute(array(
                    "id" => $id,
                    ":password" => HASH::getHash(HASH_ALGORITMO, $password, HASH_KEY),
                    ":keysp" => $pass
        ));
    }

    /**
     * Seleccionamos todo los tracks donde el $demo es igual a demo y limite a 1
     * @param type $demo
     * @return type
     */
    public function GetCheckoutDemo($demo) {
        $sql = "SELECT * FROM tb_tracks WHERE `demo` = :demo LIMIT 1";
        $q = $this->_db->prepare($sql);
        $q->execute(array(':demo' => $demo));
        $resp = $q->fetchAll(PDO::FETCH_ASSOC);
        return $resp;
    }

    /**
     * Seleccionamos todos los datos de la tabla usuarios y datosUsuarios Con LEFT JOIN donde usuarios.id = id del usuario
     * @param type $usuario
     * @return type
     */
    public function GetDatosUsuarios($usuario) {
        $datos = $this->_db->query(
                "SELECT 
                    usuarios.*, 
                    datosUsuarios.* 
                 FROM 
                    usuarios 
                 LEFT JOIN 
                    datosUsuarios 
                 ON 
                    usuarios.id = datosUsuarios.id 
                 WHERE 
                    usuarios.id = '{$usuario}' "
        );
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Seleccionamos todas las ventas con el idUsuario='a id del usuario logeado' y lo ordenamos por la fecha de fin de compra en DESC
     * @param type $idUsuario
     * @return type
     */
    public function GetVentas($idUsuario) {
        $datos = $this->_db->query(
                "SELECT * FROM ventas " .
                "WHERE id_usuario = '{$idUsuario}' ORDER BY fecha_fin DESC"
        );
        return $datos->fetchall(PDO::FETCH_ASSOC);
    }

    /**
     * Actualizamos la caducidad del Expired donde idUsuario ="a id del usuario logeado"
     * @param type $idUsuario
     * @param type $idCode
     */
    public function UpdateCaducidad($idUsuario, $idCode) {
        $this->_db->prepare(
                        "UPDATE ventas SET caducidad=:caducidad WHERE id_usuario=:idUsuario and id_code=:idCode"
                )
                ->execute(
                        array(
                            ":idUsuario" => $idUsuario,
                            ":caducidad" => 1,
                            ":idCode" => $idCode
                        )
        );
    }

    /**
     * Get HTML Y PHHP del panel download order
     */
    public function GetDownloadOrders($idUsuario = null) {
        ?>

        <div id="listadoCanciones" class="listadoOrder">
            <?php
            if (Session::get("level") == 1) {
                if ($idUsuario != null) {
                    $usuario = $idUsuario;
                } else {
                    $usuario = Session::get("usuario");
                }
            } else {
                $usuario = Session::get("usuario");
            }

            $id = $this->GetUsuarioId($usuario);
            $datos = $this->GetVentas($id['id']);
            $count = count($datos);
            if ($count == 0) {
                ?>
                <table cellpadding="0" cellspacing="0" border="0" class="display data_table">
                    <thead>
                        <tr>
                            <th colspan="3">There are no purchases</th>

                        </tr>
                    </thead>
                    <tbody id="fbody">
                        <tr><td colspan="3">There are no available purchases to be downloaded</td></tr>
                    </tbody>
                </table>
                <?php
            } else {
                ?>
                <table cellpadding="0" cellspacing="0" border="0" class="display data_table">
                    <thead>
                        <tr>
                            <th width="150px">Your purchases</th>
                            <th width="150px">Date</th>
                            <th>Total</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="fbody">
                        <?php
                        for ($i = 0; $i < count($datos); $i++) {
                            $contare = $count - $i;
                            ?><?php
                            if ($datos[$i]['estado'] == "PENDIENTE") {
                                $st = explode(" ", $datos[$i]['fecha_init']);
                                $fechas = $datos[$i]['fecha_init'];
                            } else {
                                $st = explode(" ", $datos[$i]['fecha_fin']);
                                $fechas = $datos[$i]['fecha_fin'];
                            }
                            $minutes_to_add = 30;
//                        $hora_to_add = 1;
                            $fecha = new DateTime($fechas);
                            $fecha->add(new DateInterval('P' . $minutes_to_add . 'D'));
////                        $fecha->add(new DateInterval('PT' . $horas_to_add . 'H' . $minutes_to_add . "S"));
                            $fecha_data = $fecha->format('Y-m-d H:i:s');
                            $FECHA = date('Y-m-d H:i:s');
                            ?> 
                            <?php
                            if (($fecha_data < $FECHA) and ( $datos[$i]['estado'] == "PAGADO")) {
                                $this->UpdateCaducidad(Session::get("id_usuario"), $datos[$i]['id_code']);
                            }
                            ?>
                            <tr>
                                <td>Nº <?= $count - $i; ?> </td>
                                <td>
                                    <?php echo $st[0]; ?>
                                </td>
                                <td><?= $datos[$i]['total'] . "€"; ?></td>
                                <td>
                                    <?php
                                    if ($datos[$i]['estado'] == "PENDIENTE") {
                                        ?>
                                        <button style="height: 52px; background: #7A7575 !important;width: 110px" data-code="<?= $datos[$i]['id_code']; ?>" data-order="<?= $contare; ?>" class="btn btn-info modal-download-order" data-toggle="modal" data-target="#order" data-whatever="@getbootstrap">View Order</button>
                                        <?php
                                    } else {
                                        if ($fecha_data < $FECHA) {
                                            ?>
                                            <button style="height: 52px; width: 110px;background: #FE6E00;" class="btn btn-danger modal-expired-view-order" data-order="<?= $contare; ?>" data-toggle="modal" data-target="#order" data-whatever="@getbootstrap" data-code="<?= $datos[$i]['id_code']; ?>">EXPIRED VIEW</button>

                                            <?php
                                        } else {
                                            ?>
                                            <button style="height: 52px;width: 110px" data-code="<?= $datos[$i]['id_code']; ?>" data-users='<?= $usuario; ?>' data-order="<?= $contare; ?>" class="btn btn-danger modal-download-order" data-toggle="modal" data-target="#order" data-whatever="@getbootstrap">DOWNLOAD</button>

                                            <?php
                                        }
                                        ?>


                                        <?php
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if (($datos[$i]['caducidad'] == 1) and ( $datos[$i]['estado'] == "PAGADO")) {
                                        ?>
                                        <button style="height: 52px;background: #7A7575 !important; width: 110px" class="btn btn-info" data-order="<?= $contare; ?>"  data-code="<?= $datos[$i]['id_code']; ?>" type="submit" name="submit">Paid</button>

                                        <?php
                                    } else {
                                        if ($datos[$i]['estado'] == "PENDIENTE") {
                                            ?>
                                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="">
                                                <input type="hidden" name="shipping" value="0">
                                                <input type="hidden" name="cbt" value="Presione aquí para volver a www.superbackings.com >>"> 
                                                <input type="hidden" name="cmd" value="_xclick">
                                                <input type="hidden" name="rm" value="2"> 
                                                <input type="hidden" name="bn" value="Superbackings">
                                                <input type="hidden" name="item_name" value="Your backing tracks order">
                                                <input type="hidden" name="item_number" value="<?= $datos[$i]['id_code']?>"> 
                                                <input type="hidden" name="business" value="paypal@superbackings.com">
                                                <input type="hidden" name="return" value="https://www.superbackings.com/success/"> 
                                                <input type="hidden" name="cancel_return" value="https://www.superbackings.com/success/error/">
                                                <input type="hidden" name="no_shipping" value="0"> 
                                                <input type="hidden" name="no_note" value="0">
                                                <input type="hidden" name="currency_code" value="EUR">
                                                <input type="hidden" name="amount" value="<?= $datos[$i]['total']; ?>">
                                                <!--<input style="width: 158px;" type="image" width="158px" src="<?= BASE_URL_IMG ?>checkout_active.png" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">-->
                                                <input type="submit" data-code="<?= $datos[$i]['id_code']; ?>" name="submit" style="width: 110px" value="BUY NOW" class="btn btn-danger btn-submit-update-ventas">
                                            </form>
                                            <?php
                                        } else {
                                            ?>
                                            <button style="height: 52px;background: #7A7575 !important; width: 110px" class="btn btn-info modal-download-order" data-toggle="modal" data-order="<?= $contare; ?>"  data-code="<?= $datos[$i]['id_code']; ?>" data-target="#order" data-whatever="@getbootstrap" type="submit" name="submit">Paid</button>


                                            <?php
                                        }
                                    }
                                    ?>


                                </td>
                                <td>

                                </td>
                            </tr>

                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>

        <?php
    }

    /**
     * Comprobamos que el tipo FV,WV,BASS,GT esten en el array y si existe te devuelve el nombre completo
     * @param type $type
     * @return string
     */
    public function GetTypeText($type) {
        $datos = array(
            "GT" => "no guitars",
            "WV" => "WB/vocals",
            "BASS" => "no bass",
            "FV" => "full version",
            "" => "full version",
        );
        if (array_key_exists($type, $datos)) {

            return $datos[$type];
        } else {

            return $type;
        }
    }

    /**
     * Get Pitch 1,2,3,4,-1,-2,-3,-4. Y lo devolvemos con '-' si es +1,+2,+3,+4 o '_' si es -1,-2,-3,-4
     * @param type $pitch
     * @return string
     */
    public function GetPitch($pitch) {
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
        return $type;
    }

    /**
     * Mostramos El modal con las canciones compradas y pendientes de la orden
     */
    public function GetModalOrders($code, $order, $idUser = null) {
        if (Session::get("level") == 1) {
            if ($idUser != null) {
                $usuario = $idUser;
            } else {
                $usuario = Session::get("usuario");
            }
        } else {
            $usuario = Session::get("usuario");
        }

        $rowDato = $this->GetUsuarioId($usuario);
        $datos = $this->_db->query(
                "SELECT * FROM ventas " .
                "WHERE id_usuario = '{$rowDato['id']}' and id_code='{$code}'"
        );
        $data = $datos->fetchall(PDO::FETCH_ASSOC);
        $tracks = $data[0]['arr_tracks'];
        $json = json_decode($tracks);
        ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Your song</th>
                    <th style="text-align: center;">Version</th>
                    <th style="text-align: center;">Pitch</th>
                    <th style="text-align: center;"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < count($json->cart); $i++) :
                    ?>
                    <?php
                    $da = $json->cart[$i];
                    $datas = $this->GetCheckoutDemo($da->MP3Demo);
                    ?>
                    <tr>
                        <td><?= $datas[0]['name'] . " - " . $datas[0]['artist_name'] . ""; ?></td>
                        <td style="text-align: center;"><?= ucfirst($this->GetTypeText($da->tipo)); ?></td>
                        <td style="text-align: center;"><?= $da->pitch; ?></td>
                        <td style="text-align: center;">
                            <?php if ($data[0]['estado'] == "PAGADO"): ?>
                                <?php
                                if ($da->pitch > 0) {
                                    $pitch = $this->GetPitch($da->pitch);
                                    $ex = explode(".mp3", $da->MP3Demo);
                                    $ruta = "modules/usuarios/ftp/$usuario/order_$order/$ex[0]$pitch.mp3";
                                    $mp3 = "$ex[0]$pitch.mp3";
                                } else {
                                    $ruta = "modules/usuarios/ftp/$usuario/order_$order/$da->MP3Demo";
                                    $mp3 = $da->MP3Demo;
                                }
                                if (Functions::checkFiles($ruta)) {
//                                     data-name="<?= $datas[0]['name'] . " - " . $datas[0]['artist_name'] . " - " . $da->tipo . " - $da->pitch";"
                                    ?>
                                    <span>
                                        <form method="POST" target="_BLANCK" class="download-agent" action="<?= BASE_URL . "download/" ?>">
                                            <input type="hidden" name="order" value="<?= "order_" . $order; ?>">
                                            <input type="hidden" name="download" value="<?= $mp3 ?>">
                                            <input type="hidden" name="user" value="<?= $usuario ?>">
                                            <input type="hidden" name="name" value="<?= $datas[0]['name']; ?>">
                                            <input type="hidden" name="artist_name" value="<?= $datas[0]['artist_name']; ?>">
                                            <input type="hidden" name="tipo" value="<?= $da->tipo; ?>">
                                            <input type="hidden" name="pitch" value="<?= "pitch$da->pitch" . ".mp3"; ?>">
                                            <input type="submit" value="Download">
                                        </form>
                                    </span>
                                    <?php
                                } else {
                                    ?>
                                    The file doesn't exist
                                    Click here to send a report
                                    <?php
                                }
                                ?>
                            <?php else: ?>
                                Pending
                            <?php endif; ?>
                        </td>
                    </tr>

                    <?php
                    for ($x = 0; $x < count($json->cart[$i]->version); $x++):
                        ?>
                        <?php
                        $version = $json->cart[$i]->version[$x];
                        $datas = $this->GetCheckoutDemo($version->cancion);
                        $ruta = "modules/usuarios/ftp/$usuario/order_$order/$version->cancion";
                        if (!empty($version)) {
                            ?>
                            <tr>
                                <td><?= $datas[0]['name'] . "(" . $datas[0]['artist_name'] . ")"; ?></td>
                                <td style="text-align: center;"><?= ucfirst($this->GetTypeText($version->version)); ?></td>
                                <td style="text-align: center;"><?= $version->pitch; ?></td>
                                <td style="text-align: center;">
                                    <?php if ($data[0]['estado'] == "PAGADO"): ?>
                                        <?php
                                        if (Functions::checkFiles($ruta)) {
                                            if ($version->pitch > 0) {
                                                $pitch = $this->GetPitch($version->pitch);
                                                $ex2 = explode(".mp3", $version->cancion);
                                                $ruta = "modules/usuarios/ftp/$usuario/order_$order/$ex2[0]$pitch.mp3";
                                                $mp3 = "$ex2[0]$pitch.mp3";
                                            } else {
                                                $ruta = "modules/usuarios/ftp/$usuario/order_$order/$version->cancion";
                                                $mp3 = $version->cancion;
                                            }
                                            ?>
                                            <span>
                                                <form method="POST" target="_BLANCK" class="download-agent" action="<?= BASE_URL . "download/" ?>">
                            <!--                                            <a target="_BLANCK" class="download-agent" data-order="<?= "$order" ?>" data-download="<?= $mp3 ?>">Download</a>-->
                                                    <input type="hidden" name="order" value="<?= "order_" . $order; ?>">
                                                    <input type="hidden" name="download" value="<?= $mp3 ?>">
                                                    <input type="hidden" name="user" value="<?= $usuario ?>">
                                                    <input type="hidden" name="name" value="<?= $datas[0]['name']; ?>">
                                                    <input type="hidden" name="artist_name" value="<?= $datas[0]['artist_name']; ?>">
                                                    <input type="hidden" name="version" value="<?= $version->tipo; ?>">
                                                    <input type="hidden" name="pitch" value="<?= "pitch$version->pitch" . ".mp3"; ?>">
                                                    <input type="submit" value="Download">
                                                </form>
                                            </span>
                                            <?php
                                        } else {
                                            ?>
                                            The file doesn't exist
                                            Click here to send a report
                                            <?php
                                        }
                                        ?>
                                    <?php else: ?>
                                        Pending
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>



                        <?php
                    endfor;
                endfor;
                ?>

            </tbody>
        </table>

        <?php
    }

    /**
     * Mostamos los datos expirados
     * @param type $code
     */
    public function GetModalOrdersExpired($code) {
        $datos = $this->_db->query(
                "SELECT * FROM ventas " .
                "WHERE id_usuario = '{$_SESSION['id_usuario']}' and id_code='{$code}'"
        );
        $data = $datos->fetchall(PDO::FETCH_ASSOC);
        $tracks = $data[0]['arr_tracks'];
        $json = json_decode($tracks);
        ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Your song</th>
                    <th style="text-align: center;">Version</th>
                    <th style="text-align: center;">Pitch</th>
                    <th style="text-align: center;"></th>
                </tr>
            </thead>
            <tbody>
                <?php $contar = 0 ?>
                <?php
                for ($i = 0; $i < count($json->cart); $i++) :
                    ?>
                    <?php
                    $da = $json->cart[$i];
                    $datas = $this->GetCheckoutDemo($da->MP3Demo);
                    ?>
                    <tr>
                        <td><?= $datas[0]['name'] . " - " . $datas[0]['artist_name'] . ""; ?></td>
                        <td style="text-align: center;"><?= ucfirst($this->GetTypeText($da->tipo)); ?></td>
                        <td style="text-align: center;"><?= $da->pitch; ?></td>
                        <td style="text-align: center;">
                            <?= "0.99€"; ?>
                        </td>
                    </tr>

                    <?php
                    for ($x = 0; $x < count($json->cart[$i]->version); $x++):
                        ?>
                        <?php
                        $version = $json->cart[$i]->version[$x];
                        $datas = $this->GetCheckoutDemo($version->cancion);
                        if (!empty($version)) {
                            ?>
                            <tr>
                                <td><?= $datas[0]['name'] . "(" . $datas[0]['artist_name'] . ")"; ?></td>
                                <td style="text-align: center;"><?= ucfirst($this->GetTypeText($version->version)); ?></td>
                                <td style="text-align: center;"><?= $version->pitch; ?></td>
                                <td style="text-align: center;">
                                    <?= "0.99€"; ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php
                        $contar++;
                    endfor;
                    $contar++;
                endfor;
                ?>
                <?php $total = number_format($contar * 0.99, 2); ?>
            </tbody>
        </table>
        <script>
            var total = '<?php echo $total; ?>';
            var total_data = '<?= $total . "€" ?>';
            var usuario = '<?php echo Session::get("usuario") ?>';
            var code = '<?php echo $code; ?>';
            var moneda = 'EUR';
            $("#footerModalAction").append(
                    '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="">' +
                    '   <input type="hidden" name="shipping" value="0">' +
                    '   <input type="hidden" name="cbt" value="Presione aquí para volver a www.superbackings.com >>">' +
                    '   <input type="hidden" name="cmd" value="_xclick">' +
                    '   <input type="hidden" name="rm" value="2"> ' +
                    '   <input type="hidden" name="bn" value="Superbackings">' +
                    '   <input type="hidden" name="item_name" value="Your backing tracks order">' +
                    '   <input type="hidden" name="item_number" value="' + usuario + '"> ' +
                    '   <input type="hidden" name="business" value="paypal@superbackings.com">' +
                    '   <input type="hidden" name="return" value="https://www.superbackings.com/success/">' +
                    '   <input type="hidden" name="cancel_return" value="https://www.superbackings.com/success/error/">' +
                    '   <input type="hidden" name="no_shipping" value="0"> ' +
                    '   <input type="hidden" name="no_note" value="0">' +
                    '   <input type="hidden" name="currency_code" value="' + moneda + '">' +
                    '   <input type="hidden" name="amount" value="' + total + '">' +
                    '   <input type="submit" data-code="' + code + '" data-precio="' + total + '" name="submit" style="width: 239px" value="Reactivate this order per ' + total_data + "" + '" class="btn btn-danger btn-submit-reactivate-ventas">' +
                    '</form>'
                    );
        </script>

        <?php
    }

    /**
     * Custom Orders Para la Vista de los archivos
     */
    public function GetCustomOrders() {
        $usuario = Session::get("usuario");
        $carpeta = "custom_songs";
        $ruta = "modules/usuarios/ftp/$usuario/$carpeta";
        $directorio = opendir($ruta); //ruta actual
        $archivos = array();

        while ($archivo = readdir($directorio)) { //obtenemos un archivo y luego otro sucesivamente
            ?>
            <?php
            if ($archivo == "." || $archivo == "..") {
                
            } else {
                if (is_dir($archivo)) {//verificamos si es o no un directorio
                    $archivos[] = $archivo;
                } else {
                    $archivos[] = $archivo;
                }
            }
            ?>
            <?php
        }
        closedir($directorio);
        if (count($archivos) == 0) {
            ?>
            <table class="table table-hover">
                <thead>
                    <tr style="background: #337ab7;color: white;">
                        <th style="text-align: center;color: white;" colspan="3">There are no custom songs to be downloaded</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3">If you want to get a custom backing track send an email doing <a href="mailto:superbackingtracks@gmail.com"><strong>click here</strong></a></td>
                    </tr>
                </tbody>
            </table>
            <?php
        } else {
            ?>

            <table class="table table-hover">
                <thead>
                    <tr style="background: #337ab7;color: white;">
                        <th style="text-align: center;color: white;">File</th>
                        <th style="text-align: center;color: white;">File name</th>
                        <th style="text-align: center;color: white;">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if (count($archivos) == 0) {
                        echo "<tr><td colspan=''>No hay custom songs en tu carpeta de usuario</td></tr>";
                    } else {
                        sort($archivos);
                        foreach ($archivos as $key => $fileName) {
                            ?>
                            <tr>
                                <td style="text-align: center;"><?= ++$key; ?></td>
                                <td style="text-align: center;"><?= $fileName; ?></td>
                                <td style="text-align: center;">
                                    <form method="POST" target="_BLANCK" class="download-agent" action="<?= BASE_URL . "download/" ?>">
                                        <input type="hidden" name="order" value="<?= $carpeta ?>">
                                        <input type="hidden" name="download" value="<?= $fileName ?>">
                                        <input type="hidden" name="name" value="<?= $fileName; ?>">
                                        <input style="height: 26px;padding: 0 15px !important;color: #337AB7;"type="submit" value="Download">
                                    </form>
                                </td>
                            </tr>

                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>

            <?php
        }
    }

}
