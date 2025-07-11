<?php

/**
 * Created by IntelliJ IDEA.
 * User: xoxoxo
 * Date: 07.06.16
 * Time: 21:37
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}




class NeosFaktura_fakturamaXML
{
    private $innerxml;
    private DOMDocument $xml;



    public function __construct()
    {
      if ( ! defined('ABSPATH') ) {
            return;
      }
      


      $this->xml = new DOMDocument('1.0', 'UTF-8');


      $webshopex = $this->xml->createElementNS(NCFF_XMLNS, 'webshopexport');

      $webshopexattr1 = $this->xml->createAttribute('version');
      $webshopexattr1->value = "1.6";

      $webshopex->appendChild($webshopexattr1);

      $phpversion = $this->xml->createElement('phpversion', phpversion());
      $webshopex->appendChild($phpversion);
      
      $webshop = $this->xml->createElement('webshop');
      $webshopattrShop = $this->xml->createAttribute('shop');
      $webshopattrShop->value = "WooCommerce";
      
      $webshopattrUrl = $this->xml->createAttribute('url');
      $webshopattrUrl->value = get_bloginfo('url');  //fakturama expected localhost. 
      
      $webshop->appendChild($webshopattrShop);
      $webshop->appendChild($webshopattrUrl);
      $webshopex->appendChild($webshop);

      $complete_version = $this->xml->createElement("complete_version", get_bloginfo('version') . "_" . NCFF_PLUGINDATA['Version'] );
      $webshopex->appendChild($complete_version);
       $this->xml->appendChild($webshopex);
       $this->innerxml = $webshopex;
    }

    private function ncff_prepareforXML($string){
       return html_entity_decode(strip_tags($string));
    }
  
    private function ncff_get_product_list()
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

    
    public function ncff_get_order_stati() : bool|string {
        $stati = wc_get_order_statuses();
        
        $statusListXML = $this->xml->createElement("status_list");
        foreach( $stati as $id => $translation) {
            $statusXML = $this->xml->createElement("status", $translation);
            $statusXML->setAttribute("id", $id);            
            $statusXML->setAttribute("name", $translation);
            $statusListXML->appendChild($statusXML);
        }
        $this->innerxml->appendChild($statusListXML);
        
        if(NCFF_DEBUG == False)
        {
            return  $this->xml->saveXML();
        }
       else{
            return "";
       }
    }

    public function ncff_get_products (){

        $productsXML = $this->xml->createElement("products");
        $image = $this->xml->createAttribute("imagepath");
        $image->value = get_bloginfo('url');
        $productsXML->appendChild($image);

        $products = $this->ncff_get_product_list();


        $this->ncff_debug($products);




        foreach ($products as $pid => $product){


            //$image = $this->filePath($product['featured_src']);


            $productXML = $this->xml->createElement("product");
            //attributes
            $gross = $product->get_regular_price() ? $this->xml->createAttribute("gross") : false;
            if ($gross) {
                $gross->value = $product->get_regular_price();
                $productXML->appendChild($gross);
            }
            $vatpercent = $this->xml->createAttribute("vatpercent");
            $vattmp = WC_Tax::get_rates($product->get_tax_class());
            $vatpercent->value = intval(WC_Tax::round(array_shift($vattmp)['rate']).".00");
            $quantity = $this->xml->createAttribute("quantity");
            $quantity->value = $product->get_stock_quantity() ? $product->get_stock_quantity() : 0;
            $id = $this->xml->createAttribute("id");
            $id->value = $product->get_id();

            $image_id  = $product->get_image_id();
            $image_url = wp_get_attachment_image_url( $image_id, 'medium' );

            $productXML->appendChild($vatpercent);
            $productXML->appendChild($quantity);
            $productXML->appendChild($id);

            $productXML->appendChild($this->xml->createElement("model", $this->ncff_prepareforXML($product->get_sku())));
            $productXML->appendChild($this->xml->createElement("ean"));
            $productXML->appendChild($this->xml->createElement("name", $this->ncff_prepareforXML($product->get_title())));
            $cattmp = $product->get_category_ids();
            $productXML->appendChild($this->xml->createElement("category", htmlspecialchars($this->ncff_prepareforXML(get_term( array_shift($cattmp) , 'product_cat' )->name))));
            $productXML->appendChild($this->xml->createElement("qunit"));
            $taxlabeltmp = WC_Tax::get_rates($product->get_tax_class());
            $taxratetmp = WC_Tax::get_rates($product->get_tax_class());
            $productXML->appendChild($this->xml->createElement("vatname", array_shift($taxlabeltmp)['label']." ".$this->ncff_prepareforXML(WC_Tax::round(array_shift($taxratetmp)['rate'])."%")));
            $shortDesc = $this->xml->createElement("short_description");
            $shortDesc->appendChild($this->xml->createCDATASection($this->ncff_prepareforXML( $product->get_short_description())));
            $productXML->appendChild($shortDesc);
            // $productXML->appendChild($this->xml->createElement("short_description", $this->ncff_prepareforXML( $product->get_short_description())));
            $productXML->appendChild($this->xml->createElement("image", $this->ncff_prepareforXML($image_url))); //$this->ncff_prepareforXML($image['subfolder']."/".$image['basename'])));


            $productsXML->appendChild($productXML);


        }

        $this->innerxml->appendChild($productsXML);


       if(NCFF_DEBUG == False)
        {
            return  $this->xml->saveXML();
        }
       else{
            return "";
       }
    }

    public function ncff_get_orders (){

        $customer_orders = get_posts( array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        
        'post_type'   => wc_get_order_types(),
        'post_status' => array_keys( wc_get_order_statuses() ),
        ) );


        $ordersXML = $this->xml->createElement("orders");

        foreach ($customer_orders as $corder) {


            $order = new WC_Order($corder->ID);

            $this->ncff_debug($order);
            


            $create_at = NeosFaktura_fakturamaXMLHelper::ncff_getfakturaTime($order->order_date) ;

            #  $productXML->appendChild($this->xml->createElement("", )); 
            $orderXML = $this->xml->createElement("order");
            //attributes
            $id = $this->xml->createAttribute("id");
            $id->value = $this->ncff_prepareforXML( $corder->ID);
            $date = $this->xml->createAttribute("date");
            $date->value = $this->ncff_prepareforXML( $create_at);
            $currency = $this->xml->createAttribute("currency");
            $currency->value = $this->ncff_prepareforXML( $order->get_order_currency());
            $currency_value = $this->xml->createAttribute("currency_value");
            $currency_value->value = "1.000000";
            $state = $this->xml->createAttribute("status");
            $state->value = $this->ncff_prepareforXML( $order->get_status());
            
            $orderXML->appendChild($id);
            $orderXML->appendChild($date);
            $orderXML->appendChild($currency);
            $orderXML->appendChild($currency_value);
            $orderXML->appendChild($state);
            

            $contact = $this->xml->createElement("contact");
            $contactid = $this->xml->createAttribute("id");
            $contactid->value = $this->ncff_prepareforXML( $order->customer_user);
          
            $contact->appendChild($contactid);
          

            $contact->appendChild($this->xml->createElement("gender"));
            $contact->appendChild($this->xml->createElement("firstname", $this->ncff_prepareforXML( $order->billing_first_name)));
            $contact->appendChild($this->xml->createElement("lastname", $this->ncff_prepareforXML( $order->billing_last_name)));
            $contact->appendChild($this->xml->createElement("company", $this->ncff_prepareforXML( $order->billing_company)));
            $contact->appendChild($this->xml->createElement("street", $this->ncff_prepareforXML( $order->billing_address_1)));
            $contact->appendChild($this->xml->createElement("zip", $this->ncff_prepareforXML( $order->billing_postcode)));
            $contact->appendChild($this->xml->createElement("city", $this->ncff_prepareforXML( $order->billing_city)));
            $contact->appendChild($this->xml->createElement("country", $this->ncff_prepareforXML( $order->billing_country)));
            $contact->appendChild($this->xml->createElement("delivery_gender"));
            $contact->appendChild($this->xml->createElement("delivery_firstname", $this->ncff_prepareforXML( $order->shipping_first_name)));
            $contact->appendChild($this->xml->createElement("delivery_lastname", $this->ncff_prepareforXML( $order->shipping_last_name)));
            $contact->appendChild($this->xml->createElement("delivery_company", $this->ncff_prepareforXML( $order->shipping_company)));
            $contact->appendChild($this->xml->createElement("delivery_street", $this->ncff_prepareforXML( $order->shipping_address_1)));
            $contact->appendChild($this->xml->createElement("delivery_zip", $this->ncff_prepareforXML( $order->shipping_postcode)));
            $contact->appendChild($this->xml->createElement("delivery_city", $this->ncff_prepareforXML( $order->shipping_city)));
            $contact->appendChild($this->xml->createElement("delivery_country", $this->ncff_prepareforXML( $order->shipping_country)));
            $contact->appendChild($this->xml->createElement("phone", $this->ncff_prepareforXML( $order->shipping_country)));
            $contact->appendChild($this->xml->createElement("email", $this->ncff_prepareforXML( $order->shipping_country)));
            $orderXML->appendChild($contact);
          
            $comment = $this->xml->createElement("comment", $this->ncff_prepareforXML( $order->customer_note));
            $commentid = $this->xml->createAttribute("date");
            $commentid->value = $create_at;
          
            $comment->appendChild($commentid);
            $orderXML->appendChild($comment);
          

          
            foreach ($order->get_items() as $key => $item) {

                $this->ncff_debug($item);
                $product = new WC_Product($item['product_id']);

                $itemXML = $this->xml->createElement("item");


                //attributes
                $gross = $this->xml->createAttribute("gross");
                $gross->value = wc_get_price_including_tax($product);// ->get_price_including_tax();
                $vatpercent = $this->xml->createAttribute("vatpercent");
                $vatpercent->value = $this->ncff_prepareforXML(round(NeosFaktura_fakturamaXMLHelper::ncff_calcTaxfromGross($product->get_price_including_tax(), $product->get_price_excluding_tax())).".00");
                $quantity = $this->xml->createAttribute("quantity");
                $quantity->value = $this->ncff_prepareforXML($item['qty']);
                $id = $this->xml->createAttribute("productid");
                $id->value = $this->ncff_prepareforXML($item['product_id']);

                $itemXML->appendChild($gross);
                $itemXML->appendChild($vatpercent);
                $itemXML->appendChild($quantity);
                $itemXML->appendChild($id);

                $itemXML->appendChild($this->xml->createElement("model", $this->ncff_prepareforXML($item['variation_id'])));
                $itemXML->appendChild($this->xml->createElement("ean"));
                $itemXML->appendChild($this->xml->createElement("name", $this->ncff_prepareforXML($item['name'])));
                $itemXML->appendChild($this->xml->createElement("category", htmlspecialchars($this->ncff_prepareforXML(NeosFaktura_fakturamaXMLHelper::ncff_getFirstCategory($product->get_categories())))));
                $itemXML->appendChild($this->xml->createElement("qunit"));
                $itemXML->appendChild($this->xml->createElement("vatname", "MwSt. " . $this->ncff_prepareforXML($this->ncff_calcTax($item['line_subtotal_tax'], $item['line_subtotal'])) . "%"));

                $orderXML->appendChild($itemXML);



            }

            /**
            * Shipping
            *
            **/
            $shippingXML = $this->xml->createElement("shipping");
            //attributes
            $gross = $this->xml->createAttribute("gross");
            $gross->value = $this->ncff_prepareforXML(round(floatval($order->get_total_shipping()) + floatval($order->get_shipping_tax()), 2));
            $vatpercent = $this->xml->createAttribute("vatpercent");
            $vatpercent->value = $this->ncff_prepareforXML($this->ncff_calcTax($order->get_shipping_tax(), $order->get_total_shipping())).".00";


            $shippingXML->appendChild($gross);
            $shippingXML->appendChild($vatpercent);

            $shippingXML->appendChild($this->xml->createElement("name", $this->ncff_prepareforXML($order->get_shipping_method())));
            $shippingXML->appendChild($this->xml->createElement("vatname", "MwSt. 19%"));
            $orderXML->appendChild($shippingXML);
           /**
            * Payment
            *
            **/
            $paymentXML = $this->xml->createElement("payment");
            //attributes
            $type = $this->xml->createAttribute("type");
            $type->value = $this->ncff_prepareforXML($order->payment_method);
            $total = $this->xml->createAttribute("total");
            $total->value = $this->ncff_prepareforXML($order->get_total());

            #var_dump($order);
            #die();

            $paymentXML->appendChild($type);
            $paymentXML->appendChild($total);

            $paymentXML->appendChild($this->xml->createElement("name", $this->ncff_prepareforXML($order->payment_method_title)));
            $orderXML->appendChild($paymentXML);

           
            
          
            $ordersXML->appendChild($orderXML);
        }

        $this->innerxml->appendChild($ordersXML);

        

        if(NCFF_DEBUG == False)
        {
            return  $this->xml->saveXML();
        }
       else{
            return "";
       }
        
    }
    
    public function ncff_get_products_orders (){
        $this->ncff_get_products();
        return $this->ncff_get_orders();

    }

    public function ncff_setstate ($orderstosync){
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

    private function ncff_calcTax($total_tax, $price){
      if($total_tax > 0 && $price > 0)
      {
        return round($total_tax / $price * 100);
      }
      else{
        return 0;
      }
        
    }


    private function ncff_debug($array){

        if(NCFF_DEBUG == True)
        {
            echo "<pre>";
            var_dump($array);
        }

    }

    private function ncff_filePath($filePath)
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