<?php

$pitch = $_GET['pitch'];
$name = $_GET['name'];
$fin = $_GET['name_pitch'];
switch ($pitch) {
    case "1": $pitchs = 100;
        break;
    case "2": $pitchs = 200;
        break;
    case "3": $pitchs = 300;
        break;
    case "4": $pitchs = 400;
        break;
    case "-1": $pitchs = -100;
        break;
    case "-2": $pitchs = -200;
        break;
    case "-3": $pitchs = -300;
        break;
    case "-4": $pitchs = -400;
        break;
}
//echo "pitch = $pitch | name=$name | fin = $fin"; 
/* Primera posicion, 1 Sentimos = 1 semitono,posicion final */
$fullpath = 'prueba/ . 1000.mp3';

if (!is_file($fullpath)) {
    System("sox 1000.mp3 125145.mp3", $pitchado);
    echo $pitchado;
} else {
    echo "No es un archivo";
}
//$fullpath = 'http://backingtracks.tv/demos/' . $fin;
//
//if ($pitch != 0) {
//    $headers = get_headers($fullpath, 1);
//    if (preg_match('/404/', $headers[0])) {
////        system("mkdir /home/ejercicios", $pitchado);
//        System("/usr/local/bin/sox /home/backtv/public_html/demos/$name.mp3 /home/backtv/public_html/demos/$fin --show-progress pitch $pitchs", $pitchado);
//
//        echo $pitchado;
//    } else {
//        echo "existe";
//    }
//} else {
//    echo "0";
//}
//system("/usr/local/bin/sox /home/backtv/public_html/demos/".$_REQUEST['t'].".mp3 /home/backtv/public_html/demos/".$id.".mp3 --show-progress pitch ".$pitch." >00out.txt 2>".$ruta." &",$pitchado);
//system("/usr/local/bin/sox /home/superb/public_html/pitch/125.mp3 /home/superb/public_html/pitch/output1.mp3 speed 3.0", $pitchado);//echo $pitchado;
