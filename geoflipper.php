<?php
// geoflipper.php
// http://geoflipper.fr/category/france/
// https://support.google.com/mapsengine/answer/3187059?hl=en
// http://stackoverflow.com/questions/5858827/importing-csv-file-to-google-maps

class GeoFlipper
{
    private $url="http://geoflipper.fr/category/france/";
    private $html='';
    private $csv='';
    private $geoData=[];

    /**
     * Get/Set Url
     * @param  string $url [description]
     * @return [type]      [description]
     */
    public function url($url='')
    {
        if ($url) {
            $this->url=$url;
        }
        return $this->url; 
    }

    /**
     * [getHtml description]
     * @return [type] [description]
     */
    public function getPage($url='')
    {
        if ($url) {
            $this->url=$url;
        }

        $dat=file($this->url);
        $this->html=implode('', $dat);
        return $this->html;
    }


    /**
     * [saveTmp description]
     * @param  string $filename [description]
     * @return [type]           [description]
     */
    public function saveTmp($filename='')
    {
        if(!$this->html()){
            throw new Exception("Get some html first", 1);
        }

        // save as tmp
        $f=fopen($filename, "w");
        fwrite($this->html);
        fclose($f);
        return true;
    }


    /**
     * Read the map data, and get the location, url and name
     * @return [type] [description]
     */
    public function parsePage()
    {

        /*
        var point = new google.maps.LatLng(48.87173989999999, 2.778131700000017);
        var the_link = 'http://geoflipper.fr/discovery-arcade/';
        var the_title = 'Discovery Arcade';
        */
        $this->geoData=[];
        
        $dat=explode("\n",$this->html);
        
        foreach ($dat as $line) {
    
            preg_match("/\bvar point = (.*);/", $line, $point);
            preg_match("/\bvar the_link = (.*);/", $line, $link);
            preg_match("/\bvar the_title = (.*);/", $line, $title);

            if (count($point)) {
                //print_r($point);
                preg_match("/([0-9\.-]+), ([0-9\.-]+)/", $point[1], $LATLNG);
            }
        
            if (count($link)) {
                //print_r($link);
                $URL=str_replace("'", '', trim($link[1]));
                $URL=str_replace(";", '', $URL);
            }
    
            if (count($title)) {
                //print_r($title);
                $TITLE=str_replace("'", '', $title[1]);
                $ROW=[$LATLNG[1],$LATLNG[2],$URL,$TITLE];
                $this->geoData[]=$ROW;
                //print_r($ROW);
                
            }    
        }
        return $this->geoData;
    }   

    /**
     * [convert description]
     * @return [type] [description]
     */
    public function getCsv($separator=',')
    {
        $CSV=[];
        $CSV[]=implode($separator,['Latitude','Longitude','Name','Url']);
        foreach ($this->geoData as $dat) {
            $CSV[]=implode($separator,$dat);
        }
        $this->csv=implode("\n",$CSV);
    }

    /**
     * Save csv file
     * @param  string $filename [description]
     * @return [type]           [description]
     */
    public function saveCsv($filename = '')
    {
        if(!$this->csv){
            throw new Exception("No csv data", 1);
        }

        $f=fopen($filename, "w+");
        fwrite($f, $this->csv);
        fclose($f);
        return true;
    }
}
