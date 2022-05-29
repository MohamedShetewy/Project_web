<?php 
ob_start();	
session_start();
$pagetitle = 'Homepage';
include "init.php";
?>

<section class="intro"> 
	<div class="container">
	    <div class="row">
		    <div class="col-sm-6 jumbotron text-center wow bounceIn" data-wow-delay="0.5s" >
			<h1>MO<span class="intro-h">_</span>SH</h1>
			<p>This is a site at your service where you can offer to display products for sale , also to buy</p>
			<p>and follow up on customers' opinions on products</p>
		    </div>
		   
	    </div>
	</div>
  
</section>

       <!--================Projects Area =================-->
        <section class=" projects_area">
        	<div class="row">
        		<div class="projects_item col-sm-6 col-md-2">
					<img src="layout\images\project\projects-1.jpg" alt="">
					<div class="hover">
						<h4>Alex Complex for esidence</h4>
						<p>LCD screens are uniquely modern in style, and the liquid crystals that make them work have allowed humanity to create  slimmer.</p>
					</div>
				</div>
				<div class="projects_item col-sm-6 col-md-2">
					<img src="layout\images\project\projects-2.jpg" alt="">
					<div class="hover">
						<h4>Alex Complex for esidence</h4>
						<p>LCD screens are uniquely modern in style, and the liquid crystals that make them work have allowed humanity to create  slimmer.</p>
					</div>
				</div>
				<div class="projects_item col-sm-6 col-md-6">
					<img src="layout\images\project\projects-3.jpg" alt="">
					<div class="hover">
						<h4>Alex Complex for esidence</h4>
						<p>LCD screens are uniquely modern in style, and the liquid crystals that make them work have allowed humanity to create  slimmer.</p>
					</div>
				</div>
				<div class="projects_item col-sm-6 col-md-2">
					<img src="layout\images\project\projects-4.jpg" alt="">
					<div class="hover">
						<h4>Alex Complex for esidence</h4>
						<p>LCD screens are uniquely modern in style, and the liquid crystals that make them work have allowed humanity to create  slimmer.</p>
					</div>
				</div>
        	</div>
        </section>
        <!--================End Projects Area =================-->
<div class="container">
	<div class="row">
		<h1 class="text-center h-header">All Products</h1>
		<?php
		 
		foreach (getAllFrom('*','items','where Approve = 1','','Item_ID') as $item) {
			
			echo '<div class="col-sm-6 col-md-3 wow bounceIn" data-wow-offset="220" data-wow-delay="0.5s">';
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
	?>
	</div>
</div>
<hr>
<!-- main-carousel -->
  <section class="main-carousel">
	<h1 class="text-center h-header wow fadeIn" data-wow-duration="4s">The most Important Furniture Modern</h1>
	    <div id="myslide" class="carousel slide " data-ride="carousel">
	       <div class="carousel-inner" role="listbox">
		<div class="item active row">
		    <div class="col-md-6 text-center">
		  <img  src="layout/images/1.jpg" alt="pic 1">
		    </div>
		    <div class="col-md-6 text-center">
		  <img  src="layout/images/2.jpg" alt="pic 2">
		    </div>
		</div>
		
		<div class="item row">
		   <div class="col-md-6 text-center">
		  <img  src="layout/images/3.jpg" alt="pic 1">
		    </div>
		    <div class="col-md-6 text-center">
		  <img  src="layout/images/4.jpeg" alt="pic 2">
		    </div>
		 
		</div>
		
		<div class="item row">
		   <div class="col-md-6 text-center">
		  <img  src="layout/images/5.jpg" alt="pic 1">
		    </div>
		    <div class="col-md-6 text-center">
		  <img  src="layout/images/6.jpg" alt="pic 2">
		    </div>
		 
		</div>
		</div>
    
	     <a class="left carousel-control" href="#myslide" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	      </a>
	      <a class="right carousel-control" href="#myslide" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	      </a>
	    </div>
    </section>

<?php include $tpl . "footer.php";
ob_end_flush();
?>
