

<div class="menu_frame">
   <h3> <?php echo $food->label; ?></h3>
   <div class="img" style="background-image: url('<?php echo $food->getUrlImg(); ?>');" ></div>
   <span> <?php echo $food->price; ?> </span>
   <button class="purchase"> Ajouter à ma commande </button>
</div> 

