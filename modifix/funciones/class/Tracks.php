<?php

/**
 * Description of Tracks
 *
 * @author rafa
 */
class Tracks extends modifix {

    public function __construct() {
        
    }

    public function SET_DEMOS() {
//Analizo la carpeta demos en busca de archivos que no concuerden
//con el formato de id + extension (10.mp3)
//Para que esto funcione correctamente debe respetarse el criterio de texto del archivo.
//Debe ser por lo menos [NOMBRE TEMA (ARTISTA).mp3]
//El dato relevante en este script sera la busqueda del primer parentesis, si no lo tuviese no se ejecutaria el script
//y enviaria un aviso x mail para realizar la correccion del nombre del archivo.
        $dir = "../../../demos/";
//        $dir = "/home/backtv/public_html/demos/";
        $CONECTAME = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
        $BASE_SUPER = "superb_new";

        $OK = 0;
        $CARGADOS = 0;
        $MENSAJE = "";
// Abre un directorio conocido, y procede a leer el contenido
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
//Recorro la carpeta para verificar los archivos existentes
                while (($ARCHIVOS = readdir($dh)) !== false) {
//                    echo "nombre archivo: $ARCHIVOS : tipo archivo: " . filetype($dir . $ARCHIVOS) . "<br>";
//compruebo solo los archivos mp3 para verificar el formato correcto
                    $fichero = strtolower($ARCHIVOS);
//Extraigo el id y me quedo con el mp3
                    $EXTENSION = substr($fichero, -4);
                    if ($EXTENSION == ".mp3") {
//Compruebo si el archivo es un directorio
                        if ($fichero == "." || $fichero == "..") {
                            
                        } else {
//Primero divido para excluir la extension .mp3
                            $ARCHIVOS_SIN_EXTENSION = substr($fichero, 0, -4);
//Compruebo si es un numero y de serlo lo descarto.
                            if (!is_numeric($ARCHIVOS_SIN_EXTENSION)) {
//Comprubeo si es un tema pitcheado y de serlo lo descarto
                                if (!preg_match('/([-_])([1-4 1_4]).mp3/', $fichero)) {
//                                    echo "nombre archivo: $fichero<br>";
//Reviso si hay mas de 1 parentesis (
                                    if (substr_count($ARCHIVOS_SIN_EXTENSION, "\(") > 1 or substr_count($ARCHIVOS_SIN_EXTENSION, "\)") > 1) {
                                        $MENSAJE = "<span style=\"color:blue;\">El archivo <b>" . $fichero . "</b> tiene mas de un parentesis y no podra ser cargado!</span>";
                                        $OK++;
                                    } else {
//                                        echo "nombre archivo: $fichero<br>";
//Divido el archivo en busqueda del nombre del tema que esta delante de un espacio y un parentecis abierto
                                        $DIVIDO_TEMA_ARTISTA = explode(" (", $ARCHIVOS_SIN_EXTENSION);  //nombre tema (artista) tipo
                                        $TEMA = trim(ucwords(strtolower($DIVIDO_TEMA_ARTISTA[0]))); //Nombre Tema
//Busco el artista
                                        $DIVIDO_ARTISTA_RESTO = explode(") ", $DIVIDO_TEMA_ARTISTA[1]); //artista) tipo
                                        $ARTISTA = trim(ucwords(strtolower($DIVIDO_ARTISTA_RESTO[0]))); //Artista
//                                        echo "<pre>";
//                                        print_r($DIVIDO_TEMA_ARTISTA);
//                                        print_r($DIVIDO_ARTISTA_RESTO);
//                                        echo "</pre>";
//                                        echo $TEMA . "-" . $ARTISTA . "<br>";
//Busco si tiene playback para asignar sin coros
//$DIVIDO_ARTISTA_RESTO[1] es todo lo que esta despues del parentesis
                                        if (trim(strtolower($DIVIDO_ARTISTA_RESTO[1])) == "playback") { //playback
                                            $WB = "WV";
                                            $idPrecio = 31;
                                        } elseif (trim(strtolower($DIVIDO_ARTISTA_RESTO[1])) == "gt") {
                                            $WB = "GT";
                                            $idPrecio = 31;
                                        } elseif (trim(strtolower($DIVIDO_ARTISTA_RESTO[1])) == "bs") {
                                            $WB = "BS";
                                            $idPrecio = 39;
                                        } else {
                                            $WB = "";
                                            $idPrecio = 31;
                                        }
//Compruebo si ya esta cargado en la base de datos
                                        $sqlCM = $this->DB()->query("SELECT * FROM tb_tracks WHERE name='" . addslashes($TEMA) . "' AND artist_name='" . addslashes($ARTISTA) . "' AND tipo='" . $WB . "'");
                                        if ($sqlCM->rowCount() < 1) {
//                                            echo "No existe en la base : " . $TEMA . " " . $ARTISTA . " " . $WB . "<br>";
//Ingrsar track a la base
                                            $result = mysql_query("SHOW  TABLE STATUS  FROM superb_new where name='tb_tracks'", $CONECTAME);
                                            $row = mysql_fetch_array($result);
                                            $newID = $row['Auto_increment'];
                                            $newID .= ".mp3";

                                            $sql = $this->DB()->query("INSERT INTO tb_tracks  VALUES ('null','" . mysql_real_escape_string($TEMA) . "','0','" . mysql_real_escape_string($ARTISTA) . "','27','" . $idPrecio . "','" . $newID . "','0','" . $WB . "','SUPERB','','2.49','" . date('Y-m-d H:i:s') . "','')");
                                            if ($sql) {
//Modifico el nombre del demo por el id
                                                rename($dir . $ARCHIVOS, $dir . $newID);

                                                $MENSAJE .= "El archivo <b> " . $ARCHIVOS . "</b> ha sido ingresado a la base y renombrado a <b> " . $newID . "</b><br>";
                                                $OK++;
                                                $CARGADOS++;
                                            } else {
                                                $MENSAJE .= "<span style=\"color:violet;\">El archivo <b> " . $ARCHIVOS . "</b> no pudo ser ingresado a la base ni renombrado</span><br>";

                                                $OK++;
                                            }
                                        } else {
                                            $MENSAJE .= "<span style=\"color:red;\">El archivo <b> " . $ARCHIVOS . "</b> ya se encuentra en la base de datos, VERIFICAR </span><br>";
                                            $OK++;
                                        }
                                    }
                                }
                            } else {
                                
                            }
                        }
                    }
                }
            } else {
                echo "No se encontro directorio<br>";
            }
        } else {
            echo "No es un directorio<br>";
        }
        echo $MENSAJE;
        if ($CARGADOS <= 0) {
            echo "No hay ningun archivo nuevo en la carpeta demos<br><br>";
        }

        $cabeceras = "From: sales@superbackings.com <sales@superbackings.com>\r\nContent-type: text/html\r\n";


        if ($OK > 0) {

//            mail("rafael.gonzalez.1737@gmail.com,nicolasmateobarroso@gmail.com", "Carga Automatica de Demos", $MENSAJE, $cabeceras);
        }
        /* SUBIDA AUTOMATICA DE MASTER LIGADA CON LOS DEMOS */
//Analizo la carpeta BKTS en busca de archivos que no concuerden
//con el formato de id + extension (10.KZT)
//Para que esto funcione correctamente debe respetarse el criterio de texto del archivo.
//Debe ser por lo menos [NOMBRE TEMA (ARTISTA) playback.mp3]
//El dato relevante en este script sera la busqueda del primer parentesis, si no lo tuviese no se ejecutaria el script
//y enviaria un aviso x mail para realizar la correccion del nombre del archivo.
// variables
        echo "<hr>";
        $OK = 0;
        $CARGADOS = 0;
        $RENOMBRADAS = 0;
        $MENSAJES = "";
        $ftp = ftp_connect("67.23.247.176");
        if (!$ftp)
            die('could not connect.');

// login
        $r = ftp_login($ftp, "archkzt", "yhwh=777");
        if (!$r)
            die('could not login.');

// enter passive mode
        $r = ftp_pasv($ftp, true);
        if (!$r)
            die('could not enable passive mode.');

// get listing
        $r = ftp_nlist($ftp, "/KTZS/");
        $RUTA_CARPETA = "/KTZS/";
//        echo "<pre>";
//        print_r($r);
//        echo "</pre>";
        foreach ($r as $ARCHIVO) {
            //compruebo solo los archivos mp3 para verificar el formato correcto
            $fichero = strtolower($ARCHIVO);
            //Extraigo el id y me quedo con el KZt
            $EXTENSION = substr($fichero, -4);
            if ($ARCHIVO == "." || $ARCHIVO == "..") {
                
            } else {

                if ($EXTENSION == ".mp3") {
                    //echo $extension;
                    //Primero divido para excluir la extension .kzt
                    $ARCHIVO_SIN_EXTENSION = substr($ARCHIVO, 0, -4);
                    //Compruebo si es un numero y de serlo lo descarto.
                    if (!is_numeric($ARCHIVO_SIN_EXTENSION)) {
                        //echo $ARCHIVOS. " No es un archivo numerico";
                        //Divido el archivo en busqueda del nombre del tema que esta delante de un espacio y un parentecis abierto
                        $DIVIDO_TEMA_ARTISTA = explode(" (", $ARCHIVO_SIN_EXTENSION);
                        $TEMA = trim(ucwords(strtolower($DIVIDO_TEMA_ARTISTA[0])));
                        //Busco el artista
                        $DIVIDO_ARTISTA_RESTO = explode(")", $DIVIDO_TEMA_ARTISTA[1]);
                        $ARTISTA = trim(ucwords(strtolower($DIVIDO_ARTISTA_RESTO[0])));
                        //Busco si tiene playback para buscar la coincidencia sin coros
                        //$DIVIDO_ARTISTA_RESTO[1] es todo lo que esta despues del parentesis
                        if (stristr($DIVIDO_ARTISTA_RESTO[1], "Playback")) {
                            $WB = "WV";
                        } elseif (stristr($DIVIDO_ARTISTA_RESTO[1], "gt")) {
                            $WB = "GT";
                        } elseif (stristr($DIVIDO_ARTISTA_RESTO[1], "bs")) {
                            $WB = "BS";
                        } else {
                            $WB = "";
                        }
                        $sql = mysql_query("SELECT * FROM $BASE_SUPER.tb_tracks WHERE name='" . addslashes($TEMA) . "' AND artist_name='" . addslashes($ARTISTA) . "' AND tipo='" . $WB . "'");
                        $fil = mysql_num_rows($sql);
                        if ($fil < 1) {
                            $MENSAJES .= "<span style=\"color:red;\">El archivo <b> " . $ARCHIVO . "</b> no se ha encontrado en la base de datos, VERIFICAR LOS NOMBRES O FALTA WB o GT EN LA BASE</span><br>";

                            $OK++;
                        } else {
                            $d = mysql_fetch_assoc($sql);
                            $newID = $d['id'];
                            $newID .= ".KZT";
                            //Modifico el nombre del demo por el id
                            ftp_rename($ftp, $RUTA_CARPETA . $ARCHIVO, $RUTA_CARPETA . $newID);
                            if ($RENOMBRADAS < 10)
                                $NUM = "0" . ($RENOMBRADAS + 1);
                            else
                                $NUM = $RENOMBRADAS;
//
                            $MENSAJES .= $NUM . " -  El archivo <b> " . $ARCHIVO . "</b> ha sido renombrado a <b> " . $newID . "</b><br>";
                            $UPDATE = $this->DB()->query("UPDATE tb_tracks SET master=1 WHERE id='{$d['id']}'");

                            $OK++;
                            $RENOMBRADAS++;
                            $CARGADOS++;
                        }
                        echo $MENSAJES;
                    }
                }
            }
        }
        $cabeceras = "From: sales@superbackings.com <sales@superbackings.com>\r\nContent-type: text/html\r\n";

        if ($OK > 0) {
            if ($RENOMBRADAS > 0)
                $Msj = "- (" . $RENOMBRADAS . ") Procesadas!";
            else
                $Msj = "";
            mail("rafael.gonzalez.1737@gmail.com,nicolasmateobarroso@gmail.com", "Renombrado Automatico de Pistas", $MENSAJE . "<hr>" . $MENSAJES, $cabeceras);
            //echo $MENSAJE;
        }
    }

    public function GetTracks() {
        $sentencia = $this->DB()->prepare("SELECT id FROM tb_tracks");
        if ($sentencia->execute()) {
            return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function Paginador($get, $pages = "search/query/", $class, $LIKE = null) {

        if ($get == null) {

            $WHERE = "";
        } else {

            if ($LIKE == NULL) {

                $WHERE = 'WHERE (artist_name LIKE "%' . $get . '%" OR name LIKE "%' . $get . '%") and tipo=""';
            } else {

                $WHERE = 'WHERE name=' . $LIKE['name'] . ' and artist_name=' . $LIKE['artist_name'] . ' and tipo=' . $LIKE['tipo'] . '';
            }
        }



        $resultado = $this->DB()->query('SELECT * FROM tb_tracks ' . $WHERE);



        $countRegis = $resultado->fetchall(PDO::FETCH_ASSOC);

        $num_registros = count($countRegis);

//create new object pass in number of pages and identifier

        $pages = new Paginator('20', 'page', $pages);



//pass number of records to

        $pages->set_total($num_registros);



        $data = $this->DB()->query('SELECT * FROM tb_tracks ' . $WHERE . ' ORDER BY id ASC ' . $pages->get_limit());

        $data = $data->fetchall(PDO::FETCH_ASSOC);


        $this->StylesPaginador();
        if ($num_registros == 0) {
            echo "<div style='width: 60%;margin: auto;'><span style='color:red'>UPS! IT SEAMS WE DON'T HAVE THAT SONG… but if you want to have it, and you can’t find it over there, we can make it from scratch, send us an email now  <div style='width: 94px;margin: 22px auto;'><a class='btn btn-info' href='mailto:superbackingtracks@gmail.com'>Click here</a></span></div></div>";
            ?>
            <?php
        } else {
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display data_table" width="100%">
                <thead>
                    <tr>
                        <th width="55px">ID track</th>
                        <th>Name</th>
                        <th>Artista</th>
                        <th>Tipo</th>
                        <th>Demo</th>
                        <th>Descripcion</th>
                        <th>Precio</th>
                        <th>Fecha alta</th>
                        <th>Master</th>
                    </tr>
                </thead>
                <tbody id="fbody">
                    <?php
                    foreach ($data as $key => $valor) {
                        ?>
                        <tr data-tracks_id="<?= $valor['id']; ?>">
                            <td><?= $valor['id']; ?></td>
                            <td data-name="name"><span class= "<?= $class; ?>"><?= $valor['name']; ?></span></td>
                            <td data-name="artist_name"><span class= "<?= $class; ?>"><?= $valor['artist_name']; ?></span></td>
                            <td data-name="tipo"><span class= "<?= $class; ?>"><?= $valor['tipo']; ?></span></td>
                            <td data-name="demo"><span class= "<?= $class; ?>"><?= $valor['demo']; ?></span></td>
                            <td data-name="descripcion"><span class= "<?= $class; ?>"><?= $valor['descripcion']; ?></span></td>
                            <td data-name="precio"><span class= "<?= $class; ?>"><?= $valor['precio']; ?></span>€</td>
                            <td><?= $valor['fecha_alta']; ?></td>
                            <td data-name="master"><span class= "<?= $class; ?>"><?= $valor['master']; ?></span></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th width="55px">ID track</th>
                        <th>Name</th>
                        <th>Artista</th>
                        <th>Tipo</th>
                        <th>Demo</th>
                        <th>Descripcion</th>
                        <th>Precio</th>
                        <th>Fecha alta</th>
                        <th>Master</th>
                    </tr>
                </tfoot>
            </table>
            <?php
        }

//create the page links

        $this->LinksPagination = $pages->page_links('/', null, $get);
    }

    public function StylesPaginador() {
        ?>

        <style>

            .pagination a:hover, .pagination a:active {

                background: rgba(39, 141, 188, 0.56);

                color: white;

            }

            .pagination span.current {

                /*                background-color: #FF7A00;*/
                background: #3C8DBC !important;
                color: #ffffff !important;
            }

            .pagination span.disabled {

                border: 1px solid #EEEEEE;

                color: #DDDDDD;

                margin: 2px;

                padding: 2px 5px;

            }

        </style>

        <?php
    }

}

class Paginator {

    /**

     * set the number of items per page.

     *

     * @var numeric

     */
    private $_perPage;

    /**

     * set get parameter for fetching the page number

     *

     * @var string

     */
    private $_instance;

    /**

     * sets the page number.

     *

     * @var numeric

     */
    private $_page;

    /**

     * set the limit for the data source

     *

     * @var string

     */
    private $_limit;

    /**

     * set the total number of records/items.

     *

     * @var numeric

     */
    private $_totalRows = 0;
    private $_pages = "";

    /**

     *  __construct

     *  

     *  pass values when class is istantiated 

     *  

     * @param numeric  $_perPage  sets the number of iteems per page

     * @param numeric  $_instance sets the instance for the GET parameter

     */
    public function __construct($perPage, $instance, $pages) {

        $this->_instance = $instance;

        $this->_perPage = $perPage;

        $this->_pages = $pages;

        $this->set_instance();
    }

    /**

     * get_start

     *

     * creates the starting point for limiting the dataset

     * @return numeric

     */
    public function get_start() {

        return ($this->_page * $this->_perPage) - $this->_perPage;
    }

    /**

     * set_instance

     * 

     * sets the instance parameter, if numeric value is 0 then set to 1

     *

     * @var numeric

     */
    private function set_instance() {

        $this->_page = @$_GET['page'];

//        $this->_page = (int) (!isset($_GET[$this->_instance]) ? 1 : $_GET[$this->_instance]);

        $this->_page = ($this->_page == 0 ? 1 : $this->_page);
    }

    /**

     * set_total

     *

     * collect a numberic value and assigns it to the totalRows

     *

     * @var numeric

     */
    public function set_total($_totalRows) {

        $this->_totalRows = $_totalRows;
    }

    /**

     * get_limit

     *

     * returns the limit for the data source, calling the get_start method and passing in the number of items perp page

     * 

     * @return string

     */
    public function get_limit() {

        return "LIMIT " . $this->get_start() . ",$this->_perPage";
    }

    /**

     * page_links

     *

     * create the html links for navigating through the dataset

     * 

     * @var sting $path optionally set the path for the link

     * @var sting $ext optionally pass in extra parameters to the GET

     * @return string returns the html menu

     */
    public function page_links($path = '?', $ext = null, $data = null) {

        if ($data == "") {

            $data = null;
        } else {

            $data = $data . "/";
        }

        if ($path == "/") {

            $path = BASE_URL . $this->_pages . $data;
        }

        $adjacents = "2";

        $prev = $this->_page - 1;

        $next = $this->_page + 1;

        $lastpage = ceil($this->_totalRows / $this->_perPage);

        $lpm1 = $lastpage - 1;

        $pagination = "";

        if ($lastpage > 1) {

            $pagination .= "<ul class='pagination'>";

            if ($this->_page > 1)
                $pagination.= "<li><a href='" . $path . "$prev" . "$ext/'>Previous</a></li>";
            else
                $pagination.= "<li><a><span class='disabled'>Previous</span></a></li>";

            if ($lastpage < 7 + ($adjacents * 2)) {

                for ($counter = 1; $counter <= $lastpage; $counter++) {

                    if ($counter == $this->_page)
                        $pagination.= "<li class='current disabled'><a href=''><span>$counter</span></a></li>";
                    else
                        $pagination.= "<li><a href='" . $path . "$counter" . "$ext/'>$counter</a></li>";
                }
            }

            elseif ($lastpage > 5 + ($adjacents * 2)) {

                if ($this->_page < 1 + ($adjacents * 2)) {

                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {

                        if ($counter == $this->_page)
                            $pagination.= "<li class='current disabled'><a><span>$counter</span></a></li>";
                        else
                            $pagination.= "<li><a href='" . $path . "$counter" . "$ext/'>$counter</a></li>";
                    }

                    $pagination.= "<li><a>...</a></li>";

                    $pagination.= "<li><a href='" . $path . "$lpm1" . "$ext/'>$lpm1</a></li>";

                    $pagination.= "<li><a href='" . $path . "$lastpage" . "$ext/'>$lastpage</a></li>";
                }

                elseif ($lastpage - ($adjacents * 2) > $this->_page && $this->_page > ($adjacents * 2)) {

                    $pagination.= "<li><a href='" . $path . "1" . "$ext/'>1</a></li>";

                    $pagination.= "<li><a href='" . $path . "2" . "$ext/'>2</a></li>";

                    $pagination.= "<li><a>...</a></li>";

                    for ($counter = $this->_page - $adjacents; $counter <= $this->_page + $adjacents; $counter++) {

                        if ($counter == $this->_page)
                            $pagination.= "<li class='current disabled'><a><span>$counter</span></a></li>";
                        else
                            $pagination.= "<li><a href='" . $path . "$counter" . "$ext/'>$counter</a></li>";
                    }

                    $pagination.= "<li><a>...</a></li>";

                    $pagination.= "<li><a href='" . $path . "$lpm1" . "$ext/'>$lpm1</a></li>";

                    $pagination.= "<li><a href='" . $path . "$lastpage" . "$ext/'>$lastpage</a></li>";
                }

                else {

                    $pagination.= "<li><a href='" . $path . "1" . "$ext/'>1</a></li>";

                    $pagination.= "<li><a href='" . $path . "2" . "$ext/'>2</a></li>";

                    $pagination.= "<li><a>...</a></li>";

                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {

                        if ($counter == $this->_page)
                            $pagination.= "<li class='rent disabled'><a><span >$counter</span></a></li>";
                        else
                            $pagination.= "<li><a href='" . $path . "$counter" . "$ext/'>$counter</a></li>";
                    }
                }
            }

            if ($this->_page < $counter - 1)
                $pagination.= "<li><a href='" . $path . "$next" . "$ext/'>Next</a></li>";
            else
                $pagination.= "<li class='disabled'><a><span>Next</span></a></li>";

            $pagination.= "</ul>\n";
        }

        return $pagination;
    }

}
