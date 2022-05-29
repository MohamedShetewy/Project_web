<?php

	ini_set('display_errors','Off');
	error_reporting(E_ALL);
//manage members edit, add , delete

    session_start();
    $pagetitle = 'members';
	if (isset($_SESSION['username'])) {
		include 'init.php';

		
		$do = isset($_GET['do']) ?  $_GET['do']: 'Manage';


		if ($do=='Manage') { //Manage Member page

			//select all users expect admin
			$quary ='';

			if (isset($_GET['page']) && $_GET['page'] == 'pending' ) {
				
				$quary= 'AND RegStatus = 0';
			}

			$stmt = $con->prepare("SELECT * FROM users WHERE  GroupID != 1 $quary
				                   ORDER BY UserID DESC");
			$stmt->execute();
			$rows = $stmt->fetchAll();
			if(!empty($rows)){
		 ?>

			<h1 class="text-center">Manage Member</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table manage-mambers text-center table table-bordered">
							<tr>
								<td>#ID</td>
								<td>Image</td>
								<td>Username</td>
								<td>Email</td>
								<td>FullName</td>
								<td>Registerd Date</td>
								<td>Control</td>

							</tr>
							
								<?php 
								foreach($rows AS $row){

									echo "<tr>";
										echo "<td>" . $row['UserID'] ."</td>";
										echo "<td>";
											if (empty($row['avatar'])) {
												echo "<img src='upload/avatar/defualt.png' alt=''/>";
											}else{

												echo "<img src='upload/avatar/" . $row['avatar'] ."' alt=''/>";
											}
										echo "</td>";
										echo "<td>" . $row['Username'] ."</td>";
										echo "<td>" . $row['Email'] ."</td>";
										echo "<td>" . $row['FullName'] ."</td>";
										echo "<td>" . $row['Date'] ."</td>";
										echo "<td class='control'>
												<a href='members.php?do=Edit&userid=".$row['UserID'] ."'class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
												<a href='members.php?do=Delete&userid=".$row['UserID'] ."'class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";

												if ($row['RegStatus']==0) {
													
													echo "<a href='members.php?do=Activate&userid=".$row['UserID'] ."'class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";
												}

											 echo "</td>";

									echo "</tr>";
								}
									?>
								
								
							   
							
							
						</table>
					</div>
				

					<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus "></i> New Member</a>
				</div>

			<?php }else{?>
			<div class="container">
				<div class="nice-message">There's No Record To Show</div>
				<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus "></i> New Member</a>
			</div>

		<?php  }?>
		<?php
		}elseif($do=='Add'){ // Add member page
			?>

			<h1 class="text-center">Add New Member</h1>

				<div class="container">
					
					<form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
						<!--start Username-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Username</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="username" class="form-control"  autocomplete="off" required="required" placeholder="Username To Login"/>
							</div>
						</div>
						<!--End Username -->

						<!--start Password-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Password</label>
							<div class="col-sm-10 col-md-6">
								<input type="password" name="password" class="password form-control" autocomplete="new-password" required="required" placeholder="Password Must Be Hard & Complex"/>
								<i class="show-pass fa fa-eye fa-2x"></i>
							</div>
						</div>
						<!--End Password -->

						<!--start Email-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Email</label>
							<div class="col-sm-10 col-md-6">
								<input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Vaild"/>
							</div>
						</div>
						<!--End Email -->
						<!--start Full Name-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Full Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="full" class="form-control" required="required" placeholder="Full Name Appear In Profile Page"/>
							</div>
						</div>
						<!--End Full Name --> 
						<!--start profile image-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">User Avatar</label>
							<div class="col-sm-10 col-md-6">
								<input type="file" name="avatar" class="form-control" required="required"/>
							</div>
						</div>
						<!--End profile image --> 
						<!--start submit-->
						<div class="form-group form-group-lg">
							
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!--End submit -->

					</form>

				</div>

		<?php
			 }elseif($do =='Insert'){

			 //Insert Member page

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo "<h1 class='text-center'>Insert new Member</h1>";
				echo "<div class='container'>";

				//upload Variables

				$avatar   = $_FILES['avatar'];

				$avatarName   = $_FILES['avatar']['name'];
				$avatarSize   = $_FILES['avatar']['size'];
				$avatarTmp    = $_FILES['avatar']['tmp_name'];
				$avatarType   = $_FILES['avatar']['type'];

				// list allow file types

				$avatarAllowExtension = array("jpeg","jpg","png","gif");
				
				// Get Extension from file

				$avatarExtension = strtolower(end(explode('.',$avatarName)));


				$username =$_POST['username'];
				$pass     =$_POST['password'];
				$email    =$_POST['email'];
				$full     =$_POST['full'];

				$hashpass = sha1($_POST['password']);
				
				//vaildate the form

				$formErrors =array();

				if (strlen($username) < 4) {
					
					$formErrors[] = 'User name Can\'t Be Less than <strong>4 characters </strong>';
				}

				if (empty($username)) {
					
					$formErrors[] = 'User name Can\'t Be <strong> Empty</strong>';
				}
				if (empty($pass)) {
					
					$formErrors[] = 'Password Can\'t Be <strong> Empty</strong>';
				}

				if (empty($email)) {
					
					$formErrors[] = 'Email Can\'t Be <strong> Empty</strong>';
				}
				if (empty($full)) {
					
					$formErrors[] = 'full Name Can\'t Be <strong> Empty</strong>';
				}

				if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowExtension)) {
					
					$formErrors[] = 'This Extension Not<strong> Allow</strong>';
				}

				if (empty($avatarName) ) {
					
					$formErrors[] = 'File Can\'t Be<strong> Empty</strong>';
				}

				if ( $avatarSize > 4194304) {
					
					$formErrors[] = 'File Can\'t be larger than <strong> 4MB</strong>';
				}

				foreach ($formErrors as $error) {
					$themsg =  '<div class= "alert alert-danger">' .$error .'</div>' ;
				}
				
				//check if no error

				if (empty($formErrors)) {

					$avatar = rand(0,10000000) . '_' . $avatarName;

					move_uploaded_file($avatarTmp,"upload\avatar\\" . $avatar );


					//check if user exit in DB  
					

					$check = checkitem("Username","users",$username);

					if ($check == 1) {
						
						
						$themsg =  '<div class= "alert alert-danger">Sorry User already Exit</div>' ;
					    redirecthome($themsg,'back');

					}else{

					// Insert in DB
				
						$stmt = $con->prepare("INSERT INTO 
												users (Username,Password,Email, FullName,RegStatus,Date,avatar)
												VALUES (:zuser,:zpass,:zemail,:zfull,1,now(),:zavatar)");
						$stmt->execute(array(
							'zuser'   => $username,
							'zpass'   => $hashpass,
							'zemail'  => $email,
							'zfull'   => $full,
							'zavatar' => $avatar
						));

						$themsg = '<div class= "alert alert-success">' . $stmt->rowCount() . " row Inserted </div>";

						redirecthome($themsg,'back');
						
					}
				}else{

						redirecthome($themsg,'back',8);
				} 
			}else{

				echo "<div class='container'>";
				$themsg = "<div class='alert alert-danger'> Sorry You Can't To Browsing This Page Directly </div> ";
				redirecthome($themsg);
				echo "</div>";
			}

				echo "</div>";

			 } elseif($do=='Edit') {  

			// Edit profile

			$user = (isset($_GET['userid']) && is_numeric($_GET['userid']))? intval($_GET['userid']):0;
			

			$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
			$stmt->execute(array($user));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();

			if($count > 0){ ?>
				<h1 class="text-center">Edit Member</h1>

				<div class="container">
					
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="userid" value="<?php echo $user ?>" />
						<!--start Username-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Username</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="username" class="form-control" value="<?php echo $row['Username']?>" autocomplete="off" required="required"/>
							</div>
						</div>
						<!--End Username -->

						<!--start Password-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Password</label>
							<div class="col-sm-10 col-md-6">
								<input type="hidden" name="oldpassword" value="<?php echo $row['Password']?>" />
								<input type="password" name="newpassword" class="form-control" placeholder="Leave blank if you Don't want to change" autocomplete="new-password" />
							</div>
						</div>
						<!--End Password -->

						<!--start Email-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Email</label>
							<div class="col-sm-10 col-md-6">
								<input type="email" name="email" value="<?php echo $row['Email'] ?>"class="form-control" required="required"/>
							</div>
						</div>
						<!--End Email -->
						<!--start Full Name-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Full Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="full" value="<?php echo $row['FullName'] ?>"class="form-control" required="required"/>
							</div>
						</div>
						<!--End Full Name --> 
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
				echo "<h1 class='text-center'>Update Member</h1>";
		    	echo "<div class='container'>";

				$id       =$_POST['userid'];
				$username =$_POST['username'];
				$email    =$_POST['email'];
				$full     =$_POST['full'];

				$pass= empty($_POST['newpassword'] ) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
				
				//vaildate the form

				$formErrors =array();

				if (strlen($username) < 4) {
					
					$formErrors[] = 'User name Can\'t Be Less than <strong>4 characters </strong>';
				}

				if (strlen($username) > 20) {
					
					$formErrors[] = 'User name Can\'t Be More than  <strong>20 characters</strong>';
				}

				if (empty($username)) {
					
					$formErrors[] = 'User name Can\'t Be <strong> Empty</strong>';
				}

				if (empty($email)) {
					
					$formErrors[] = 'Email Can\'t Be <strong> Empty</strong>';
				}
				if (empty($full)) {
					
					$formErrors[] = 'full Name Can\'t Be <strong> Empty</strong>';
				}

				foreach ($formErrors as $error) {
					echo  '<div class= "alert alert-danger">' .$error .'</div>' ;
				}

				//check if no error

				if (empty($formErrors)) {

					$stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
					$stmt2->execute(array($username,$id));
					$rows = $stmt2->fetch();
					$count = $stmt2->rowCount();

					if ($count == 1) {
						
						
						$themsg =  '<div class= "alert alert-danger">Sorry User already Exit</div>' ;
					    redirecthome($themsg,'back');

					}else{

					
							// Update in DB
							
							$stmt = $con->prepare("UPDATE users SET Username = ? ,Email = ?, FullName = ? ,Password =? WHERE UserID = ?");
							$stmt->execute(array($username,$email,$full,$pass,$id));

							$themsg = '<div class= "alert alert-success">' . $stmt->rowCount() . " row Update </div>";
							redirecthome($themsg,'back');
					
						}

				}

				

				echo "</div>";

			}else{

			    echo "<div class='container'>";
				$themsg = "<div class='alert alert-danger'> you can't to browsing that directly </div> ";
				redirecthome($themsg);
				echo "</div>";
			}

		}elseif($do=='Delete'){

			//Delete member page

			echo "<h1 class='text-center'>Delete Member</h1>";
		    echo "<div class='container'>";
		    
		    // check user id exit and number
			$user = (isset($_GET['userid']) && is_numeric($_GET['userid']))? intval($_GET['userid']):0;
			

			
			$count = checkitem("UserID","users",$user);

			if($count > 0){
				
				$stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
				$stmt->bindparam(":zuser",$user);
				$stmt->execute();
				$themsg = '<div class= "alert alert-success">' . $stmt->rowCount() . " row Deleted </div>";

				redirecthome($themsg);
				
			echo "</div>";
			}else{

				echo "<div class='container'>";
				$themsg = "<div class='alert alert-danger'> This Id Not Exit </div> ";
				redirecthome($themsg);
				echo "</div>";
				
			}

		}elseif($do=='Activate'){

			//Activate member page

			echo "<h1 class='text-center'>Activate Member</h1>";
		    echo "<div class='container'>";

		    // check user id exit and number
			$user = (isset($_GET['userid']) && is_numeric($_GET['userid']))? intval($_GET['userid']):0;
			

			
			$count = checkitem("UserID","users",$user);

			if($count > 0){
				
				    $stmt = $con->prepare("UPDATE users SET RegStatus = 1  WHERE UserID = ?");
					$stmt->execute(array($user));

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