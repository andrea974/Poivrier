<?php
require_once("model/model.php");

class Controller{
   private $DB;
   public $MODEL;

   public function __construct(){
      global $DB ; $this->DB = $DB;
      $this->MODEL= new Model();
   }

   public function defaultVar(){
      if( !isset($_GET["page"]) ){ $_GET["page"] = "index" ;}
   }

   public function createOrder(){

      $query = "SELECT COUNT(num_order) as 'nbSession'
      FROM order_saved 
      WHERE session_ip = ?";
      $result = $this->DB->QueryDB($query, ["165.169.60.44"])[0] ;
      
      //Verifie si cette adresse ip a déjà fait une commande.
      if($result["nbSession"] >= 1){

      }
      else{
         echo "salut";
         $query= "SELECT MAX(num_order)+1 as 'newIdOrder'
                FROM order_saved";
         $newIdOrder = $this->DB->QueryDB($query, ["*"])[0] ;

         $newOrder = new Order(
            $newIdOrder["newIdOrder"], 
            "165.169.60.443", 
            null, 
            0, 
            null, 
            0, 
            0);
        // var_dump($newOrder);
         $newOrder->addDB();
      }

   }

   public function navBar(){
      include "view/nav.php";
   }

   public function order(){
      $orderSession = $this->MODEL->getOrderActiveSession();
   
      include "view/order.php";


   }

   public function menu(){
      echo "<div id='menu'>";
      
      $listFood=$this->MODEL->getListFood();
         foreach($listFood as $food){
            include "view/menu.php";
         }
      echo "</div>";
   }
}
   
?>