<?php 
	session_start() ;
	
	
	require_once "controller/controller.php";
    $controller= new Controller();
	$controller->defaultVar();
	$controller->createOrder();

	
?>

<!DOCTYPE html>
<HTML lang="fr">
<head>
	<?php include 'view/head.php';
	?>
	
	<?php 
		echo "<script src='script/....js'> </script>";
		echo "<script src='public/script/....js'> </script>";
		echo 	"<link rel='stylesheet'  type='text/css' href='public/css/poivrier.css'>";
	switch ($_GET["page"]) {
		case "order":
			echo "<link rel='stylesheet'  type='text/css' href='public/css/orderv2.css'>";
			break;
		case "menu":
			echo "<link rel='stylesheet'  type='text/css' href='public/css/menu.css'>";
			break;
		}
	
	?>
	<title> Le poivrier </title>
</head>

<body>

<!--------------Barre de navigation des menus------------------>
<?php
	//Insertion de la barre de navigation
		$controller->navBar();
	?>
<!------------------------------------------------------------->

<div id="content">
		
	
	<?php
		include "view/header.php";

		switch ($_GET["page"]) {
			case "index":
				echo "<div id='test'>";
				echo "</div>" ;
				break;
			case "order":
				$controller->order();
				break;
			case "menu":
				$controller->menu();
				break;

			default:
				header("Location: index.php");
				break;
		}

	



	?>
	

</div>


	
</body>
</HTML>