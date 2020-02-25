<?php

/**
 * Description of Ventas
 *
 * @author rafa
 */
class Ventas extends modifix {

    private $_usuarios;

    public function __construct() {
        $this->_usuarios = new Users();
    }

    public function GetVentasFull() {
        $data = $this->DB()->query("SELECT id_ventas FROM ventas");
        $datos = $data->fetchAll(PDO::FETCH_ASSOC);
        return $datos;
    }

    public function Paginador($get, $pages = "search/query/", $class) {

        if ($get == null) {

            $WHERE = "";
        } else {

            if ($LIKE == NULL) {

                $WHERE = 'WHERE (artist_name LIKE "%' . $get . '%" OR name LIKE "%' . $get . '%") and tipo=""';
            } else {

                $WHERE = 'WHERE name=' . $LIKE['name'] . ' and artist_name=' . $LIKE['artist_name'] . ' and tipo=' . $LIKE['tipo'] . '';
            }
        }



        $resultado = $this->DB()->query('SELECT * FROM ventas ' . $WHERE);



        $countRegis = $resultado->fetchall(PDO::FETCH_ASSOC);

        $num_registros = count($countRegis);

//create new object pass in number of pages and identifier

        $pages = new Paginator('20', 'page', $pages);



//pass number of records to

        $pages->set_total($num_registros);



        $data = $this->DB()->query('SELECT * FROM ventas ' . $WHERE . ' ORDER BY fecha_fin DESC ' . $pages->get_limit());

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
                        <th width="55px">ID Venta</th>
                        <th>Usuario</th>
                        <th>Tracks</th>
                        <th>Estado</th>
                        <th>Caducidad</th>
                        <th>Total</th>
                        <th>Navegador</th>
                        <th>Fecha compra</th>
                        <th>Email paypal</th>
                        <th>Settings</th>
                    </tr>
                </thead>
                <tbody id="fbody">
                    <?php
                    foreach ($data as $key => $valor) {
                        $dtUser = $this->_usuarios->GetUser($valor['id_usuario']);
                        $browser = json_decode($valor['browser']);
                        if ($valor['estado'] == "PENDIENTE") {
                            $estado = '<span class= "' . $class . ' label label-danger">PENDIENTE</span>';
                            $activar = '<button class="btn btn-danger activar-orden" data-email="' . $dtUser['email'] . '" data-user="' . $dtUser['usuario'] . '">Activar orden</button>';
                        } else {
                            $estado = '<span class= "' . $class . ' label label-success">PAGADO</span>';
                            $activar = '<button style="width:103px" class="btn btn-success">Activado</button>';
                        }
                        if ($valor['caducidad'] == 0) {
                            $caducidad = '<span class= "' . $class . ' label label-success">Faltan x dias</span>';
                        } else {
                            $caducidad = '<span class= "' . $class . ' label label-danger">EXPIRED</span>';
                        }
                        if (empty($browser)) {
                            $browsers = "";
                        } else {
                            $browsers = $browser->sistema . " | " . $browser->navegador;
                        }
                        ?>
                        <tr style="height: 50px;" data-tracks_id="<?= $valor['id_ventas']; ?>">
                            <td><?= $valor['id_ventas']; ?></td>
                            <td data-name="name"><span class= "<?= $class; ?>"><?= $dtUser['usuario']; ?></span></td>
                            <td data-name="artist_name"><a class="btn btn-info viewTracks" data-idVentas='<?= $valor['id_ventas']; ?>' data-tracks='<?= $valor['arr_tracks']; ?>' data-toggle="modal" data-target="#viewstracks" data-whatever="@getbootstrap">View Traks</a></td>
                            <td data-name="tipo"><?= $estado; ?></td>
                            <td data-name="demo"><?= $caducidad; ?></td>
                            <td data-name="precio"><span class= "<?= $class; ?>"><?= $valor['total']; ?></span>€</td>
                            <td><?= $browsers; ?></td>
                            <td data-name="master"><span class= "<?= $class; ?>"><?= $valor['fecha_fin']; ?></span></td>
                            <td data-name="master"><span class= "<?= $class; ?>"><?= $valor['payer_email']; ?></span></td>
                            <td><?= $activar ?></td>
                        </tr>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th width="55px">ID Venta</th>
                        <th>Usuario</th>
                        <th>Tracks</th>
                        <th>Estado</th>
                        <th>Caducidad</th>
                        <th>Total</th>
                        <th>Navegador</th>
                        <th>Fecha compra</th>
                        <th>Email paypal</th>
                        <th>Settings</th>
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
