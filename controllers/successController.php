<?php

class successController extends Controller {

    private $_model;
    private $_lib;

    public function __construct() {
        parent::__construct();
        $this->_view->contentfull = "width: calc(100% - 20px);box-sizing: border-box;";
        $this->_model = $this->loadModel("success");

        $this->_view->paypal = $this->_model;
        $this->_view->setJsPlugin(array('min/Funciones.min'));
    }

    public function index() {
//        if (!Session::get("autenticado")) {
//            $this->redireccionar("error/access/5050/");
//        }
        $this->_view->titulo = "Success!";
        $this->_view->titulo_page = "Success! | Super Backing Tracks";
// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory. 
// Especially useful if you encounter network errors or other intermittent problems with IPN (validation). 
// Set this to 0 once you go live or don't require logging. 
        define("DEBUG", 1);

// Set to 0 once you're ready to go live 
        define("USE_SANDBOX", 0);


        define("LOG_FILE", "./ipn.log");


// Read POST data 
// reading posted data directly from $_POST causes serialization 
// issues with array data in POST. Reading raw POST data from input stream instead. 
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
// read the post from PayPal system and add 'cmd' 
        $req = 'cmd=_notify-validate';
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

// Post IPN data back to PayPal to validate the IPN data is genuine 
// Without this step anyone can fake IPN data 

        if (USE_SANDBOX == true) {
            $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        } else {
            $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
        }

        $ch = curl_init($paypal_url);
        if ($ch == FALSE) {
            return FALSE;
        }

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

        if (DEBUG == true) {
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        }

// CONFIG: Optional proxy configuration 
//curl_setopt($ch, CURLOPT_PROXY, $proxy); 
//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1); 
// Set TCP timeout to 30 seconds 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path 
// of the certificate as shown below. Ensure the file is readable by the webserver. 
// This is mandatory for some environments. 
//$cert = __DIR__ . "./cacert.pem"; 
//curl_setopt($ch, CURLOPT_CAINFO, $cert); 

        $res = curl_exec($ch);
        if (curl_errno($ch) != 0) { // cURL error 
            if (DEBUG == true) {
                error_log(date('[Y-m-d H:i e] ') . "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
            }
            curl_close($ch);
            exit;
        } else {
// Log the entire HTTP response if debug is switched on. 
            if (DEBUG == true) {
                error_log(date('[Y-m-d H:i e] ') . "HTTP request of validation request:" . curl_getinfo($ch, CURLINFO_HEADER_OUT) . " for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
                error_log(date('[Y-m-d H:i e] ') . "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
            }
            curl_close($ch);
        }

// Inspect IPN validation result and act accordingly 
// Split response headers and payload, a better way for strcmp 
        $tokens = explode("\r\n\r\n", trim($res));
        $res = trim(end($tokens));

        if (strcmp($res, "VERIFIED") == 0) {
// check whether the payment_status is Completed 
// check that txn_id has not been previously processed 
// check that receiver_email is your PayPal email 
// check that payment_amount/payment_currency are correct 
// process payment and mark item as paid. 
// assign posted variables to local variables 
//$item_name = $_POST['item_name']; 
//$item_number = $_POST['item_number']; 
//$payment_status = $_POST['payment_status']; 
//$payment_amount = $_POST['mc_gross']; 
//$payment_currency = $_POST['mc_currency']; 
//$txn_id = $_POST['txn_id']; 
//$receiver_email = $_POST['receiver_email']; 
//$payer_email = $_POST['payer_email']; 
            $db = new mysqli("localhost", "superb", "servidores=x10", "superb_new");
            if ($_POST['item_name'] == "Backing Tracks download") {
                $datos = $_POST;
                $user = $db->query("SELECT * FROM ventas,usuarios WHERE (ventas.id_code='{$_POST['item_number']}') and (ventas.id_usuario=usuarios.id)");
                $fetch = $user->fetch_assoc();
                $db->query("UPDATE ventas SET estado='PAGADO', payer_id='{$_POST['payer_id']}', payment_date='{$_POST['payment_date']}', payer_email='{$_POST['payer_email']}', payer_status='{$_POST['payer_status']}', payment_status='{$_POST['payment_status']}',caducidad=0,totalReactivate='0' WHERE id_code='{$_POST['item_number']}'");

                $to = $fetch['email'];

                $subject = 'You order from superbackings.com';

// Cabecera que especifica que es un HMTL
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Cabeceras adicionales
//            $headers .= "From: \"" . "Superbackings" . "\" <" . "superbackingtracks@gmail.com" . ">\n";
                $headers .= "From: sales@superbackings.com" . "\r\n" . "Sender:sales@superbackings.com";
//            $cabeceras .= 'Cc: archivotarifas@example.com' . "\r\n";
//            $cabeceras .= 'Bcc: copiaoculta@example.com' . "\r\n";

                $cuerpo = '
        <html> 
            <head> 
               <title>' . $subject . '</title>
            </head> 
            <body> 
            Has pagado en www.superbacking.com

            </body>
        </html>
            ';


                mail($to, $subject, $cuerpo, $headers);
                $this->_view->renderizar("index", 'success/');
                exit();
            } else if ($_POST['item_name'] == "Your backing tracks order") {
                $datos = $_POST;
                $user = $db->query("SELECT * FROM ventas,usuarios WHERE (ventas.id_code='{$_POST['item_number']}') and (ventas.id_usuario=usuarios.id)");
                $fetch = $user->fetch_assoc();
                $datee = date("Y-m-d H:i:s");
                $db->query("UPDATE ventas SET fecha_init='{$datee}',fecha_fin='{$datee}', estado='PAGADO', payer_id='{$_POST['payer_id']}', payment_date='{$_POST['payment_date']}', payer_email='{$_POST['payer_email']}', payer_status='{$_POST['payer_status']}', payment_status='{$_POST['payment_status']}',caducidad=0,totalReactivate='1' WHERE id_code='{$_POST['item_number']}'");

                $to = $fetch['email'];

                $subject = 'You order from superbackings.com';

// Cabecera que especifica que es un HMTL
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Cabeceras adicionales
//            $headers .= "From: \"" . "Superbackings" . "\" <" . "superbackingtracks@gmail.com" . ">\n";
                $headers .= "From: sales@superbackings.com" . "\r\n" . "Sender:sales@superbackings.com";
//            $cabeceras .= 'Cc: archivotarifas@example.com' . "\r\n";
//            $cabeceras .= 'Bcc: copiaoculta@example.com' . "\r\n";

                $cuerpo = '
        <html> 
            <head> 
               <title>' . $subject . '</title>
            </head> 
            <body> 
            Has pagado en www.superbacking.com

            </body>
        </html>
            ';


                mail($to, $subject, $cuerpo, $headers);
                $this->_view->renderizar("index", 'success/');
                exit();
            } else if ($_POST['item_name'] == "backingtracksdownload") {

                function detectSistema() {
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

//                foreach ($_POST as $key => $value) {
//                    error_log("$key | $value" . PHP_EOL, 3, "backingtracksdownloadt.cop");
//                }
                $db = new mysqli("localhost", "superb", "servidores=x10", "superb_new");
                $info = detectSistema();
                $sistema = array(
                    'sistema' => $info["os"],
                    'navegador' => $info["browser"],
                    'version' => $info["version"]
                );
                $sistema = json_encode($sistema);

                $id_cookie = $_POST['item_number'];
                $estado = "PAGADO";
                $porcdescuento = "0";
                $caducidad = "0";
                $descuento = "0";
                $total = "80";
                $browser = $sistema;


                $item_name = $_POST['item_name'];
                $payment_status = $_POST['payment_status'];
                $payment_amount = $_POST['mc_gross'];
                $payment_currency = $_POST['mc_currency'];
                $txn_id = $_POST['txn_id'];
                $receiver_email = $_POST['receiver_email'];
                $payer_email = $_POST['payer_email'];
                $payer_id = $_POST['payer_id'];
                $payer_status = $_POST['payer_status'];
                $payment_date = $_POST['payment_date'];

                $carrito = $db->query("SELECT * FROM carrito WHERE id_cookie='{$id_cookie}'");
                $datos = $carrito->fetch_object();
//                    $id_session = $value->id_session;
                $id_carrito = $datos->id_carrito;
                $id_session = $datos->id_session;
                $cart = $datos->cart;
                $fecha_start = $datos->fecha_start;
                $fecha_ultima = $datos->fecha_ultima;
                $fecha = new DateTime($fecha_ultima);
                $intervalo = new DateInterval('P1M');
                $fecha->add($intervalo);
                $fecha_ultima = $fecha->format('Y-m-d H:i:s') . "\n";

                $codigoPromocional = $datos->codigoPromocional;
                $promo = $db->query("SELECT * FROM codigoPromocional WHERE id_promocional='{$codigoPromocional}'");
                $p = $promo->fetch_object();
                $porcentaje = $p->porciento;

                $db->query("INSERT INTO compras(id_usuario,fecha_init,arr_tracks,estado,caducidad,porcdescuento,descuento,fecha_fin,total,browser,payer_id,payment_date,payer_email,payer_status,payment_status,id_code) "
                        . "VALUES('{$id_session}','{$fecha_start}','{$cart}','{$estado}','{$caducidad}','{$porcentaje}','{$codigoPromocional}','{$fecha_ultima}','{$total}','{$browser}','{$payer_id}','{$payment_date}','{$payer_email}','{$payer_status}','{$payment_status}','{$id_cookie}')");

                $db->query("DELETE FROM carrito WHERE id_carrito='{$id_carrito}'");

                $sql_user = $db->query("SELECT * FROM usuarios2 WHERE id='{$id_session}'");
                $users = $sql_user->fetch_object();
                if ($users->email_second != "") {
                    $email = $users->email_second;
                } else {
                    $email = $users->email;
                }
                $idUser = $users->id;
                /*                 * ************* Generación de los demos ************** */


                $carpeta = $id_session . "/" . $id_cookie;

                $post = array(
                    "cart" => $cart,
                    "carpeta" => $carpeta,
                );
// close the connection
                $ch = curl_init('http://www.backingtracksdownload.com/API/CURL/prepare_order_files.php');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

// execute!
                $response = curl_exec($ch);

// close the connection, release resources used
                curl_close($ch);
// do anything you want with your response
//                var_dump($response);

                /*                 * ************* Aqui va el envio del email al cliente************** */
                ob_start();
                ?>
                <div style="margin:0;padding:0;background-color:#ffffff" class=""><div class="adM">    </div><div style="background-color:#ffffff"><div class="adM">
                        </div><table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" style="margin:0px;padding:0px;border:0px;width:100%!important">
                            <tbody>
                                <tr>
                                    <td align="center" valign="top" style="background-color:#ffffff;width:100%">


                                        <table border="0" cellpadding="0" cellspacing="0" width="600" style="margin:0;padding:0;width:600px!important;background-color:#ffffff"> 
                                            <tbody>
                                                <tr>
                                                    <td align="left">

                                                        <table border="0" cellpadding="0" cellspacing="0" width="600" style="margin:0;padding:0;width:600px!important"> 
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <table width="100%" cellspacing="0" cellpadding="10" border="0" style="background-color:#ffffff">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td valign="top" style="padding:10px 20px;font-family:Arial,Helvetica,sans-serif">
                                                                                        <div style="text-align:center"><img border="0" style="display:block;border:0px;float:none;margin:0px auto;width:100%;min-height:113px" src="https://ci5.googleusercontent.com/proxy/GM181E06oKU7obIaM3BiXnhDRINKON022Tv9Mugt_ekgRKd1SajtNXMKCaLwPVS4lgCSH7lbRyeaY-KYlhN2uLPJVG2r9l1Okv9uwiL1BIapBKFUAv3fTyU7TXXsDpFmoA=s0-d-e1-ft#http://x25420.track.list-manage7.net/web_public_shared/image/25420/logo.png" height="113" width="560" title=" " alt=" " class="CToWUd"></div><div><hr style="border-width:1px 0px;border-top-style:solid;border-bottom-style:solid;border-top-color:#dddddd;border-bottom-color:#dddddd;margin:10px"></div><div style="min-height:100px">
                                                                                            <p style="padding:0px;margin:0.3em 0px 0.8em;line-height:normal;font-family:Arial,Helvetica,sans-serif">
                                                                                                <span>
                                                                                                    <font face="Arial, Helvetica, sans-serif">
                                                                                                    <b>Hi 
                                                                                                        <a href="mailto:<?= $email; ?>" target="_blank">
                                                                                                            <span><?= $email; ?></span>
                                                                                                        </a> 
                                                                                                    </b>Thanks for you payment!&nbsp;
                                                                                                    </font>
                                                                                                </span>
                                                                                            </p>
                                                                                            <p style="padding:0px;margin:0.3em 0px 0.8em;line-height:normal;font-family:Arial,Helvetica,sans-serif">
                                                                                                <span>
                                                                                                    <font face="Arial, Helvetica, sans-serif">
                                                                                                    You can download your content via email doing click on the download button, or you can log in to 
                                                                                                    <u>
                                                                                                        <a href="http://www.backingtracksdownload.com/your-client-area/">your customer panel</a>
                                                                                                    </u> 
                                                                                                    to download the files that way.&nbsp;
                                                                                                    </font>
                                                                                                </span>
                                                                                            </p>
                                                                                            <p style="line-height:normal;padding:0px;margin:0.3em 0px 0.8em;font-family:Arial,Helvetica,sans-serif">
                                                                                                <b style="line-height:normal">
                                                                                                    <font face="Arial, Helvetica, sans-serif">
                                                                                                    <br>
                                                                                                    </font>
                                                                                                </b>
                                                                                            </p>
                                                                                            <?php
                                                                                            $carts = json_decode($cart);
                                                                                            $i = 0;
                                                                                            $precios = 0;
                                                                                            foreach ($carts as $key => $value) {
                                                                                                $precios += $value->precio;
                                                                                                $download = array(
                                                                                                    "id_carrito" => $id_carrito,
                                                                                                    "track" => $value->track,
                                                                                                    "carrito" => $carpeta
                                                                                                );
                                                                                                $download = json_encode($download);
                                                                                                $download = Hash::encrypt($download, HASH_KEY);
                                                                                                ?>
                                                                                                <p style="line-height:normal;padding:0px;margin:0.3em 0px 0.8em;font-family:Arial,Helvetica,sans-serif">
                                                                                                    <font face="Arial, Helvetica, sans-serif">
                                                                                                    <b style="line-height:normal"><?= $key + 1 ?>.&nbsp;</b>
                                                                                                    <span style="line-height:normal">
                                                                                                        <?= $value->name; ?> - <button> <a href="http://www.backingtracksdownload.com/download-link/<?= $download; ?>/">DOWNLOAD</a></button>
                                                                                                    </span>
                                                                                                    <br>
                                                                                                    </font>
                                                                                                </p>
                                                                                                <?php
                                                                                            }
                                                                                            ?>

                                                                                            <?php
                                                                                            $subtotal = number_format($precios, 2);
                                                                                            if ($codigoPromocional === "NULL" or $codigoPromocional == NULL) {
                                                                                                $total = $subtotal;
                                                                                                $porcentaje = 0;
                                                                                            } else {
                                                                                                $t = (100 - $porcentaje) / 100;
                                                                                                $total = $subtotal * $t;
                                                                                                $total = number_format($total, 2);
                                                                                            }
                                                                                            ?>
                                                                                            <p style="line-height:normal;padding:0px;margin:0.3em 0px 0.8em;font-family:Arial,Helvetica,sans-serif">
                                                                                                <span style="line-height:normal">
                                                                                                    <font face="Arial, Helvetica, sans-serif">
                                                                                                    <br>
                                                                                                    </font>
                                                                                                </span>
                                                                                            </p>
                                                                                            <p style="text-align:right;padding:0px;margin:0.3em 0px 0.8em;line-height:normal;font-family:Arial,Helvetica,sans-serif">
                                                                                                <span>
                                                                                                    <font face="Arial, Helvetica, sans-serif">Price : <?= $subtotal; ?>&euro;</font>
                                                                                                </span>
                                                                                            </p>
                                                                                            <p style="text-align:right;padding:0px;margin:0.3em 0px 0.8em;line-height:normal;font-family:Arial,Helvetica,sans-serif">
                                                                                                <span>
                                                                                                    <font face="Arial, Helvetica, sans-serif">Discount : <?= $porcentaje; ?>%</font>
                                                                                                </span>
                                                                                            </p>
                                                                                            <p style="line-height:normal;padding:0px;margin:0.3em 0px 0.8em;font-family:Arial,Helvetica,sans-serif">
                                                                                            </p>
                                                                                            <p style="text-align:right;padding:0px;margin:0.3em 0px 0.8em;line-height:normal;font-family:Arial,Helvetica,sans-serif">
                                                                                                <span>
                                                                                                    <b>
                                                                                                        <font face="Arial, Helvetica, sans-serif">Total : <?= $total; ?>&euro;</font>
                                                                                                    </b>
                                                                                                </span>
                                                                                            </p>
                                                                                            <p style="font-size:13px;font-family:Arial,Helvetica,sans-serif;line-height:normal;padding:0px;margin:0.3em 0px 0.8em"><span style="line-height:normal"><br></span></p>
                                                                                            <p style="font-size:13px;font-family:Arial,Helvetica,sans-serif;padding:0px;margin:0.3em 0px 0.8em;line-height:normal"></p>
                                                                                        </div>
                                                                                        <div>
                                                                                            <hr style="border-width:1px 0px;border-top-style:solid;border-bottom-style:solid;border-top-color:#dddddd;border-bottom-color:#dddddd;margin:10px">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <table width="100%" cellspacing="0" cellpadding="20" border="0" style="background-color:#ffffff">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td valign="top" width="100%" style="padding-top:10px;padding-bottom:10px;text-align:left;line-height:18px;font-size:14px"><div><p style="text-align:left;padding:0px;margin:0.3em 0px 0.8em;line-height:normal;font-family:'Times New Roman'"><font color="#3c5587"><span style="font-family:Arial,Helvetica,sans-serif">This link, which enables your download will expire 1 month from receiving your order. After this period you will need to buy again.</span><font face="Arial, Helvetica, sans-serif"><br></font></font></p><p style="font-size:14px;padding:0px;margin:0.3em 0px 0.8em;line-height:normal;font-family:'Times New Roman'">
                                                                                            </p>
                                                                                            <p style="font-size:14px;padding:0px;margin:0.3em 0px 0.8em;line-height:normal;font-family:'Times New Roman'"><span><font face="Arial, Helvetica, sans-serif"><font color="#3c5587">If you have any further question or just need help please send us an email to </font><a href="mailto:sales@backingtracksdownload.com" style="color:#333333" target="_blank"><span>sales@backingtracksdownload.<wbr>com</span></a></font></span></p></div></td></tr></tbody></table><table width="100%" cellspacing="0" cellpadding="20" border="0" style="background-color:#ffffff"><tbody><tr><td valign="top" width="100%" style="padding-top:10px;padding-bottom:10px;text-align:left;line-height:18px;font-size:14px"><div><hr style="border-width:1px 0px;border-top-style:solid;border-bottom-style:solid;border-top-color:#dddddd;border-bottom-color:#dddddd;margin:10px"></div></td></tr></tbody></table><table width="100%" cellspacing="0" cellpadding="20" border="0" style="background-color:#ffffff"><tbody><tr><td valign="top" width="50%" style="padding-top:10px;padding-bottom:10px;text-align:left;line-height:18px;font-size:14px"><div><p style="text-align:left;padding:0px;margin:0.3em 0px 0.8em;font-family:'Times New Roman';line-height:normal"><span style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-size:16px;font-weight:bold">Custom Backing Tracks</span></p><p style="text-align:left;padding:0px;margin:0.3em 0px 0.8em;font-family:'Times New Roman';line-height:normal"><span style="color:#888888;font-family:Arial,Helvetica,sans-serif;font-size:12px">If you want to have a custom track created you can send the original music to be listened as reference by answering this emails. We'll Quote.&nbsp;</span><br></p></div></td><td valign="top" width="50%" style="padding-top:10px;padding-bottom:10px;text-align:left;line-height:18px;font-size:14px"><div><p style="text-align:left;padding:0px;margin:0.3em 0px 0.8em;font-family:'Times New Roman';line-height:normal"><span style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:normal;font-weight:bold">Free backing Tracks</span><br><br><font color="#888888" face="Arial, Helvetica, sans-serif"><span style="font-size:12px">Everyday a free backing track! Visit our website for more&nbsp;information. Download the free track of today!&nbsp;</span></font><br></p></div></td></tr></tbody></table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table align="center" cellspacing="0" cellpadding="20" border="0" style="width:600px;max-width:600px;margin:0 auto">
                            <tbody>
                                <tr>
                                    <td align="left" style="padding:20px 0">
                                        <table align="left">
                                            <tbody>
                                                <tr>
                <!--                                                    <td style="padding:0;font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#333333">
                                                        <span style="color:#333333">
                                                            Agréganos a tu lista de contactos
                                                        </span> 
                                                    </td>-->
                                                </tr>
                                                <tr>
                <!--                                                    <td style="padding:0;font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#333333">
                                                        <a href="http://x25420.track.list-manage7.net/track/click?u=vcard&amp;p=32353432303a3133313a303a303a323a31&amp;s=ac151747ad906aa10411ea43457276c9&amp;m=50811" style="color:#333333" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=es&amp;q=http://x25420.track.list-manage7.net/track/click?u%3Dvcard%26p%3D32353432303a3133313a303a303a323a31%26s%3Dac151747ad906aa10411ea43457276c9%26m%3D50811&amp;source=gmail&amp;ust=1465040691603000&amp;usg=AFQjCNHa6sidbqPSMR7iE1uTp9gsTLOWiQ">
                                                            Información de Contacto
                                                        </a>
                                                    </td>-->
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td align="right" style="float:right;padding:20px 0">
                                        <table align="right">
                                            <tbody>
                                                <tr>
                <!--                                                    <td style="padding:0;font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#333333">
                                                        <span style="color:#333333">
                                                            Para desuscribirse de nuestra lista haga
                                                        </span> 
                                                        <a href="http://x25420.track.list-manage7.net/track/click?u=unsubscribe&amp;p=32353432303a3133313a303a303a333a31&amp;s=ac151747ad906aa10411ea43457276c9&amp;m=50811" style="color:#333333" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=es&amp;q=http://x25420.track.list-manage7.net/track/click?u%3Dunsubscribe%26p%3D32353432303a3133313a303a303a333a31%26s%3Dac151747ad906aa10411ea43457276c9%26m%3D50811&amp;source=gmail&amp;ust=1465040691603000&amp;usg=AFQjCNE81iYouxKodwGnWlQ5Y4TElQomzw">
                                                            Click Aquí
                                                        </a>
                                                    </td>-->
                                                </tr>
                                                <tr>
                <!--                                                    <td align="right" style="padding:0;font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#333333">
                                                        <a href="http://x25420.track.list-manage7.net/track/click?u=unsubscribe&amp;p=32353432303a3133313a303a303a343a31&amp;s=ac151747ad906aa10411ea43457276c9&amp;m=50811" style="color:#333333" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=es&amp;q=http://x25420.track.list-manage7.net/track/click?u%3Dunsubscribe%26p%3D32353432303a3133313a303a303a343a31%26s%3Dac151747ad906aa10411ea43457276c9%26m%3D50811&amp;source=gmail&amp;ust=1465040691603000&amp;usg=AFQjCNEO6Z86MuxVjCoQMiLJY6r2JBxNSQ">
                                                            <img border="0" align="right" src="https://ci5.googleusercontent.com/proxy/PQTlthOicgQlcjD6VYCFUcJKPDmYd0U-DYV_XNXimQ7D3PKYCL98tF2l4bQgEesEk0vzoYoX1GveqC7WKeQzotIBKddK4E3c6PGEjknx4pIYGHrdQg=s0-d-e1-ft#http://x25420.track.list-manage7.net/img/es/unsubscribe-img.png" width="136" height="13" style="width:136px;min-height:13px" class="CToWUd">
                                                        </a>
                                                    </td>-->
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <img src="https://ci6.googleusercontent.com/proxy/STmWHgt6Uf_Z1QSpWUGGbzx4pFKCVOBqbUPA2WNBMjISoekGyPa0yrVIyib8v16xx3fiC40JJl_uFHlLjt1VxJzAKhtf-GQykZsyYZmbAK_JEKozjr6mWZZd1_L87DEohATP7VCHeuwOMHMkoojAkbEcT67W6iwrvfpvZ822KhU-K8uPTbuabsat9ucLwxvdMIBCRqBlw8h2ZA=s0-d-e1-ft#https://reads.ferozo.com/demo.png?s=4c2e5d7ce2da5c3fe8744c1788a71ece&amp;AdministratorID=25420&amp;CampaignID=131&amp;Demo=1&amp;MemberID=50811&amp;v=6" width="10" height="10" alt="---" class="CToWUd"><div class="yj6qo"></div><div class="adL">  </div></div>


                <?php
                $contenido = ob_get_contents();
                ob_end_clean();

                $subject = 'You order from superbackings.com';

                // Cabecera que especifica que es un HMTL
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

                // Cabeceras adicionales
                //            $headers .= "From: \"" . "Superbackings" . "\" <" . "superbackingtracks@gmail.com" . ">\n";
                $headers .= "From: backingtracksdownload <sales@backingtracksdownload.com>" . "\r\n";
                //            $cabeceras .= 'Cc: archivotarifas@example.com' . "\r\n";
//                $headers .= 'Bcc: nicolasmateobarroso@gmail.com ' . "\r\n";
//                $headers .= 'Bcc: nicobackingtracks@gmail.com' . "\r\n";
//                $headers .= 'Bcc: rafael.gonzalez.1737@gmail.com' . "\r\n";

                mail($email, "Backing Tracks Download", $contenido, $headers);
                mail("rafael.gonzalez.1737@gmail.com,sales@backingtracksdownload.com", "Backing Tracks Download", $contenido, $headers);
            } else if ($_POST['item_name'] == "cristiaweb_sembrar") {
//                foreach ($_POST as $key => $value) {
//                    error_log("$key | $value" . PHP_EOL, 3, "backingtracksdownloadt.cop");
//                }
                $db = new mysqli("localhost", "cristianresource", "servidores=x10", "cristian_database");

                $id_cookie = $_POST['item_number'];

                $item_name = $_POST['item_name'];
                $payment_status = $_POST['payment_status'];
                $payment_amount = $_POST['mc_gross'];
                $payment_currency = $_POST['mc_currency'];
                $txn_id = $_POST['txn_id'];
                $receiver_email = $_POST['receiver_email'];
                $payer_email = $_POST['payer_email'];
                $payer_id = $_POST['payer_id'];
                $payer_status = $_POST['payer_status'];
                $payment_date = $_POST['payment_date'];
                $date = date("Y-m-d H:i:s");
                $db->query("INSERT INTO sembrar(id_usuario,cantidad,fecha,payment_status,payer_email,payer_status,payment_date) VALUES('{$id_cookie}','{$payment_amount}','{$date}','{$payment_status}','{$payer_email}','{$payer_status}','{$payment_date}')");
            }
            ?>

            <?php // $Pub->UpdataCompra($_POST);                          ?>
            <?php
            if (DEBUG == true) {
                error_log(date('[Y-m-d H:i e] ') . "Verified IPN: $req " . PHP_EOL, 3, LOG_FILE);
            }
        } else if (strcmp($res, "INVALID") == 0) {
// log for manual investigation 
// Add business logic here which deals with invalid IPN messages 
            if (DEBUG == true) {
                error_log(date('[Y-m-d H:i e] ') . "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
            }
        }
        $this->_view->renderizar("index", 'success/');
    }

    public function error() {
        if (!Session::get("autenticado")) {
            header("location: " . BASE_URL . "error/access/5050");
        }
        unset($_SESSION['carrito_paypal']);
        $this->_view->titulo = "Checkout paypal Error";
        $this->_view->titulo_page = "Checkout paypal Error | Super Backing Tracks";
        $this->_view->renderizar("error", 'success/');
    }

}
