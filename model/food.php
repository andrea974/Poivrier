<?php 
   class Food{

      public $id;
      public $label;
      public $price;
      public $listCateg;

   
   
      function __construct($id, $label, $price){
         $this->id            = $id;
         $this->label       = $label;
         $this->price   = $price;

      }

      function getListCateg(){
         $listCateg = null;
         $query = "SELECT categ.* 
         FROM categ_prov 
         INNER JOIN categ ON categ_prov.categ_num = categ.categ_id 
         WHERE categ_prov.food_num = ?" ;
         $result = $this->DB->QueryDB($query, [$this->id]);
         
         foreach ($result as $categ) {
            $listCateg[$categ["categ_id"]] = new Category($categ["categ_id"], $categ["name"]);
         }

         return $listCateg;
      }

      function getUrlImg(){
         $directory= "public/media/menu/food-img/food$this->id.jpg";
			return $directory;
      }

   
   }

   class OrderedFood extends Food{
      
      public $nbSame;
      //public $provInfo;
      //public $totalPrice;
      //public $orderId;



      function __construct($id, $label, $price, $nbSame, $listProvConcern){
      
         parent::__construct($id, $label, $price);
         $this->nbSame = $nbSame;
         // $this->provInfo               = $this->getProvInfo();
         // $this->totalPrice             = $this->getTotalPrice();
       
      
      }

      // function getTotalPrice(){
      //    global $DB;
      //    $query= "SELECT ROUND(SUM(price),2)+(SELECT price FROM food WHERE food_id=?) as totalPrice 
      //             FROM provision
      //             INNER JOIN order_element_info ON provision.id_prov=order_element_info.prov_id 
      //             WHERE order_element_info.ord_id=? 
      //             AND order_element_info.food_id = ?
      //             AND order_element_info.num_elmt = ?";
      //    $result = $DB->QueryDB($query, [$this->id, $this->orderId, $this->id, $this->numFood] );
         
      //    return $result[0]["totalPrice"] ;
         
      // }


      // function getProvInfo(){
      //    global $DB;

      //    $query = "SELECT order_element_info.*, categ.categ_id as categNum
      //    FROM order_element_info 
      //    INNER JOIN provision ON order_element_info.prov_id = provision.id_prov
      //    LEFT JOIN categ ON provision.categ_id = categ.categ_id
      //    WHERE order_element_info.ord_id=? 
      //    AND order_element_info.food_id = ?
      //    AND order_element_info.num_elmt = ?";

      
      
      //    $result = $DB->QueryDB($query, [$this->orderId, $this->id, $this->numFood] );
      //       global $MODEL;
          
      //       foreach ($result as $infoProv) {
      //          $listInfoProv[$infoProv['categNum']]= $MODEL->getInfoProvision($infoProv["prov_id"]) ; //[$infoProv['prov_id']] pour pas écraser
      //       }
      //       if( !isset($listInfoProv) ){$listInfoProv = null ;}

      //    return $listInfoProv;
      // }

   }
   
   

   class Provision{
      protected $DB; 
      public $id; 
      public $label;
      public $price;
      public $stock;
      public $categId;


      function __construct($id, $label, $price, $stock, $categId){
         global $DB; $this->db   =   $DB;
         $this->id               = $id;
         $this->label            = $label;
         $this->price            = $price;
         $this->stock            = $stock;
         $this->categId          = $categId;
      }
   
   }

   
   class Category{
       
      private $DB;
      public $id;
      public $name;


      function __construct($id, $name){
         global $DB; $this->db   =   $DB;
         $this->id               = $id;
         $this->name             = $name;
      }

      
   }
    

?>