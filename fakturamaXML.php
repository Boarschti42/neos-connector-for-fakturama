<?php

/**
 * Created by IntelliJ IDEA.
 * User: xoxoxo
 * Date: 07.06.16
 * Time: 21:37
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );




class NeosFaktura_fakturamaXML
{

    private $woo;
    private $xmlhead;
    private $xmlfooter;
    private $debug;


    public function __construct($debug = False)
    {
      if ( ! defined('ABSPATH') ) {
            return;
      }
      
      $this->debug = $debug;

      $dom = new DOMDocument('1.0', 'UTF-8');

      $webshopex = $dom->createElement('webshopexport');
      $webshopexattr = $dom->createAttribute('version');
      $webshopexattr->value = "1.6";
      
      $webshopex->appendChild($webshopexattr);
      $dom->appendChild($webshopex);
      
      $phpversion = $dom->createElement('phpversion', phpversion());
      $webshopex->appendChild($phpversion);
      
      $webshop = $dom->createElement('webshop');
      $webshopattrShop = $dom->createAttribute('shop');
      $webshopattrShop->value = "WooCommerce";
      
      $webshopattrUrl = $dom->createAttribute('url');
      $webshopattrUrl->value = get_bloginfo('url');  //fakturama expected localhost. 
      
      $webshop->appendChild($webshopattrShop);
      $webshop->appendChild($webshopattrUrl);
      $webshopex->appendChild($webshop);
      
      $this->dom = $webshopex;
      $this->xml = $dom;
      
     
    }

    private function prepareforXML($string){
       return html_entity_decode(strip_tags($string));
    }


  
    private function get_product_list()
    {   $full_product_list = array();

        $types = array('product');

        //$types[] = 'product_variation';



        $products = get_posts( array(
            'post_type'   => $types,
            'posts_per_page' => -1
        ) );
        #$wc_query = new WP_Query($product);


        foreach ($products as $product){


                $full_product_list[$product->ID] = new WC_Product($product->ID);


        }


        sort($full_product_list);


        return $full_product_list;
    }
  
 
    public function get_products (){

        $productsXML = $this->xml->createElement("products");
        $image = $this->xml->createAttribute("imagepath");
        $image->value = get_bloginfo('url');
        $productsXML->appendChild($image);

        $products = $this->get_product_list();


        $this->debug($products);




        foreach ($products as $pid => $product){


            //$image = $this->filePath($product['featured_src']);


            $productXML = $this->xml->createElement("product");
            //attributes
            $gross = $this->xml->createAttribute("gross");
            $gross->value = $product->get_regular_price();
            $vatpercent = $this->xml->createAttribute("vatpercent");
            $vatpercent->value = WC_Tax::round(array_shift(WC_Tax::get_rates($product->get_tax_class()))['rate']).".00";
            $quantity = $this->xml->createAttribute("quantity");
            $quantity->value = $product->get_stock_quantity();
            $id = $this->xml->createAttribute("id");
            $id->value = $product->get_sku();



            $productXML->appendChild($gross);
            $productXML->appendChild($vatpercent);
            $productXML->appendChild($quantity);
            $productXML->appendChild($id);



            $productXML->appendChild($this->xml->createElement("model", $this->prepareforXML($product->get_sku())));
            $productXML->appendChild($this->xml->createElement("ean"));
            $productXML->appendChild($this->xml->createElement("name", $this->prepareforXML($product->get_title())));
            $productXML->appendChild($this->xml->createElement("category", $this->prepareforXML(get_term( array_shift($product->get_category_ids()) , 'product_cat' )->name)));
            $productXML->appendChild($this->xml->createElement("qunit"));
            $productXML->appendChild($this->xml->createElement("vatname", array_shift(WC_Tax::get_rates($product->get_tax_class()))['label']." ".$this->prepareforXML(WC_Tax::round(array_shift(WC_Tax::get_rates($product->get_tax_class()))['rate'])."%")));
            $productXML->appendChild($this->xml->createElement("short_description", $this->prepareforXML( $product->get_short_description())));
            //$productXML->appendChild($this->xml->createElement("image", $this->prepareforXML($image['subfolder']."/".$image['basename'])));


            $productsXML->appendChild($productXML);


        }

        $this->dom->appendChild($productsXML);


       if($this->debug == False)
        {
            return  $this->xml->saveXML();
        }
       else{
            return "";
       }
    }

    public function get_orders (){

        $customer_orders = get_posts( array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        
        'post_type'   => wc_get_order_types(),
        'post_status' => array_keys( wc_get_order_statuses() ),
        ) );


        $ordersXML = $this->xml->createElement("orders");

        foreach ($customer_orders as $corder) {


            $order = new WC_Order($corder->ID);

            $this->debug($order);
            


            $create_at = NeosFaktura_fakturamaXMLHelper::getfakturaTime($order->order_date) ;

            #  $productXML->appendChild($this->xml->createElement("", )); 
            $orderXML = $this->xml->createElement("order");
            //attributes
            $id = $this->xml->createAttribute("id");
            $id->value = $this->prepareforXML( $corder->ID);
            $date = $this->xml->createAttribute("date");
            $date->value = $this->prepareforXML( $create_at);
            $currency = $this->xml->createAttribute("currency");
            $currency->value = $this->prepareforXML( $order->get_order_currency());
            $currency_value = $this->xml->createAttribute("currency_value");
            $currency_value->value = "1.000000";
            $state = $this->xml->createAttribute("status");
            $state->value = $this->prepareforXML( $order->get_status());
            
            $orderXML->appendChild($id);
            $orderXML->appendChild($date);
            $orderXML->appendChild($currency);
            $orderXML->appendChild($currency_value);
            $orderXML->appendChild($state);
            

            $contact = $this->xml->createElement("contact");
            $contactid = $this->xml->createAttribute("id");
            $contactid->value = $this->prepareforXML( $order->customer_user);
          
            $contact->appendChild($contactid);
          

            $contact->appendChild($this->xml->createElement("gender"));
            $contact->appendChild($this->xml->createElement("firstname", $this->prepareforXML( $order->billing_first_name)));
            $contact->appendChild($this->xml->createElement("lastname", $this->prepareforXML( $order->billing_last_name)));
            $contact->appendChild($this->xml->createElement("company", $this->prepareforXML( $order->billing_company)));
            $contact->appendChild($this->xml->createElement("street", $this->prepareforXML( $order->billing_address_1)));
            $contact->appendChild($this->xml->createElement("zip", $this->prepareforXML( $order->billing_postcode)));
            $contact->appendChild($this->xml->createElement("city", $this->prepareforXML( $order->billing_city)));
            $contact->appendChild($this->xml->createElement("country", $this->prepareforXML( $order->billing_country)));
            $contact->appendChild($this->xml->createElement("delivery_gender"));
            $contact->appendChild($this->xml->createElement("delivery_firstname", $this->prepareforXML( $order->shipping_first_name)));
            $contact->appendChild($this->xml->createElement("delivery_lastname", $this->prepareforXML( $order->shipping_last_name)));
            $contact->appendChild($this->xml->createElement("delivery_company", $this->prepareforXML( $order->shipping_company)));
            $contact->appendChild($this->xml->createElement("delivery_street", $this->prepareforXML( $order->shipping_address_1)));
            $contact->appendChild($this->xml->createElement("delivery_zip", $this->prepareforXML( $order->shipping_postcode)));
            $contact->appendChild($this->xml->createElement("delivery_city", $this->prepareforXML( $order->shipping_city)));
            $contact->appendChild($this->xml->createElement("delivery_country", $this->prepareforXML( $order->shipping_country)));
            $contact->appendChild($this->xml->createElement("phone", $this->prepareforXML( $order->shipping_country)));
            $contact->appendChild($this->xml->createElement("email", $this->prepareforXML( $order->shipping_country)));
            $orderXML->appendChild($contact);
          
            $comment = $this->xml->createElement("comment", $this->prepareforXML( $order->customer_note));
            $commentid = $this->xml->createAttribute("date");
            $commentid->value = $create_at;
          
            $comment->appendChild($commentid);
            $orderXML->appendChild($comment);
          

          
            foreach ($order->get_items() as $key => $item) {

                $this->debug($item);
                $product = new WC_Product($item['product_id']);

                $itemXML = $this->xml->createElement("item");


                //attributes
                $gross = $this->xml->createAttribute("gross");
                $gross->value = $product->get_price_including_tax();
                $vatpercent = $this->xml->createAttribute("vatpercent");
                $vatpercent->value = $this->prepareforXML(round(NeosFaktura_fakturamaXMLHelper::calcTaxfromGross($product->get_price_including_tax(), $product->get_price_excluding_tax())).".00");
                $quantity = $this->xml->createAttribute("quantity");
                $quantity->value = $this->prepareforXML($item['qty']);
                $id = $this->xml->createAttribute("productid");
                $id->value = $this->prepareforXML($item['product_id']);

                $itemXML->appendChild($gross);
                $itemXML->appendChild($vatpercent);
                $itemXML->appendChild($quantity);
                $itemXML->appendChild($id);

                $itemXML->appendChild($this->xml->createElement("model", $this->prepareforXML($item['variation_id'])));
                $itemXML->appendChild($this->xml->createElement("ean"));
                $itemXML->appendChild($this->xml->createElement("name", $this->prepareforXML($item['name'])));
                $itemXML->appendChild($this->xml->createElement("category", $this->prepareforXML(NeosFaktura_fakturamaXMLHelper::getFirstCategory($product->get_categories()))));
                $itemXML->appendChild($this->xml->createElement("qunit"));
                $itemXML->appendChild($this->xml->createElement("vatname", "MwSt. " . $this->prepareforXML($this->calcTax($item['line_subtotal_tax'], $item['line_subtotal'])) . "%"));

                $orderXML->appendChild($itemXML);



            }

            /**
            * Shipping
            *
            **/
            $shippingXML = $this->xml->createElement("shipping");
            //attributes
            $gross = $this->xml->createAttribute("gross");
            $gross->value = $this->prepareforXML(round(floatval($order->get_total_shipping()) + floatval($order->get_shipping_tax()), 2));
            $vatpercent = $this->xml->createAttribute("vatpercent");
            $vatpercent->value = $this->prepareforXML($this->calcTax($order->get_shipping_tax(), $order->get_total_shipping())).".00";


            $shippingXML->appendChild($gross);
            $shippingXML->appendChild($vatpercent);

            $shippingXML->appendChild($this->xml->createElement("name", $this->prepareforXML($order->get_shipping_method())));
            $shippingXML->appendChild($this->xml->createElement("vatname", "MwSt. 19%"));
            $orderXML->appendChild($shippingXML);
           /**
            * Payment
            *
            **/
            $paymentXML = $this->xml->createElement("payment");
            //attributes
            $type = $this->xml->createAttribute("type");
            $type->value = $this->prepareforXML($order->payment_method);
            $total = $this->xml->createAttribute("total");
            $total->value = $this->prepareforXML($order->get_total());

            #var_dump($order);
            #die();

            $paymentXML->appendChild($type);
            $paymentXML->appendChild($total);

            $paymentXML->appendChild($this->xml->createElement("name", $this->prepareforXML($order->payment_method_title)));
            $orderXML->appendChild($paymentXML);

           
            
          
            $ordersXML->appendChild($orderXML);
        }
      
        $this->dom->appendChild($ordersXML);

        

        if($this->debug == False)
        {
            return  $this->xml->saveXML();
        }
       else{
            return "";
       }
        
    }
    
    public function get_products_orders (){
        $this->get_products();
        return $this->get_orders();

    }

    public function setstate ($orderstosync){
        $orderstosync = trim($orderstosync, '{}');
        
        if(empty($orderstosync)){
          return;
        }
        
        $comment = "";
        
        if (strpos($orderstosync , '*') !== false) {
            $comment = explode('*', $orderstosync);            
            $state = explode('=', $comment[0]);   
            $id = $state[0];
            $state = $state[1];
            $comment = $comment[1];
            
            
        }
        else{
            $state = explode('=', $orderstosync);
            $id = $state[0];
            $state = $state[1];
        }
      
      
          echo $state;
          


            $convstate = '';

            if(intval($state) == 1){
                $convstate = 'pending';

            }else if(intval($state) == 2){
                $convstate = 'processing';

            }if(intval($state) == 3){
                $convstate = 'completed';

            }

            $order = new WC_Order(intval($id));
            $order->update_status($convstate);
      
            if(!empty($comment))
            {
              $order->add_order_note($comment);
            }



    }

    private function calcTax($total_tax, $price){
      if($total_tax > 0 && $price > 0)
      {
        return round($total_tax / $price * 100);
      }
      else{
        return 0;
      }
        
    }


    private function debug($array){

        if($this->debug == True)
        {
            echo "<pre>";
            var_dump($array);
        }

    }

    private function filePath($filePath)
    {
        
        $fileParts = pathinfo($filePath);



        if(!isset($fileParts['filename']))
        {$fileParts['filename'] = substr($fileParts['basename'], 0, strrpos($fileParts['basename'], '.'));}


        $subf = explode("/", $filePath );

        

        $key1 = array_search('uploads', $subf);
        $key2 = array_search($fileParts['basename'], $subf );

        
        $count = count($subf);

        //remove elements
        for ($i=0; $i <= $count; $i++ ){
            if($i <= $key1 || $i >= $key2){

                unset($subf[$i]);
            }
        }

        $fileParts['subfolder'] = "/".implode("/", $subf);
        $fileParts['dirname']   = str_replace($fileParts['subfolder'], '' , $fileParts['dirname']);


        return $fileParts;
    }

}