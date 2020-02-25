<?php

class FullsonglistModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getCanciones($limit = null) {
        if (is_numeric($limit)) {
            $limit = "LIMIT " . $limit;
        }
        $listado = $this->_db->query("SELECT * FROM tb_tracks LIMIT 20");
        return $listado->fetchall();
    }

    public function functionName($param) {
        
    }

    public function GetTabla($resultado = null, $LIMIT = 20) {
        if ($resultado == null) {
            $resultado = $this->getCanciones($LIMIT);
        }
        foreach ($resultado as $key => $value):
            $datosCarrito = array("id" => $value['precio'], "precio" => $value['precio']);
            $datosCarrito = json_encode($datosCarrito);
            ?>
            <tr>
                <td><i onclick="play('<?php echo $value['demo']; ?>', this);" class="fa fa-play-circle fa-2x"></i></td>
                <td><a href="<?php echo $_layoutParams['root']; ?>backing-tracks-download/"><?= $value['name']; ?></a></td>
                <td><a href="<?php echo $_layoutParams['root']; ?>artist"><?= $value['artist_name']; ?></a></td>
                <td style="vertical-align: bottom;"><?= $this->getType($value['tipo, true']); ?></td>
                <td>
                    <a href="javascript:void(0);" class="buybtn" data-carrito='<?= $datosCarrito; ?>'>
                        <span class="buybtn-text">Add to cart</span> 
                        <!--<span class="buybtn-hidden-text">3.99 &euro;</span>-->
                        <span class="buybtn-image buybtn-image-text"><span>2.49&euro;</span></span>
                    </a>
                </td>
            </tr>
            <?php
        endforeach;
        ?>
        <?php
    }

    public function getArtistas() {
        $listado = $this->_db->query("SELECT DISTINCT artist_name FROM tb_tracks ORDER BY artist_name ASC");
        return $listado->fetchall();
    }

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
                return "<img src='" . BASE_URL_IMG . "" . $datos[$type] . "' $params>";
            } else {
                return $datos[$type];
            }
        } else {
            return;
        }
    }

}
?>
