<?php 
	ob_start();	
	session_start();
	$pagetitle = 'Profile';
	include "init.php";
	if(isset($_SESSION['user'])){

		$getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
		$getUser->execute(array($sessionUser));
		$info = $getUser->fetch();
		$userid =  $info['UserID'];

?>
<h1 class="text-center">My Profile</h1>
<div class="infromation block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My Information</div>
			<div class="panel-body">
				<ul class="list-unstyled">
					<li> 
						<i class="fa fa-unlock-alt fa-fw"></i>
						<span>Login Name</span> : <?php echo $info['Username']?>
					</li>
					<li> 
						<i class="fa fa-envelope-o fa-fw"></i>
						<span>Email</span>: <?php echo $info['Email']?>
						 </li>
					<li>
						<i class="fa fa-user fa-fw"></i>
					 	<span>Full Name</span> : <?php echo $info['FullName']?>
					</li>

					<li>
						<i class="fa fa-calendar fa-fw"></i>
						<span>Register Date</span> : <?php echo $info['Date']?> 
					</li>

					
				</ul>
				<!--<a href="#" class="btn btn-default">Edit Information</a> -->
			</div>
		</div>
	</div>
</div>


<div id="my-ads" class="my-ads block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My Items</div>
			<div class="panel-body">
				
					<?php
					$allItems = getAllFrom("*","items","where Member_ID = $userid" ,"","Item_ID");	
					if(!empty($allItems)){
						echo '<div class="row">';
						
						foreach ($allItems as $item) {
							
							echo '<div class="col-sm-6 col-md-3">';
								echo '<div class="thumbnail item-box img-h">';
									if ($item['Approve'] == 0) {
										echo "<span class='approve-status'>Waiting Approve</span>";
									}
									echo '<span class="price-tag">$' . $item['Price'] . '</span>';								
									if (empty($item['imgitem'])) {
										echo "<img src='admin/upload/item/defualt.jpg' alt=''/>";
							        }else{
										echo "<img class='img-responsive' src='admin/upload/item/" . $item['imgitem'] ."' alt=''/>";
									}
									echo '<div class="caption">';
										echo '<h3> <a href="item.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</a></h3>';
										echo '<p>' . $item['Description'] . '</p>';
										echo '<div class="date">' . $item['Add_Date'] . '</div>';
									echo '</div>';
								echo '</div>';
							echo '</div>';
						}
						echo '</div>';
					}else{

						echo 'There\'s No Ads to Show, create <a href="newad.php">New Ads</a>';
					}

					?>
			</div>
		</div>
	</div>
</div>


<div class="my-comments block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Latest Comments</div>
			<div class="panel-body">
				<?php
					// related to comment

					$allComments = getAllFrom("comment","comments","where user_id = $userid","","c_id");
					if(!empty($allComments)){

						foreach ($allComments as $comment) {
							echo '<p>' . $comment['comment'] . '</p>';
						}

					}else{

						echo "There's No Comments to Show";
					}
				?>
			</div>
		</div>
	</div>
</div>


<?php

	}else{

		header('Location: login.php');
		exit();
	}

 include $tpl . "footer.php";
 ob_end_flush();
 ?>
