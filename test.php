<?php
#Suposicion solo hay dos equipos
$lineas = file('league_of_legends.csv');
$winner = '';
$selectedWinner = FALSE;
$failed = FALSE;
$kills = 0;
$deaths = 0;

$players = [];
foreach ($lineas as $num_linea => $linea) {
    if ($num_linea != 0) {
        $rowArray = (explode(",", $linea));
        #verifica que el archivo no traiga campos de mas por linea
        if (end($rowArray) == "\n" && strlen(end($rowArray)) == 1 && count($rowArray) == 11) {
            $failed = TRUE;
        } else {
            $players[$rowArray[1]] = 0;
            #verfica que los ganadores correspondan con el nombre
            if ($rowArray[3] == 'true') {
                if (!$selectedWinner) {
                    $winner = $rowArray[2];
                    $selectedWinner = TRUE;
                }
                if ($selectedWinner && $winner != $rowArray[2]) {
                    # code...
                    $failed = TRUE;
                }
                #verificacion de asesinatos del ganador
                $kills = $kills + (int)$rowArray[5];
                #si es ganador se le suma 10 puntos
                $players[$rowArray[1]] = (int)$players[$rowArray[1]] + 10;
            } else {
                #verificacion de muertes del perdedor
                $deaths = $deaths + (int)$rowArray[6];
            }
            switch ($rowArray[4]) {
                case "T":
                    $players[$rowArray[1]] = $players[$rowArray[1]] + (((int)$rowArray[5] + (int)$rowArray[7]) / (int)$rowArray[6]) + ((int)$rowArray[8] * 0.03 + (int)$rowArray[9] * 0.01);
                    break;
                case "B":
                    $players[$rowArray[1]] = $players[$rowArray[1]] + (((int)$rowArray[5] + (int)$rowArray[7]) / (int)$rowArray[6]) + ((int)$rowArray[8] * 0.03 + (int)$rowArray[9] * 0.01);
                    break;
                case "M":
                    $players[$rowArray[1]] = $players[$rowArray[1]] + (((int)$rowArray[5] + (int)$rowArray[7]) / (int)$rowArray[6]) + ((int)$rowArray[8] * 0.03 + (int)$rowArray[9] * 0.01);
                    break;
                case "J":
                    $players[$rowArray[1]] = $players[$rowArray[1]] + (((int)$rowArray[5] + (int)$rowArray[7]) / (int)$rowArray[6]) + ((int)$rowArray[8] * 0.02 + (int)$rowArray[9] * 0.02);
                    break;
                case "S":
                    $players[$rowArray[1]] = $players[$rowArray[1]] + (((int)$rowArray[5] + (int)$rowArray[7]) / (int)$rowArray[6]) + ((int)$rowArray[8] * 0.01 + (int)$rowArray[9] * 0.03);
                    break;
                default:
                    $failed = TRUE;
                    break;
            }
        }
    }
}
if ($deaths != $kills) {
    $failed = TRUE;
}

if ($failed) {
    echo "Error en el Archivo";
} else {
    #ordenamiento del array asociativo
    asort($players, 1);
    #obtengo el ultimo elemento
    end($players);
    #obtengo el nikname dle jugador
    $key = key($players);
    echo "El jugador con mayor puntaje en el juego es: {$key} con {$players[$key]} puntos";
}
