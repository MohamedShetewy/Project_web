<?php 
	ob_start();
	$pagetitle = 'Categories';
include "init.php"; ?>

<div class="container">
	<div class="row">
		<?php
		   if(isset($_GET['name'])){
		   		$tag = $_GET['name'];
		   		echo '<h1 class="text-center">'. $tag .'</h1>';
				
				
				$tagitem = getAllFrom("*","items","where tags like '%$tag%'","AND approve = 1","Item_ID");
				foreach ($tagitem as $item) {
					echo '<div class="col-sm-6 col-md-3">';
						echo '<div class="thumbnail item-box img-h">';
							echo '<span class="price-tag">$' . $item['Price'] . '</span>';
							echo '<img class="img-responsive" src="img.jpg" alt="" />';
							echo '<div class="caption">';
								echo '<h3><a href="item.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</a></h3>';
								echo '<p>' . $item['Description'] . '</p>';
								echo '<div class="date">' . $item['Add_Date'] . '</div>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				}
	        }else{
	    		echo "<div class='alert alert-danger'> You didn't specify Page ID</div>";
	    	}
	?>
	</div>
</div>


<?php include $tpl . "footer.php";
ob_end_flush();
?>
