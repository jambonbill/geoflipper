<?php
header('Content-Type: text/html; charset=utf-8');

// Test file
// 
// http://geoflipper.fr/category/france/
// https://support.google.com/mapsengine/answer/3187059?hl=en
// http://stackoverflow.com/questions/5858827/importing-csv-file-to-google-maps
// 
// 
include "geoflipper.php";

echo "<pre>";
//echo "Saved\n";
$geoflip=new GeoFlipper();
$url='http://geoflipper.fr/category/france/ile-de-france/paris/';

echo "geoflip->getPage($url)\n";
$geoflip->getPage($url);

echo "geoflip->parsePage()\n";
$data=$geoflip->parsePage();

echo count($data) . "records\n";

echo "geoflip->getCsv()\n";
$csv=$geoflip->getCsv("\t");

echo "geoflip->saveCsv()\n";
$geoflip->saveCsv("/tmp/paris.csv");

die("ok");
