<?php

class FunctionsClass {

    public function __construct() {
        
    }

    public function GenerateSiteMap() {
//Sentencia que nos saca todos los artÃ­culos que tenemos en nuestra base de datos.
        $ssql = "select * from articulo";

//Creamos la cabecera del .xml
        $codigo = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $rs = mysql_query($ssql);
        $total = mysql_num_rows($rs);
        while ($fila = mysql_fetch_object($rs)) {
            $codigo .='<url>
    <loc>' . $fila->ruta;
            $codigo .='</loc>
   <lastmod>' . $fila->fecha . '</lastmod> 
   <changefreg>' . $fila->fecha . ' </changefreg>
   <priority>0.7</priority>
   </url> ';
        }
        $codigo .='</urlset> ';

//Ahora creamos el archivo con el cÃ³digo necesario
        $path = "/sitemap.xml";
        $modo = "w+";

        if ($fp = fopen($path, $modo)) {
            fwrite($fp, $codigo);
            echo "<p><b>Archivo sitemap creado correctamente</b>";
        } else {
            echo "<p><b>Ha habido un problema y el archivo no ha sido creado correctamente</b>";
        }
    }

}
