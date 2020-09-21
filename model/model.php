<?php 
require_once("model/food.php");
require_once("model/order.php");

$DB = new DB() ;
$MODEL = new Model();


// Connexion à la BASE DE DONNÉES
class DB {
  private $connect = null;
  private $result = null;
  public $totalResult = null;
  public $rowPerPage = null;

  function __construct(){

    $config = parse_ini_file("config.ini");

    $login  = $config['DB_LOGIN'];
    $passwd = $config['DB_PASSWORD'];
    $base   = $config['DB_NOM'];
    $host   = $config['DB_SERVEUR'];
    $type   = $config['DB_SGBD'];
    $dsn    = $type.':host='. $host.';dbname='. $base;
  
    $this->connect = new PDO($dsn, $login, $passwd);
    $this->connect->query("SET NAMES UTF8");
  }

  function __destruct(){
    if ($this->result!==null) { $this->result = null; }
    if ($this->connect!==null) { $this->connect = null; }
  }

  function QueryDB($strSQL, $tblValeur){

    $this->result = $this->connect->prepare($strSQL);


    $this->result->execute($tblValeur);
    return $this->result->fetchAll();
    
  }

  function paginateQueryDB($query,$tabValue, $displayView, $rowPerPage){
    global $MODEL ;
    //Reqête pour récupérer la liste des livres
 
    $this->totalResult = COUNT(  $this->QueryDB($query, $tabValue) ); //Compter le nombre de lignes
    $this->rowPerPage = $rowPerPage;
    $totalPage = ceil($this->totalResult/$this->rowPerPage); //Calcul du nombre de page totale
    

     if( 
       isset($_GET["numPage"]) 
       AND !empty($_GET["numPage"]) 
       AND $_GET["numPage"] > 0 
       AND $_GET["numPage"] <= $totalPage
     ){
       $_GET["numPage"] = intval($_GET["numPage"]);
       $_GET["numPage"] = $_GET["numPage"];
      } 
    else {
      $_GET["numPage"] = 1;
    }
    $start = ($_GET["numPage"]-1)*$rowPerPage;
    $query = $query." LIMIT ". $start .", " .$rowPerPage;

    
   $result["resultSQL"] = $this->QueryDB($query, $tabValue);
   $result["totalResult"] = $this->totalResult;
   
   return $result ;

     
  }

  function listingResultPage($currentPage){
    global $MODEL ;
    $totalPage = ceil($this->totalResult/$this->rowPerPage); //Calcule du nombre de page totale
    $listPage = "";
    if ($totalPage > 1){
       for($i=1; $i<=$totalPage ;$i++) {
          if($i == $currentPage) {
             $listPage .= '<span>'.$i.'</span>';
          } 
          else {
             //$getCurrentUrlVar = $MODEL->getUrlVar();
             $varUrl="?";
             foreach ($_GET as $getKey => $getValue) {
              if($getKey != "action" && $getValue !=null || $getKey != "page"){
                $varUrl.="$getKey=$getValue&";
              }
            }
             $listPage .= '<a href="'.$varUrl.'&numPage='.$i.'">'.$i.'</a>';
          }
       }
   }

   return $listPage ; 
  }
  
}

class Model{
   public $DB;
   
   public function __construct(){
     global $DB ; $this->DB = $DB;
   }

  function getListFood(){

    $query= "SELECT * FROM food";
    $listFood=$this->DB->QueryDB($query, ['*']);

    foreach($listFood as $food){
      $newListFood[$food['food_id']]= new Food($food['food_id'],
                              $food['label'],
                              $food['price']);
    }
    return $newListFood;
  }
  

  function infoFood($idFood){
    
    $query= "SELECT * 
             FROM food 
             WHERE food_id=?";

    $infoFood= $this->DB->QueryDB($query, [$idFood])[0];

    $newInfoFood=new Food($food['food_id'],
                          $food['label'],
                          $food['price']);

    return $newListFood;
  }


  //  function getOrder(){

  //   $query= "SELECT * 
  //            FROM order_saved
  //            WHERE session_ip = ?";

  //   $listOrder= $this->DB->QueryDB($query, [ $_SERVER['REMOTE_ADDR'] ] )[0];
  //   $newListOrder= new Order(
  //                           $listOrder['num_order'],
  //                           $listOrder['session_ip'],
  //                           $listOrder['ordered_date'],
  //                           $listOrder['planned_date'],
  //                           $listOrder['take_away'],
  //                           $listOrder['info_add'],
  //                           $listOrder['delivered'],
  //                           $listOrder['available']
  //                         ); 
  //   return $newListOrder;
  //  }

  
  function getOrderActiveSession(){

    $query= "SELECT * 
             FROM order_saved
             WHERE session_ip= ?";

    $result = $this->DB->QueryDB($query, ["165.169.60.44"])[0];
    
    $orderSession= new Order(
      $result['num_order'], 
      $result['session_ip'], 
      $result['planned_date'], 
      $result["take_away"], 
      $result["info_add"], 
      $result["delivered"], 
      $result["available"]
  );

  return $orderSession;
 }

 



function getListProvision($categId){

  $query= "SELECT * 
         FROM provision 
         WHERE categ_id=?";
  
  $listProvision= $this->DB->QueryDB($query, [$categId]);  //[0]-> met quand retourne un seul résultat
  
  foreach($listProvision as $provision){

    $newlistProvision[ $provision['id_prov'] ]= new Provision($provision['id_prov'], //[id_prov]->c'est une clé unique donc prendre champ  2eme raison: identifier facile
                                     $provision['label'],
                                     $provision['price'],
                                     $provision['stock'],
                                     $provision['categ_id']);
  }
  return $newlistProvision;
}

function getInfoProvision($provId){

  $query= "SELECT * 
         FROM provision 
         WHERE id_prov=?";
  
  $result= $this->DB->QueryDB($query, [$provId])[0];  //[0]-> met quand retourne un seul résultat
  


    $provision = new Provision($result['id_prov'], //[id_prov]->c'est une clé unique donc prendre champ  2eme raison: identifier facile
                                     $result['label'],
                                     $result['price'],
                                     $result['stock'],
                                     $result['categ_id']);
 
  return $provision;
}

function getListCateg(){

  $query= "SELECT * 
         FROM categ";
  
  $listCateg= $this->DB->QueryDB($query, ["*"]);

  foreach($listCateg as $categ){

    $newListCateg[ $categ['categ_id']]= new Category($categ['categ_id'],
                                $categ['name']);
  }

  return $newListCateg;
}




 
  

}
 

?>