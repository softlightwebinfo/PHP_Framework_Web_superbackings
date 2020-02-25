<?php

class Canciones {

    private $bd;

    /**
     * Contructor a�ade BD a la clase
     * @param type $bd
     */
    function __construct() {

    }

    /**
     * Listado de todos los traks con o sin limite
     * @param string $limit
     * @return type
     */
    public function getListadoCanciones($limit = null) {
        if (is_numeric($limit)) {
            $limit = "LIMIT " . $limit;
        }
        $listado = $this->_db->query("SELECT * FROM tb_tracks $limit");
        return $listado->fetch_all();
    }

    /**
     * Listado de todos los artistas diferentes ordenado por artista ASC
     * @return type
     */
    public function getArtistas() {
        $listado = $this->bd->get_results("SELECT DISTINCT artist_name FROM tb_tracks ORDER BY artist_name ASC");
        return $listado;
    }

    /**
     * Get tipo predeterminado
     * @param type $type
     * @param type $img
     * @param type $param
     * @return string
     */
    public function getType($type, $img = false, $param = null) {
        $datos = array(
            "GT" => "gt.png",
            "WV" => "nv.png",
            "BASS" => "bass.png"
        );
        if (is_array($param)) {
            $params;
            foreach ($param as $key => $value) {
                $params .= $key . "='" . $value . "' ";
            }
        }
        if (array_key_exists($type, $datos)) {
            if ($img) {
                return "<img src='" . get_template_directory_uri() . "/images/" . $datos[$type] . "' $params>";
            } else {
                return $datos[$type];
            }
        } else {
            return;
        }
    }

    /*     * *******************************************************************************
     * **************ABECEDARIo********************************************************
     * ****************************************************************************** */

    /**
     * Select Artista, track y tipo LIKE
     * @param type $name
     * @return type
     */
    public function getArtistasAbcd($name) {
        $listado = $this->bd->get_results("SELECT artist_name,name,tipo FROM tb_tracks WHERE artist_name LIKE '$name%' and tipo='' ORDER BY artist_name ASC");
        return $listado;
    }

    public function abecedario() {
        echo "<table id='abecedario' border='0' bgcolor='#FFCC00'> 
                <tr>
                    <td>";
        for ($L2 = "A"; $L2 <= "Z"; $L2++) {
            echo "<a class=link2 href=#$L2>$L2</a>&nbsp;-&nbsp;";
            if ($L2 == "Z") {
                echo "";
                break;
            }
        }

        for ($N2 = 0; $N2 < 10; $N2++) {
            echo "<a class=link2 href=#$N2>$N2</a>";
            if ($N2 == 9) {
                echo "";
//exit;
            } else {
                echo "&nbsp;-&nbsp;";
            }
        }

        echo "</td>
			</tr>
		   </table>";
    }

    public function getLeyenda() {
        return "<img src=\"" . get_template_directory_uri() . "/images/nv.png\" width=40 height=27 align=absmiddle /> Without backing vocals <img src=\"" . get_template_directory_uri() . "/images/gt.png\" width=40 height=27 align=absmiddle /> Without Guitar <img src=\"" . get_template_directory_uri() . "/images/bass.png\" width=40 height=27 align=absmiddle /> Without Bass<br><br>";
    }

    public function getListAbecedario($abc = false) {

        for ($AZ = "A"; $AZ <= "Z"; $AZ++) {
//Abecedario horizontal y link
            $style = "";
            echo "<div>";
            $style = "position: absolute;padding: 4px;margin: -6px 0 0 11px;";

            echo "<font style='font-family: verdana;font-weigth: bold;font-size: 26px;$style border: 1px solid #36ACE7;'><a name=$AZ>" . $AZ . "</a></font>";
            $abc = $this->abecedario();

            echo "<hr noshade=\"noshade\"/>";

            echo "</div>";
            $list = $this->getArtistasAbcd($AZ);
            foreach ($list as $key => $post):
                ?>
                <li>
                    <a class="link1" href="<?php echo home_url('/'); ?>artist/?art=<?php echo $this->getURL($post->artist_name); ?>">
                        <?= "<b>" . $post->artist_name . "</b>"; ?>
                    </a>
                    <a class="link1" href="<?php echo home_url('/'); ?>backing-tracks-download/?art=<?php echo $this->getURL($post->name); ?>">
                        <?= "- " . $post->name; ?>
                    </a>
                </li>

            <?php endforeach; ?>
            <?php
            echo "<hr noshade=\"noshade\"/>";

            if ($AZ == "Z")
                break;
        }
        for ($CN = 0; $CN < 10; $CN++) {
//Abecedario horizontal y link
            $style = "";
            echo "<div>";
            if ($AZ != "A") {
                $style = "position: absolute;padding: 4px;margin: -6px 0 0 11px;";
            }
            echo "<font style='font-family: verdana;font-weigth: bold;font-size: 26px;$style border: 1px solid #36ACE7;'><a name=$CN>" . $CN . "</a></font>";
            $abc = $this->abecedario();
            echo "<hr noshade=\"noshade\"/>";

            echo "</div>";

            $list = $this->getArtistasAbcd($CN);
            foreach ($list as $key => $post):
                ?>
                <li><a class="link1" href=""><?= "<b>" . $post->artist_name . "</b> - " . $post->name; ?></a> <?php echo $this->getType($post->tipo, true, array("width" => "30px", "height" => "23px")); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
            <?php endforeach; ?>
            <?php
        }
    }

    public function getScrollAjax($start, $num, $WHERE = false, $ORDER = false) {
        if ($ORDER == false) {
            $ORDER = "artist_name ASC";
        } else {
            $ORDER = $ORDER;
        }
        $listado = $this->bd->query("SELECT * FROM tb_tracks $WHERE ORDER BY $ORDER LIMIT $start, $num");
        return $listado->fetchall();
    }

    public function getURL($str) {
//Quitar tildes y �
        $tildes = array('�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�');
        $vocales = array('a', 'e', 'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N');
        $str = str_replace($tildes, $vocales, $str);

//Quitar s�mbolos
        $simbolos = array("=", "�", "?", "�", "!", "'", "%", "$", "�", "(", ")", "[", "]", "{", "}", "*", "+", "�", ".", "&lt; ", "&gt;");
        $i = 0;
        while ($simbolos[$i]) {
            $str = str_replace($simbolos[$i], "", $str);
            $i++;
        }

//Quitar espacios
        $str = str_replace(" ", "_", $str);

//Pasar a min�sculas
        $str = strtolower($str);

        return $str;
    }

    /* PAGINACION */

    public function ConfigPaginacion($totalQuery, $tamPagina = 20, $getPage = 'page', $inicio = 0, $pagina = 1) {

        $countRegis = $wpdb->get_results($totalQuery);
        $num_registros = $wpdb->num_rows;
//Limito la busqueda
        $TAMANO_PAGINA = $tamPagina;
//examino la p�gina a mostrar y el inicio del registro a mostrar
        $pagina = $_GET[$getPage];
        if (!$pagina) {
            $inicio = $inicio;
            $pagina = $pagina;
        } else {
            $inicio = ($pagina - 1) * $TAMANO_PAGINA;
        }
        $resultado = $wpdb->get_results($totalQuery . ' LIMIT ' . $inicio . ', ' . $TAMANO_PAGINA . '');
//calculo el total de p�ginas

        $total_paginas = ceil($num_registros / $TAMANO_PAGINA);
        $num_regist_page = $wpdb->num_rows;
        return $num_regist_page;
    }

    public function GetTable($resultado) {
        echo "Ok";
    }

    public function Paginador($get, array $LIKE = null) {
        if ($LIKE == NULL) {
            $WHERE = 'WHERE artist_name LIKE "%' . $get . '%" OR name LIKE "%' . $get . '%"';
        } else {
            $WHERE = 'WHERE name=' . $LIKE['name'] . ' and artist_name=' . $LIKE['artist_name'] . ' and tipo=' . $LIKE['tipo'] . '';
        }
        $countRegis = $this->bd->get_results('SELECT * FROM tb_tracks ' . $WHERE . '');
        $num_registros = $this->bd->num_rows;
        //create new object pass in number of pages and identifier
        $pages = new Paginator('20', 'pag');

//pass number of records to
        $pages->set_total($num_registros);

        $data = $this->bd->get_results('SELECT * FROM tb_tracks ' . $WHERE . ' ORDER BY artist_name ASC ' . $pages->get_limit());
        if ($num_registros == 0) {
            ?>
            <tr>
                <td colspan="5"><b>UPS! IT SEAMS WE DON'T HAVE IT...</b> but if you want to have the song you're looking for we can make it from scratch, send us an email now  <a href='mailto:superbackingtracks@gmail.com'>Click</a>.</td>
            </tr>
            <?php
        } else {
            $this->GetTable($data);
        }
//create the page links
        $this->LinksPagination = $pages->page_links();
    }

    public function StylesPaginador() {
        ?>
        <style>
            /*                        ul.pagination {
                                        display: inline-block;
                                        padding: 0;
                                        margin: 0;
                                    }
            
                                    ul.pagination li {display: inline;}
            
                                    ul.pagination li a {
                                        color: black;
                                        float: left;
                                        padding: 8px 16px;
                                        text-decoration: none;
                                    }
                                    ul.pagination li a.active {
                                        background: -webkit-linear-gradient(top, #ff8400 0%, #ff6600 100%);
                                        color: white;
                                    }
            
                                    ul.pagination li a:hover:not(.active) {background-color: #278dbc;color:white;}
                                    ul.pagination li a {
                                        border-radius: 5px;
                                    }
            
                                    ul.pagination li a.active {
                                        border-radius: 5px;
                                    }
                                    ul.pagination li a {
                                        transition: background-color .3s;
                                    }
                                    ul.pagination li a {
                                        border: 1px solid #ddd;  Gray 
                                    }
                                    .pagination li:first-child a {
                                        border-top-left-radius: 5px;
                                        border-bottom-left-radius: 5px;
                                    }
            
                                    ul.pagination li:last-child a {
                                        border-top-right-radius: 5px;
                                        border-bottom-right-radius: 5px;
                                    }
                                    ul.pagination li a {
                                        margin: 0 4px;  0 is for top and bottom. Feel free to change it 
                                    }*/
            .pagination {
                clear: both;
                padding: 0;
            }
            .pagination li {
                display:inline;
            }
            .pagination a{
                border: 1px solid #D5D5D5;
                color: #FF7A00;
                font-size: 13px;
                font-weight: bold;
                height: 25px;
                padding: 4px 8px;
                text-decoration: none;
                margin: 2px;
            }
            .pagination a:hover, .pagination a:active {
                background: rgba(39, 141, 188, 0.56);
                color: white;
            }
            .pagination span.current {
                background-color: #FF7A00;
                border: 1px solid #D5D5D5;
                color: #ffffff;
                font-size: 15px;
                font-weight: bold;
                height: 25px;
                padding: 8px 12px;
                text-decoration: none;
                margin: 2px;
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

    /* HTML */

    public function HTML_Buscador() {
        ?>
        <div id="search">
            <label>Search: </label>
            <input type="search" class="form-controller" placeholder="Find that song you're looking for...">
            <div id="results_search"></div>
        </div>
        <?php
    }

}
?>
<?php
/*
 * PHP Pagination Class
 *
 * @author Rafa Gonzalez- rafael.gonzalez.1737@gmail.com - http://www.interactivesweb.com
 * @version 1.0
 * @date enero 30, 2016
 */

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

    /**
     *  __construct
     *  
     *  pass values when class is istantiated 
     *  
     * @param numeric  $_perPage  sets the number of iteems per page
     * @param numeric  $_instance sets the instance for the GET parameter
     */
    public function __construct($perPage, $instance) {
        $this->_instance = $instance;
        $this->_perPage = $perPage;
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
        $this->_page = (int) (!isset($_GET[$this->_instance]) ? 1 : $_GET[$this->_instance]);
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
    public function page_links($path = '?', $ext = null) {
        $adjacents = "2";
        $prev = $this->_page - 1;
        $next = $this->_page + 1;
        $lastpage = ceil($this->_totalRows / $this->_perPage);
        $lpm1 = $lastpage - 1;
        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= "<ul class='pagination'>";
            if ($this->_page > 1)
                $pagination.= "<li><a href='" . $path . "$this->_instance=$prev" . "$ext'>Previous</a></li>";
            else
                $pagination.= "<span class='disabled'>Previous</span>";
            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $this->_page)
                        $pagination.= "<li><span class='current'>$counter</span></li>";
                    else
                        $pagination.= "<li><a href='" . $path . "$this->_instance=$counter" . "$ext&query=" . $_GET['query'] . "'>$counter</a></li>";
                }
            }
            elseif ($lastpage > 5 + ($adjacents * 2)) {
                if ($this->_page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $this->_page)
                            $pagination.= "<li><span class='current'>$counter</span></li>";
                        else
                            $pagination.= "<li><a href='" . $path . "$this->_instance=$counter" . "$ext&query=" . $_GET['query'] . "'>$counter</a></li>";
                    }
                    $pagination.= "...";
                    $pagination.= "<li><a href='" . $path . "$this->_instance=$lpm1" . "$ext&query=" . $_GET['query'] . "'>$lpm1</a></li>";
                    $pagination.= "<li><a href='" . $path . "$this->_instance=$lastpage" . "$ext&query=" . $_GET['query'] . "'>$lastpage</a></li>";
                }
                elseif ($lastpage - ($adjacents * 2) > $this->_page && $this->_page > ($adjacents * 2)) {
                    $pagination.= "<li><a href='" . $path . "$this->_instance=1" . "$ext&query=" . $_GET['query'] . "'>1</a></li>";
                    $pagination.= "<li><a href='" . $path . "$this->_instance=2" . "$ext&query=" . $_GET['query'] . "'>2</a></li>";
                    $pagination.= "...";
                    for ($counter = $this->_page - $adjacents; $counter <= $this->_page + $adjacents; $counter++) {
                        if ($counter == $this->_page)
                            $pagination.= "<span class='current'>$counter</span>";
                        else
                            $pagination.= "<li><a href='" . $path . "$this->_instance=$counter" . "$ext&query=" . $_GET['query'] . "'>$counter</a></li>";
                    }
                    $pagination.= "..";
                    $pagination.= "<li><a href='" . $path . "$this->_instance=$lpm1" . "$ext&query=" . $_GET['query'] . "'>$lpm1</a></li>";
                    $pagination.= "<li><a href='" . $path . "$this->_instance=$lastpage" . "$ext&query=" . $_GET['query'] . "'>$lastpage</a></li>";
                }
                else {
                    $pagination.= "<li><a href='" . $path . "$this->_instance=1" . "$ext&query=" . $_GET['query'] . "'>1</a></li>";
                    $pagination.= "<li><a href='" . $path . "$this->_instance=2" . "$ext&query=" . $_GET['query'] . "'>2</a></li>";
                    $pagination.= "..";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $this->_page)
                            $pagination.= "<span class='current'>$counter</span>";
                        else
                            $pagination.= "<li><a href='" . $path . "$this->_instance=$counter" . "$ext&query=" . $_GET['query'] . "'>$counter</a></li>";
                    }
                }
            }
            if ($this->_page < $counter - 1)
                $pagination.= "<li><a href='" . $path . "$this->_instance=$next" . "$ext&query=" . $_GET['query'] . "'>Next</a></li>";
            else
                $pagination.= "<li><span class='disabled'>Next</span></li>";
            $pagination.= "</ul>\n";
        }
        return $pagination;
    }

}
