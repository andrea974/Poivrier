<?php 
   class Order{

    public $id;
    public $sessionIp;
    public $orderedDate;
    public $plannedDate;
    public $takeAway;
    public $add;
    public $delivered;
    public $available;
    public $foodList;


   function __construct($id, $sessionIp, $plannedDate, $takeAway, $add, $delivered, $available){
   
      $this->id            = $id;
      $this->sessionIp       = $sessionIp;
      //$this->orderedDate   = $orderedDate;
      $this->plannedDate    = $plannedDate;
      $this->takeAway      = $takeAway;
      $this->add           = $add;
      $this->delivered     = $delivered;
      $this->available     = $available;
      $this->foodList     = $this->getFoodList();
   }

   function getFoodList(){
      global $DB;
      $query= "SELECT oe.food_id, SUM(f.price) totalPrice, f.label, COUNT(DISTINCT oe.food_num) nbSame,

      (SELECT  GROUP_CONCAT(DISTINCT test.prov_id)
      FROM order_element_info test
      WHERE food_num = oe.food_num
       AND order_id = oe.order_id
      AND food_id = oe.food_id) as listProv
      ,
      
      (
          CASE WHEN (SELECT  GROUP_CONCAT(DISTINCT test.prov_id)
      FROM order_element_info test
      WHERE food_num = oe.food_num
      AND order_id = oe.order_id
      AND food_id = oe.food_id) IS NOT NULL THEN
      (
          SELECT  GROUP_CONCAT( DISTINCT wtf.food_num)
      FROM order_element_info g
      RIGHT JOIN order_element wtf ON
      g.order_id = wtf.order_id 
      AND g.food_id = wtf.food_id 
      AND g.food_num = wtf.food_num
      WHERE wtf.food_id = oe.food_id
      AND g.prov_id = listProv)
          ELSE 
          (
          SELECT  GROUP_CONCAT( DISTINCT h.food_num)
      FROM order_element h
      LEFT JOIN order_element_info wtf ON
      h.order_id = wtf.order_id 
      AND h.food_id = wtf.food_id 
      AND h.food_num = wtf.food_num
              
             WHERE h.food_id = oe.food_id
              AND h.order_id = oe.order_id
              AND wtf.prov_id IS NULL
              
      
          )
          
           END
      )
      
      
      as listNumConcerne
          
      FROM order_element oe
      INNER JOIN food f ON f.food_id = oe.food_id
      LEFT JOIN order_element_info oei ON
      oe.order_id = oei.order_id 
      AND oe.food_id = oei.food_id 
      AND oe.food_num = oei.food_num
      WHERE oe.order_id = ?
      GROUP BY oe.food_id, oei.prov_id  
      ORDER BY `listNumConcerne` ASC";

      $result = $DB->QueryDB($query, [$this->id]);


      foreach ($result as $food) { //foreach($tab as $ligne){$ligne['bddColumn']}
      

         $listFood[ $food['food_id'].$food['listNumConcerne'] ]= new OrderedFood(
            $food['food_id'], 
            $food['label'], 
            $food['totalPrice'], 
            $food['nbSame'],
            $food['listNumConcerne']);
      }


      if(!isset($listFood)){ $listFood = null;}
      return $listFood ;
   }

   function addDB(){
      global $DB;
      $query= "INSERT INTO 
      order_saved( 
         session_ip ,
         planned_date, 
         take_away, 
         info_add, 
         delivered, 
         available 
      ) VALUES(?,CURRENT_DATE,?,?,?,?)";

      $DB->QueryDB($query, [$this->sessionIp , $this->takeAway, $this->add, $this->delivered, $this->available]);
      
   }




}




?>