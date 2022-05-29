<?php 
	ob_start();
	$pagetitle = 'Categories';
include "init.php"; ?>

<div class="container min-h">
	<h1 class="text-center">Show Category Items</h1>
	<div class="row">
		<?php
		   if(isset($_GET['pageid']) && is_numeric($_GET['pageid'])){
				$catid = intval($_GET['pageid']);
				$allItems = getAllFrom('*','items','where Cat_ID = ' . $catid ,'AND approve = 1','Item_ID');	
				foreach ($allItems as $item) {
					echo '<div class="col-sm-6 col-md-3">';
						echo '<div class="thumbnail item-box img-h">';
							echo '<span class="price-tag">$' . $item['Price'] . '</span>';	
							if (empty($item['imgitem'])) {
								echo "<img src='admin/upload/item/defualt.jpg' alt=''/>";
					        }else{
								echo "<img class='img-responsive' src='admin/upload/item/" . $item['imgitem'] ."' alt=''/>";
							}
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
