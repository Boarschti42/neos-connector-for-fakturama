<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class NeosFaktura_fakturamaXMLHelper{


  public static function ncff_getFirstCategory($catString){
    preg_match('~>\K[^<>]*(?=<)~', $catString, $match);
                  
                  if(array_key_exists(0, $match)){
                    return $match[0];
                  }
                  else{
                    return "";
                  }
  }
  
  public static function ncff_getImagePath($imgString){
    $document = new DOMDocument("1.0", "UTF-8");
    $xpath = new DOMXPath($document->loadHTML($imgString));
    return $xpath->evaluate("string(//img/@src)");
  }
  
  public static function ncff_calcTaxfromGross($gross, $net){
    if($gross > 0 && $net> 0)
    {
      return ((($gross / $net) - 1 ) * 100);
    }
    else{
      return 0;
    }
  }
  
  public static function ncff_getfakturaTime($time){
  		$timezone = get_option(NCFF_TEXTDOMAIN.'_timezone');
  		
  		if($timezone !== FALSE){
  			$date=date_create($time,timezone_open(get_option(NCFF_TEXTDOMAIN.'_timezone')));
  			
  		}
  		else{
  			$date=date_create($time,timezone_open('Europe/Berlin'));
  		}
        
        return date_format($date,"Y-m-d") . 'T' . date_format($date,"H:m:s");
    }
  
}