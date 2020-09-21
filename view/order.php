<div id="order">  
<h2>Ma commande</h2>

    <div id="recap">
        <div id="food-list">
       
        <?php foreach($orderSession->foodList as $food){ ?>
          
            <div class="food_frame">
                <div style=" background-image: url('public/media/menu/food-img/food<?php echo $food->id; ?>.jpg');" class="imgProd"> <span class="nb"> <?php echo $food->nbSame ?></span> <?php echo $food->label; ?> </div>
                <div class="content">
              
                    <div class="info"> <?php //echo $food->provInfo[2]->label  ?> <span> <?php if(isset($food->provInfo[1]->label)){ echo $food->provInfo[1]->label;}   ?></span> </div>
                    <div class="price"> <?php //echo $food->totalPrice."€" ?><span> <img alt="Information" style="width:22px" src="public/media/order/info.png"> </span>  </div>
                    <div class="action"> 
                        <div class="delete">
                            <img class="icon" src="public/media/order/trashv2.png">  
                        </div>
                        <div class="qty">
                            <button class="add"> + </button>
                            <button class="moins"> - </button>
                        </div>
                    </div>
                </div>
            </div>   
        <?php } ?>

        <div id="order-info">
            <table>
                <tr>
                    <td>Produit</td>
                    <td>Prix </td>
                </tr>
                <tr>
                    <td><p>Produits</p></td>
                    <td><p>9€</p></td>
                </tr>
                
                <tr>
                    <td><p>Livraison</p></td>
                    <td><p>2€</p></td>
                </tr>
               
            </table>
            <div id="purchase">
                <p style="font-weight: bold;">TOTAL: 11€</p>

            </div>
            <button type="submit"> Valider la commande </button>
        </div>
    </div>
</div>