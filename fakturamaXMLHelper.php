<?php


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class NeosFaktura_fakturamaXMLHelper{
  
  public static function getFirstCategory($catString){
    preg_match('~>\K[^<>]*(?=<)~', $catString, $match);
                  
                  if(array_key_exists(0, $match)){
                    return $match[0];
                  }
                  else{
                    return "";
                  }
  }
  
  public static function getImagePath($imgString){
    $xpath = new DOMXPath(@DOMDocument::loadHTML($imgString));
    return $xpath->evaluate("string(//img/@src)");
  }
  
  public static function calcTaxfromGross($gross, $net){
    if($gross > 0 && $net> 0)
    {
      return ((($gross / $net) - 1 ) * 100);
    }
    else{
      return 0;
    }
  }
  
  public static function getfakturaTime($time){
  		$timezone = get_option('NeosFaktura_timezone');
  		
  		if($timezone == False){
  			$date=date_create($time,timezone_open(get_option('NeosFaktura_timezone')));
  			
  		}
  		else{
  			$date=date_create($time,timezone_open('Europe/Berlin'));
  		}
        
        return date_format($date,"Y-m-d H:m:s");
    }
  
}