<?php 
	ob_start();		
	session_start();
	$pagetitle = 'Show Items';
	include "init.php";	

	// check item Id id number and found
	$itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid']))? intval($_GET['itemid']):0;
	$stmt = $con->prepare("SELECT items.* , categories.Name As Category_Name ,users.Username FROM items
								   INNER JOIN categories ON categories.ID = items.Cat_ID
								   INNER JOIN users ON users.UserID = items.Member_ID
							 WHERE 
							 Item_ID = ? AND Approve = 1");



			$stmt->execute(array($itemid));
			$item = $stmt->fetch();
			$count = $stmt->rowCount();

    if($count > 0){ 

?>

	<h1 class="text-center"> <?php echo $item['Name']?></h1>
	<div class="container min-h item-mimg">
		<div class="row">
			<div class="col-md-3 img-h">
					<?php
					if (empty($item['imgitem'])) {
						echo "<img src='admin/upload/item/defualt.jpg' alt=''/>";
			        }else{
					     echo "<img class='img-responsive img-thumbnail center-block' src='admin/upload/item/" . $item['imgitem'] ."' alt=''/>";
				    }
					?>
			</div>
			<div class="col-md-9 item-info">
				<h2><?php echo $item['Name']?></h2>
				<p><?php echo $item['Description']?></p>
				<ul class="list-unstyled">
					<li>
						<i class="fa fa-calendar fa-fw"></i>
						<span>Added Date  </span>: <?php echo $item['Add_Date']?>
					</li>
					<li>
						<i class="fa fa-money fa-fw"></i>
						<span>Price  </span>: $<?php echo $item['Price']?>
					</li>
					<li>
						<i class="fa fa-building fa-fw"></i>
						<span>Made In  </span>: <?php echo $item['Country_Made']?>
					</li>
					<li>
						<i class="fa fa-tags fa-fw"></i>
						<span>Category Name  </span>: <a href="categories.php?pageid=<?php echo $item['Cat_ID']?>"><?php echo $item['Category_Name']?></a>
					</li>
					<li>
						<i class="fa fa-user fa-fw"></i>
						<span>Added By  </span>: <a href="#"><?php echo $item['Username']?> </a>
					</li>
					<li class="tags-items">
						<i class="fa fa-user fa-fw"></i>
						<span>Tags  </span>: 
						<?php
						$alltags = explode(",",$item['tags']);
							foreach ($alltags as $tag) {
								$tag = str_replace(' ', '',$tag);
								$lowertag = strtolower($tag);
								if (!empty($tag)) {
									echo " <a href='tags.php?name={$lowertag}'>". $tag . "</a>";
								}
								
							}
						?>
					</li>
				</ul>
			</div>
		</div>
		<hr class="custom-hr">
		<!-- start add comment -->
		<?php if(isset($_SESSION['user'])){?>
		<div class="row">
			<div class="col-md-offset-3">
				<div class="add-comment">
					<h3>Add Your Comment</h3>
					<form action="<?php echo $_SERVER['PHP_SELF'] .'?itemid='. $item['Item_ID'] ?>" method="POST">
						<textarea class="form-control" name="comment" required></textarea>
						<input class="btn btn-primary" type="submit" value="Add Comment">
					</form>
					<?php

					if ($_SERVER['REQUEST_METHOD'] == 'POST') {
						
						$comment  =filter_var($_POST['comment'],FILTER_SANITIZE_STRING);						
						$itemid   =$item['Item_ID'];
						$userid   =$_SESSION['uid'];

						if (!empty($comment)) {
							
							$stmt =$con->prepare("INSERT INTO comments(comment,status,comment_date,item_id,user_id)
												VALUES(:zcomment,0,now(),:zitemid,:zuserid)");
							$stmt->execute(array(
								'zcomment' =>$comment,
								'zitemid'  =>$itemid,
								'zuserid'  =>$userid
							));

							if ($stmt) {
								
								echo '<div class="alert alert-success">Comment Added</div>';
							}
						}else{

							echo '<div class="alert alert-danger">Comment Can\'t be Empty</div>';
						}


					}

					?>
				</div>
			</div>
		</div>
		<?php 
		}else{

			echo '<a href="login.php">Login</a> OR <a href="login.php">Register </a>to add comment';
		}
		?>
		<!-- start End comment -->
		<hr class="custom-hr">
				<?php
					$stmt = $con->prepare("SELECT 
											comments.*,users.username AS Member FROM comments
											INNER JOIN users ON users.UserID = comments.user_id									
											WHERE status = 1 AND item_id = ? 
											ORDER BY c_id DESC
											");
					$stmt->execute(array($item['Item_ID']));
					$comments = $stmt->fetchAll();

					

				
		
		foreach ($comments as $comment) { ?>

			<div class="comment-box">
				<div class="row">
					<div class="col-md-2 text-center"> 
						<img class="img-responsive img-thumbnail img-circle center-block" src="img.jpg" alt="" />
						<?php echo $comment['Member'] ?>
						</div>
					<div class="col-md-10"> 
						<p class="lead">
							<?php echo $comment['comment'] ?>
						</p>
					</div>	
				 </div>
			</div>
			<hr class="custom-hr">

			<?php
				}

		?>


	</div>

<?php
}else{

			    echo "<div class='container min-h'>";
				echo "<div class='alert alert-danger'> There no such ID Or This item is Waiting Approval</div> ";
				echo "</div>";
		}	

 include $tpl . "footer.php";
 ob_end_flush();
 ?>
