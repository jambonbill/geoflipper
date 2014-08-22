<?php

// geoflipper.php
// http://geoflipper.fr/category/france/
// https://support.google.com/mapsengine/answer/3187059?hl=en
// http://stackoverflow.com/questions/5858827/importing-csv-file-to-google-maps
// 
// 
class GeoFlipper
{
    private $url="http://geoflipper.fr/category/france/";
    private $html='';

    public function getHtml()
    {
        $dat=file($this->url);
        $this->html=implode('', $dat);
        return $this->html;
    }

    public function saveTmp($filename='')
    {
        // save as tmp
        $f=fopen($filename, "w");
        fwrite($this->html);
        fclose($f);
        return true;
    }

    public function parse()
    {

    }

    public function convert()
    {

    }

}




$CSV=[];
$i=0;


foreach ($dat as $line) {
    
    if ($i>10) {
        continue;
    }

    preg_match("/\bvar point = (.*);/", $line, $point);
    preg_match("/\bvar the_link = (.*);/", $line, $link);
    preg_match("/\bvar the_title = (.*);/", $line, $title);

    if (count($point) && preg_match("/([0-9\.-]+), ([0-9\.-]+)/", $point[1], $LATLNG)) {
        //print_r($point);
        
    }
    
    if (count($link)) {
        //print_r($link);
        $URL=str_replace("'", '', trim($link[1]));
        $URL=str_replace(";", '', $URL);
    }
    
    if (count($title)) {
        //print_r($title);
        $TITLE=str_replace("'", '', $title[1]);
        //exit;
        $ROW=[$LATLNG[1],$LATLNG[2],$URL,$TITLE];
        $CSV[]=$ROW;
        $i++;
        //print_r($ROW);
        //echo "<hr />";
    }
    

}


// Save as csv
$f=fopen("output.csv", "w+");
foreach ($CSV as $line) {
    print_r($CSV);
    fwrite($f, implode("\t", $line)."\n");
}
fclose($f);

die("done");

/*
var point = new google.maps.LatLng(48.87173989999999, 2.778131700000017);
var the_link = 'http://geoflipper.fr/discovery-arcade/';
var the_title = 'Discovery Arcade';
*/
