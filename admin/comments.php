<?php

//manage members edit, add , delete of comments

    session_start();
    $pagetitle = 'members';
	if (isset($_SESSION['username'])) {
		include 'init.php';

		
		$do = isset($_GET['do']) ?  $_GET['do']: 'Manage';


		if ($do=='Manage') { //Manage comments page

			

			$stmt = $con->prepare("SELECT 
										comments.*,items.Name AS Item_Name,users.username AS Member FROM comments
										INNER JOIN items ON items.Item_ID = comments.item_id
										INNER JOIN users ON users.UserID = comments.user_id
										ORDER BY c_id DESC
										");
			$stmt->execute();
			$rows = $stmt->fetchAll();
			if(!empty($rows)){
		 ?>

			<h1 class="text-center">Manage Comments</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table text-center table table-bordered">
							<tr>
								<td>ID</td>
								<td>Comment</td>
								<td>Item Name</td>
								<td>User Name</td>
								<td>Added Date</td>
								<td>Control</td>
							</tr>
							
								<?php 
								foreach($rows AS $row){

									echo "<tr>";
										echo "<td>" . $row['c_id'] ."</td>";
										echo "<td>" . $row['comment'] ."</td>";
										echo "<td>" . $row['Item_Name'] ."</td>";
										echo "<td>" . $row['Member'] ."</td>";
										echo "<td>" . $row['comment_date'] ."</td>";
										echo "<td class='control'>
												<a href='comments.php?do=Edit&comid=".$row['c_id'] ."'class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
												<a href='comments.php?do=Delete&comid=".$row['c_id'] ."'class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";

												if ($row['status']==0) {
													
													echo "<a href='comments.php?do=Approve&comid=".$row['c_id'] ."'class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
												}

											 echo "</td>";

									echo "</tr>";
								}
									?>
								
								
							   
							
							
						</table>
					</div>
					
				</div>


			<?php }else{?>
			<div class="container">
				<div class="nice-message">There's No Record To Show</div>
			</div>

		<?php  }?>

		<?php

	}elseif($do=='Edit') {  

			// Edit profile

			$comid = (isset($_GET['comid']) && is_numeric($_GET['comid']))? intval($_GET['comid']):0;
			

			$stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");
			$stmt->execute(array($comid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();

			if($count > 0){ ?>
				<h1 class="text-center">Edit Comment</h1>

				<div class="container">
					
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="comid" value="<?php echo $comid ?>" />
						<!--start Comment-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Comment</label>
							<div class="col-sm-10 col-md-6">
								<textarea class="form-control" name="comment"><?php echo $row['comment']; ?></textarea>
							</div>
						</div>
						<!--End Comment -->

						<!--start submit-->
						<div class="form-group form-group-lg">
							
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!--End submit -->

					</form>

				</div>

		<?php 

		}else{

			    echo "<div class='container'>";
				$themsg = "<div class='alert alert-danger'> there no such ID </div> ";
				redirecthome($themsg);
				echo "</div>";
			
		}

	 }
		elseif($do=='Update'){

			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				echo "<h1 class='text-center'>Update Comment</h1>";
		    	echo "<div class='container'>";

				$comid       =$_POST['comid'];
				$comment =$_POST['comment'];
				

				
			

			

					// Update in DB
			
					$stmt = $con->prepare("UPDATE comments SET comment = ?  WHERE c_id = ?");
					$stmt->execute(array($comment,$comid));

					$themsg = '<div class= "alert alert-success">' . $stmt->rowCount() . " row Update </div>";
					redirecthome($themsg,'back');
				



				echo "</div>";

			}else{

			    echo "<div class='container'>";
				$themsg = "<div class='alert alert-danger'> you can't to browsing that directly </div> ";
				redirecthome($themsg);
				echo "</div>";
			}

		}elseif($do=='Delete'){

			//Delete Comment page

			echo "<h1 class='text-center'>Delete Comment</h1>";
		    echo "<div class='container'>";
		    
		    // check  id exit and number
			$comid = (isset($_GET['comid']) && is_numeric($_GET['comid']))? intval($_GET['comid']):0;
			

			
			$count = checkitem("c_id","comments",$comid);

			if($count > 0){
				
				$stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zcom");
				$stmt->bindparam(":zcom",$comid);
				$stmt->execute();
				$themsg = '<div class= "alert alert-success">' . $stmt->rowCount() . " row Deleted </div>";

				redirecthome($themsg,'back');
				
			echo "</div>";
			}else{

				echo "<div class='container'>";
				$themsg = "<div class='alert alert-danger'> This Id Not Exit </div> ";
				redirecthome($themsg);
				echo "</div>";
				
			}

		}elseif($do=='Approve'){

			//Activate Comment page

			echo "<h1 class='text-center'>Activate Comment</h1>";
		    echo "<div class='container'>";

		    // check  id exit and number
			$comid = (isset($_GET['comid']) && is_numeric($_GET['comid']))? intval($_GET['comid']):0;
			

			
			$count = checkitem("c_id","comments",$comid);

			if($count > 0){
				
				    $stmt = $con->prepare("UPDATE comments SET status = 1  WHERE c_id = ?");
					$stmt->execute(array($comid));

					$themsg = '<div class= "alert alert-success">' . $stmt->rowCount() . " Record Activate </div>";
					redirecthome($themsg,'back');
				
			echo "</div>";
			}else{

				echo "<div class='container'>";
				$themsg = "<div class='alert alert-danger'> This Id Not Exit </div> ";
				redirecthome($themsg);
				echo "</div>";
				
			}


		}else{

			    echo "<div class='container'>";
				$themsg = "<div class='alert alert-danger'> This page Not Exit </div> ";
				redirecthome($themsg);
				echo "</div>";
		}


		include $tpl . "footer.php";

    }else{

    	header('location: index.php');
		exit();

    }