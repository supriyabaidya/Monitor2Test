<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$array = json_decode($_POST['matrix']);

$coverageR = fopen("coverageR.csv", "w");
$text = '';

foreach ($array as $row) {
    foreach ($row as $data) {
        $text = $text . $data . ',';
    }

    fputcsv($coverageR, explode(',', $text));
    $text = '';
}

fclose($coverageR);
echo json_encode(array(
    'html' => 'coverageR.csv file is successfully generated',
));
