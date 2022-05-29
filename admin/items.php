<?php
	
	ini_set('display_errors','Off');
	error_reporting(E_ALL);
	// Items Page
	//==============================================================
	ob_start(); // output buffering start 
    session_start();

    $pagetitle = 'Items';

	if (isset($_SESSION['username'])) {
		include 'init.php';


	$do = isset($_GET['do']) ?  $_GET['do']: 'Manage';




	if ($do=='Manage') {
		

			$stmt = $con->prepare("SELECT items.* , categories.Name As Category_Name ,users.Username FROM items
								   INNER JOIN categories ON categories.ID = items.Cat_ID
								   INNER JOIN users ON users.UserID = items.Member_ID
								   ORDER BY Item_ID DESC
								   ");
			$stmt->execute();
			$items = $stmt->fetchAll();
			if(!empty($items)){

		   ?>

			<h1 class="text-center">Manage Items</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table manage-item text-center table table-bordered">
							<tr>
								<td>#ID</td>
								<td>Name</td>
								<td>Image</td>
								<td>Description</td>
								<td>Price</td>
								<td>Adding Date</td>
								<td>Category</td>
								<td>Username</td>
								<td>Control</td>
							</tr>
							
								<?php 
								foreach($items AS $item){

									echo "<tr>";
										echo "<td>" . $item['Item_ID'] ."</td>";
										echo "<td>" . $item['Name'] ."</td>";
										echo "<td>";
											if (empty($item['imgitem'])) {
												echo "<img src='upload/item/defualt.jpg' alt=''/>";
											}else{

												echo "<img src='upload/item/" . $item['imgitem'] ."' alt=''/>";
											}
										echo "</td>";
										echo "<td><textarea disabled>" . $item['Description'] ."</textarea></td>";
										echo "<td>" . $item['Price'] ."</td>";
										echo "<td>" . $item['Add_Date'] ."</td>";
										echo "<td>" . $item['Category_Name'] ."</td>";
										echo "<td>" . $item['Username'] ."</td>";
										echo "<td class='control'>
												<a href='items.php?do=Edit&itemid=".$item['Item_ID'] ."'class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
												<a href='items.php?do=Delete&itemid=".$item['Item_ID'] ."'class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
													if ($item['Approve']==0) {
													
													echo "<a href='items.php?do=Approve&itemid=".$item['Item_ID'] ."'class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
												}
											 echo "</td>";

									echo "</tr>";
								}
									?>
								
							   
							
							
						</table>
					</div>
					<a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus "></i> Add Item</a>
				</div>


			<?php }else{?>
			<div class="container">
				<div class="nice-message">There's No Record To Show</div>
				<a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus "></i> Add Item</a>
			</div>

		<?php  }?>
		<?php

	    } elseif($do=='Add') {

			?>

			<h1 class="text-center">Add New Item</h1>

				<div class="container">
					
					<form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
						<!--start Name-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Name</label>
							<div class="col-sm-10 col-md-6">
								<input 
								type="text"
								name="name" 
								class="form-control"   
								required="required"
								placeholder="Name Of The Item"/>
							</div>
						</div>
						<!--End Name -->
						<!--start Description-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Description</label>
							<div class="col-sm-10 col-md-6">
								<input 
								type="text"
								name="description" 
								class="form-control"   
								required="required"
								placeholder="Description Of The Item"/>
							</div>
						</div>
						<!--End Description -->
						<!--start Price-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Price</label>
							<div class="col-sm-10 col-md-6">
								<input 
								type="text"
								name="price" 
								class="form-control"  
								required="required"
								placeholder="Price Of The Item"/>
							</div>
						</div>
						<!--End Price -->
						<!--start Country_Made-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Country</label>
							<div class="col-sm-10 col-md-6">
								<input 
								type="text"
								name="country" 
								class="form-control"   
								required="required"
								placeholder="Country of Made  "/>
							</div>
						</div>
						<!--End Country_Made -->
						<!--start Status-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Status</label>
							<div class="col-sm-10 col-md-6">								
									<select name="status" >
										<option value="0">....</option>
										<option value="1">New</option>
										<option value="2">Like New</option>
										<option value="3">Used</option>
										<option value="4">Very old</option>
									</select>
							</div>
						</div>
						<!--End Status -->

						<!--start member field-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Member</label>
							<div class="col-sm-10 col-md-6">								
									<select name="member" >
										<option value="0">....</option>
										<?php
											
											$allusers = getAllFrom("*","users","","","UserID");
											foreach ($allusers as $user) {
												echo "<option value='".$user['UserID'] ."'>".$user['Username']."</option>";

											}
										?>
									</select>
							</div>
						</div>
						<!--End member fields -->

						<!--start Category field-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Category</label>
							<div class="col-sm-10 col-md-6">								
									<select name="category" >
										<option value="0">....</option>
										<?php											
											$allcats = getAllFrom("*","categories","where parent = 0","","ID");
											foreach ($allcats as $cat) {
												echo "<option value='".$cat['ID'] ."'>".$cat['Name']."</option>";
												$childcats = getAllFrom("*","categories","where parent = {$cat['ID']}","","ID");
												foreach ($childcats as $child) {
													echo "<option value='".$child['ID'] ."'>--".$child['Name']."</option>";
												}
											}
										?>
									</select>
							</div>
						</div>
						<!--End Category fields -->

						<!--start  image-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Image</label>
							<div class="col-sm-10 col-md-6">
								<input type="file" name="imgitem" class="form-control" required="required"/>
							</div>
						</div>
						<!--End  image --> 
						<!--start tags -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> tags</label>
							<div class="col-sm-10 col-md-6">
								<input 
								type="text"
								name="tags" 
								class="form-control"   
								placeholder="Separate tags with comma (,)"/>
							</div>
						</div>
						<!--End tags -->


						<!--start Rating          // ============================== not  allow  for admin insert rating ==========
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Rating</label>
							<div class="col-sm-10 col-md-6">								
									<select name="rating" class="form-control" >
										<option value="0">....</option>
										<option value="1">*</option>
										<option value="2">**</option>
										<option value="3">***</option>
										<option value="4">****</option>
										<option value="5">*****</option>
									</select>
							</div>
						</div>
						End Rating -->


						<!--start submit-->
						<div class="form-group form-group-lg">
							
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Add Item" class="btn btn-primary btn-sm" />
							</div>
						</div>
						<!--End submit -->

					</form>

				</div>

		<?php

	}elseif($do=='Insert') {


			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo "<h1 class='text-center'>Insert Item</h1>";
				echo "<div class='container'>";

				//upload Variables

				$imgitem   = $_FILES['imgitem'];

				$imgitemName   = $_FILES['imgitem']['name'];
				$imgitemSize   = $_FILES['imgitem']['size'];
				$imgitemTmp    = $_FILES['imgitem']['tmp_name'];
				$imgitemType   = $_FILES['imgitem']['type'];

				// list allow file types

				$imgitemAllowExtension = array("jpeg","jpg","png","gif");
				
				// Get Extension from file

				$imgitemExtension = strtolower(end(explode('.',$imgitemName)));
				
				$name       =$_POST['name'];
				$desc       =$_POST['description'];
				$price      =$_POST['price'];
				$country    =$_POST['country'];
				$status     =$_POST['status'];				
				$member     =$_POST['member'];
				$cat        =$_POST['category'];
				$tags       =$_POST['tags'];

				
				//vaildate the form

				$formErrors =array();

				if (empty($name)) {
					
					$formErrors[] = ' name Can\'t Be <strong>Empty</strong>';
				}

				if (empty($desc)) {
					
					$formErrors[] = 'Description Can\'t Be <strong>Empty</strong>';
				}

				if (empty($price)) {
					
					$formErrors[] = 'Price Can\'t Be <strong>Empty</strong>';
				}
				if (empty($country)) {
					
					$formErrors[] = 'Country Can\'t Be <strong>Empty</strong>';
				}

				if ($status == 0) {
					
					$formErrors[] = ' you must choose the <strong>Status</strong>';
				}

				if ($member == 0) {
					
					$formErrors[] = ' you must choose the <strong>Member</strong>';
				}

				if ($cat == 0) {
					
					$formErrors[] = ' you must choose the <strong>Category</strong>';
				}
				if (! empty($imgitemName) && ! in_array($imgitemExtension, $imgitemAllowExtension)) {
					
					$formErrors[] = 'This Extension Not<strong> Allow</strong>';
				}

				if (empty($imgitemName) ) {
					
					$formErrors[] = 'File Can\'t Be<strong> Empty</strong>';
				}

				if ( $imgitemSize > 4194304) {
					
					$formErrors[] = 'File Can\'t be larger than <strong> 4MB</strong>';
				}

				foreach ($formErrors as $error) {
					$themsg =  '<div class= "alert alert-danger">' .$error .'</div>' ;
					
				}
				//check if no error

				if (empty($formErrors)) {

					$imgg = rand(0,10000000) . '_' . $imgitemName;

					move_uploaded_file($imgitemTmp,"upload\item\\" . $imgg );

					// Insert in DB
				
						$stmt = $con->prepare("INSERT INTO 
												items (Name,Description,Price,Country_Made,Status,Add_Date,Cat_ID,Member_ID,imgitem,tags)
												VALUES (:zname,:zdesc,:zprice,:zcountry,:zstatus,now(),:zcat,:zmember,:zimgg,:ztags)");
						$stmt->execute(array(
							'zname'    => $name,
							'zdesc'    => $desc,
							'zprice'   => $price,
							'zcountry' => $country,
							'zstatus'  => $status,
							'zmember'  => $member,
							'zcat'     => $cat,
							'zimgg'     => $imgg,
							'ztags'    => $tags

						));

						$themsg = '<div class= "alert alert-success">' . $stmt->rowCount() . " row Inserted </div>";

						redirecthome($themsg,'back');
					
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

	}elseif($do=='Edit') {

			// check item Id id number and found

			$itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid']))? intval($_GET['itemid']):0;
			

			$stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
			$stmt->execute(array($itemid));
			$item = $stmt->fetch();
			$count = $stmt->rowCount();

			if($count > 0){ ?>
				<h1 class="text-center">Edit Item</h1>

				<div class="container">
					
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="itemid" value="<?php echo $itemid ?>" />
						<!--start Name-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Name</label>
							<div class="col-sm-10 col-md-6">
								<input 
								type="text"
								name="name" 
								class="form-control"   
								required="required"
								placeholder="Name Of The Item"
								value="<?php echo $item['Name']?>"
								/>
							</div>
						</div>
						<!--End Name -->
						<!--start Description-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Description</label>
							<div class="col-sm-10 col-md-6">
								<input 
								type="text"
								name="description" 
								class="form-control"   
								required="required"
								placeholder="Description Of The Item"
								value="<?php echo $item['Description']?>"
								/>
							</div>
						</div>
						<!--End Description -->
						<!--start Price-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Price</label>
							<div class="col-sm-10 col-md-6">
								<input 
								type="text"
								name="price" 
								class="form-control"  
								required="required"
								placeholder="Price Of The Item"
								value="<?php echo $item['Price']?>"
								/>
							</div>
						</div>
						<!--End Price -->
						<!--start Country_Made-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Country</label>
							<div class="col-sm-10 col-md-6">
								<input 
								type="text"
								name="country" 
								class="form-control"   
								required="required"
								placeholder="Country Of Made"
								value="<?php echo $item['Name']?>"
								/>
							</div>
						</div>
						<!--End Country_Made -->
						<!--start Status-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Status</label>
							<div class="col-sm-10 col-md-6">								
									<select name="status" >
										<option value="0">....</option>
										<option value="1" <?php if ($item['Status'] == 1) { echo 'selected';}?> >New</option>
										<option value="2" <?php if ($item['Status'] == 2) { echo 'selected';}?>>Like New</option>
										<option value="3" <?php if ($item['Status'] == 3) { echo 'selected';}?>>Used</option>
										<option value="4" <?php if ($item['Status'] == 4) { echo 'selected';}?>>Very old</option>
									</select>
							</div>
						</div>
						<!--End Status -->

						<!--start member field-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Member</label>
							<div class="col-sm-10 col-md-6">								
									<select name="member" >
										<?php
											$stmt = $con->prepare("SELECT * FROM users");
											$stmt->execute();
											$users =$stmt->fetchAll();
											foreach ($users as $user) {
												echo "<option value='".$user['UserID'] ."'";
												if ($item['Member_ID'] == $user['UserID']) { echo 'selected';}
												echo ">".$user['Username']."</option>";

											}
										?>
									</select>
							</div>
						</div>
						<!--End member fields -->

						<!--start Category field-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Category</label>
							<div class="col-sm-10 col-md-6">								
									<select name="category" >
										<?php
											$stmt2 = $con->prepare("SELECT * FROM categories");
											$stmt2->execute();
											$cats =$stmt2->fetchAll();
											foreach ($cats as $cat) {
												echo "<option value='".$cat['ID'] ."'";
												if ($item['Cat_ID'] == $cat['ID']) { echo 'selected';}
												echo ">".$cat['Name']."</option>";

											}
										?>
									</select>
							</div>
						</div>
						<!--End Category field -->
						<!--start tags -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> tags</label>
							<div class="col-sm-10 col-md-6">
								<input 
								type="text"
								name="tags" 
								class="form-control"   
								placeholder="Separate tags with comma (,)"
								value="<?php echo $item['tags']?>"
								/>

							</div>
						</div>
						<!--End tags -->

						<!--start submit-->
						<div class="form-group form-group-lg">
							
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!--End submit -->

					</form>

					<?php

							// related to comment

								$stmt = $con->prepare("SELECT 
															comments.*,users.username AS Member FROM comments

															INNER JOIN users ON users.UserID = comments.user_id
															WHERE item_id = ?
															");
								$stmt->execute(array($itemid));
								$rows = $stmt->fetchAll();

								if (!empty($rows)) {
									
								
							 ?>

								<h1 class="text-center">Manage [<?php echo $item['Name']?>] Comments</h1>
										<div class="table-responsive">
											<table class="main-table text-center table table-bordered">
												<tr>
													<td>Comment</td>
													<td>User Name</td>
													<td>Added Date</td>
													<td>Control</td>
												</tr>
												
								<?php 
								foreach($rows AS $row){

									echo "<tr>";
										echo "<td>" . $row['comment'] ."</td>";
										echo "<td>" . $row['Member'] ."</td>";
										echo "<td>" . $row['comment_date'] ."</td>";
										echo "<td>
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
					
				<?php }?>

				</div>

		<?php 

		}else{

			    echo "<div class='container'>";
				$themsg = "<div class='alert alert-danger'> there no such ID </div> ";
				redirecthome($themsg);
				echo "</div>";
			
		}

	 

	}elseif($do=='Update'){

			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				echo "<h1 class='text-center'>Update Item</h1>";
		    	echo "<div class='container'>";

		    	$id         =$_POST['itemid'];
		    	$name       =$_POST['name'];
				$desc       =$_POST['description'];
				$price      =$_POST['price'];
				$country    =$_POST['country'];
				$status     =$_POST['status'];	
				$cat        =$_POST['category'];			
				$member     =$_POST['member'];
				$tags       =$_POST['tags'];
				

				//vaildate the form

				$formErrors =array();

				if (empty($name)) {
					
					$formErrors[] = ' name Can\'t Be <strong>Empty</strong>';
				}

				if (empty($desc)) {
					
					$formErrors[] = 'Description Can\'t Be <strong>Empty</strong>';
				}

				if (empty($price)) {
					
					$formErrors[] = 'Price Can\'t Be <strong>Empty</strong>';
				}
				if (empty($country)) {
					
					$formErrors[] = 'Country Can\'t Be <strong>Empty</strong>';
				}

				if ($status == 0) {
					
					$formErrors[] = ' you must choose the <strong>Status</strong>';
				}

				if ($member == 0) {
					
					$formErrors[] = ' you must choose the <strong>Member</strong>';
				}

				if ($cat == 0) {
					
					$formErrors[] = ' you must choose the <strong>Category</strong>';
				}

				foreach ($formErrors as $error) {
					$themsg =  '<div class= "alert alert-danger">' .$error .'</div>' ;
					
				}
				//check if no error

				if (empty($formErrors)) {

					// Update in DB
				
						
					$stmt = $con->prepare("UPDATE Items SET 
												Name = ? ,
												Description = ?,
												Price = ? ,
												Country_Made =? ,
												Status =? ,
												Cat_ID = ?,
												Member_ID =?,
												tags =?
						WHERE Item_ID = ?");
					$stmt->execute(array($name,$desc,$price,$country,$status,$cat,$member,$tags,$id));

					$themsg = '<div class= "alert alert-success">' . $stmt->rowCount() . " row Update </div>";
					redirecthome($themsg,'back');
					
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


	}elseif($do=='Delete') {

			//Delete Item

			echo "<h1 class='text-center'>Delete Item</h1>";
		    echo "<div class='container'>";
		    
		    // check item id exit and number
			$item = (isset($_GET['itemid']) && is_numeric($_GET['itemid']))? intval($_GET['itemid']):0;
			

			
			$count = checkitem("Item_ID","items",$item);

			if($count > 0){
				
				$stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zitem");
				$stmt->bindparam(":zitem",$item);
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

	}elseif($do=='Approve') {



			//Approve  Item

			echo "<h1 class='text-center'>Approve Item</h1>";
		    echo "<div class='container'>";

		    // check Item id exit and number
			$itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid']))? intval($_GET['itemid']):0;
			

			
			$count = checkitem("Item_ID","items",$itemid);

			if($count > 0){
				
				    $stmt = $con->prepare("UPDATE items SET Approve = 1  WHERE Item_ID = ?");
					$stmt->execute(array($itemid));

					$themsg = '<div class= "alert alert-success">' . $stmt->rowCount() . " Record Approve </div>";
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

ob_end_flush(); //release output
?>