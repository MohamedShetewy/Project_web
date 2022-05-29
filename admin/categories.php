<?php

	//==========================Categories page =============================

	ob_start(); // output buffering start 
    session_start();

    $pagetitle = 'Categories';

	if (isset($_SESSION['username'])) {
		include 'init.php';


	$do = isset($_GET['do']) ?  $_GET['do']: 'Manage';




	if ($do=='Manage') {

		$sort = 'ASC';

		$sort_array = array('ASC','DESC');

		if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {

			$sort = $_GET['sort'];
		}

		$stmt = $con->prepare("SELECT * FROM categories where parent = 0 ORDER BY Ordering $sort");

		$stmt->execute();

		$rows = $stmt->fetchALL();

		if(!empty($rows)){
		?>


		<h1 class="text-center"> Manage Categories</h1>
		<div class="container categories">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-6"><i class='fa fa-edit'></i> Manage Categories</div>
						<div class="col-md-6 option pull-right">
							<i class='fa fa-sort'></i> Oredering:[

							<a class="<?php if($sort=='ASC'){echo 'active';}?>" href="?sort=ASC">Asc</a> |
							<a class="<?php if($sort=='DESC'){echo 'active';}?>" href="?sort=DESC">Desc</a> ]

							<i class='fa fa-eye'></i> View:[
							<span class="active" data-view="full">Full</span> |
							<span data-view="classic">Classic</span>]
						</div>
					</div>
				</div>
				<div class="panel-body">
					<?php 
					foreach ($rows as $cat) {
						echo "<div class='cat'>";
						echo "<div class='hidden-buttons'>";
							echo "<a href='?do=Edit&catid=".$cat['ID']." 'class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
							echo "<a href='?do=Delete&catid=".$cat['ID']." ' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
						echo "</div>";
						echo "<h3>" .$cat['Name'] . "</h3>";
						echo "<div class='full-view'>";
							echo "<p>"; if ($cat['Description'] == '') {
											echo "This Category has no Description";
										}else{
											echo $cat['Description']; 
										}
							echo "</p>";
							 if($cat['Visibility'] == 1){echo "<span class='glob visibility'><i class='fa fa-eye'></i> Hidden</span>";}
							 if($cat['Allow_Comment'] == 1){echo "<span class='glob commenting'><i class='fa fa-close'></i> Comment Disable</span>";}
							 if($cat['Allow_Ads'] == 1){echo "<span class='glob advertises'><i class='fa fa-close'></i> Ads Disable</span>";}
						 echo "</div>";

							 // Get Child Category
							$childcat = getAllFrom("*","categories","where parent = {$cat['ID']}","","ID","ASC");
							if (!empty($childcat)) {
								
								echo "<h4 class='child-head'>Child Category</h4>";
								echo "<ul class='list-unstyled child-cats'>";
								    foreach ( $childcat as $c) {
									 echo  "<li class='child-link'>
									 			<a href='?do=Edit&catid=".$c['ID']." '>" . $c['Name'] . "</a>
									 			<a href='?do=Delete&catid=".$c['ID']." ' class='show-delete confirm'>Delete</a>
									 		</li>";
								     }
							    echo "</ul>";
							 }		

						echo "</div>";
						

									
						echo "<hr>";
					}
					?>
				</div>
			</div>

			<a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i>Add New category</a>
		</div>

			<?php }else{?>
			<div class="container">
				<div class="nice-message">There's No Record To Show</div>
				<a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i>Add New category</a>
			</div>

		<?php  }?>

		<?php
	} elseif($do=='Add') {// Add Category page
			?>

			<h1 class="text-center">Add New Category</h1>

				<div class="container">
					
					<form class="form-horizontal" action="?do=Insert" method="POST">
						<!--start Name-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="name" class="form-control"  autocomplete="off" required="required" placeholder="Name Of The Category"/>
							</div>
						</div>
						<!--End Name -->

						<!--start Description-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Description</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="description" class="form-control" placeholder="Description The Category"/>
							</div>
						</div>
						<!--End Description -->

						<!--start Ordering-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Ordering</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="ordering" class="form-control"  placeholder="Number To Arrage The Category"/>
							</div>
						</div>
						<!--End Ordering -->
						<!--start Category type-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> parent?</label>
							<div class="col-sm-10 col-md-6">
								<select name="parent">
									<option value="0">None</option>
									<?php
										$catParent = getAllFrom("*","Categories","where parent = 0","","ID","ASC");
										foreach ($catParent as $cat) {
											echo '<option value="'. $cat['ID'] .'">' . $cat['Name'] .'</option>';
										}
									?>
								</select>
							</div>
						</div>
						<!--End Category type-->
						<!--start Visibility-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Visible</label>
							<div class="col-sm-10 col-md-6">
								<div>
									<input id="vis-yes" type="radio" name="visibility" value="0" checked />
									<label for="vis-yes">Yes</label>
								</div>
								<div>
									<input id="vis-no" type="radio" name="visibility" value="1" />
									<label for="vis-no">No</label>
								</div>
							</div>
						</div>
						<!--End Visibility --> 
						<!--start Allow_Comment-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Allow Commenting</label>
							<div class="col-sm-10 col-md-6">
								<div>
									<input id="com-yes" type="radio" name="commenting" value="0" checked />
									<label for="com-yes">Yes</label>
								</div>
								<div>
									<input id="com-no" type="radio" name="commenting" value="1" />
									<label for="com-no">No</label>
								</div>
							</div>
						</div>
						<!--End Allow_Comment --> 
						<!--start Allow_Ads-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Allow Ads</label>
							<div class="col-sm-10 col-md-6">
								<div>
									<input id="ads-yes" type="radio" name="ads" value="0" checked />
									<label for="ads-yes">Yes</label>
								</div>
								<div>
									<input id="ads-no" type="radio" name="ads" value="1" />
									<label for="ads-no">No</label>
								</div>
							</div>
						</div>
						<!--End Allow_Ads --> 
						<!--start submit-->
						<div class="form-group form-group-lg">
							
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!--End submit -->

					</form>

				</div>

		<?php

	}elseif($do=='Insert') {

		 //Insert Category page

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo "<h1 class='text-center'>Insert Category</h1>";
				echo "<div class='container'>";
				
				$name         =$_POST['name'];
				$desc         =$_POST['description'];
				$parent       =$_POST['parent'];
				$ordering     =$_POST['ordering'];
				$visibility   =$_POST['visibility'];
				$commenting   =$_POST['commenting'];
				$ads          =$_POST['ads'];
   			
				
				//vaildate the form

				

				if (!(empty($name))) {

					//check if category exit in DB

					$check = checkitem("Name","categories",$name);

					if ($check == 1) {
						
						
						$themsg =  '<div class= "alert alert-danger">Sorry Category already Exit</div>' ;
					    redirecthome($themsg,'back');

					}else{

					// Insert category info in DB
				
						$stmt = $con->prepare("INSERT INTO 
												categories(Name,Description,parent,Ordering,Visibility,Allow_Comment,Allow_Ads)
												VALUES (:zname,:zdesc,:zparent,:zordering,:zvisibility,:zcommenting,:zads)");
						$stmt->execute(array(
							'zname'        => $name,
							'zdesc'        => $desc,
							'zparent'	   => $parent,
							'zordering'    => $ordering,
							'zvisibility'  => $visibility,
							'zcommenting'  => $commenting,
							'zads'         => $ads
						));

						$themsg = '<div class= "alert alert-success">' . $stmt->rowCount() . " Record Inserted </div>";

						redirecthome($themsg,'back');
					}
				}else{

					echo "<div class='container'>";
					$themsg = "<div class='alert alert-danger'> Category name Can't Be <strong> Empty</strong> </div> ";
					redirecthome($themsg,'back');
					echo "</div>";
				}

			}else{

				echo "<div class='container'>";
				$themsg = "<div class='alert alert-danger'> Sorry You Can't To Browsing This Page Directly </div> ";
				redirecthome($themsg,'back');
				echo "</div>";
			}

				echo "</div>";

	}elseif($do=='Edit') {

		// Edit profile

			$catid = (isset($_GET['catid']) && is_numeric($_GET['catid']))? intval($_GET['catid']):0;
			

			$stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");
			$stmt->execute(array($catid));
			$cat = $stmt->fetch();
			$count = $stmt->rowCount();

			if($count > 0){ 
				?>

			<h1 class="text-center">Edit Category</h1>

				<div class="container">
					
					<form class="form-horizontal" action="?do=Update" method="POST">
					<input type="hidden" name="catid" value="<?php echo $catid ?>" />

						<!--start Name-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Category" value="<?php echo $cat['Name'];?>" />
							</div>
						</div>
						<!--End Name -->

						<!--start Description-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Description</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="description" class="form-control" placeholder="Description The Category" value="<?php echo $cat['Description'];?>"/>
							</div>
						</div>
						<!--End Description -->

						<!--start Ordering-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Ordering</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="ordering" class="form-control"  placeholder="Number To Arrage The Category" value="<?php echo $cat['Ordering'];?>"/>
							</div>
						</div>
						<!--End Ordering -->
						<!--start Category type-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> parent?</label>
							<div class="col-sm-10 col-md-6">
								<select name="parent">
									<option value="0">None</option>
									<?php
										$catParent = getAllFrom("*","Categories","where parent = 0","","ID","ASC");
										foreach ($catParent as $c) {
											echo '<option value="'. $c['ID'] .'" ';
											if ($c['ID'] == $cat['parent']) {
												echo "selected";
											}
											echo '>' . $c['Name'] .'</option>';
										}
									?>
								</select>
							</div>
						</div>
						<!--End Category type-->
						<!--start Visibility-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Visible</label>
							<div class="col-sm-10 col-md-6">
								<div>
									<input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility'] == 0){echo 'checked';}?>  />
									<label for="vis-yes">Yes</label>
								</div>
								<div>
									<input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['Visibility'] == 1){echo 'checked';}?>  />
									<label for="vis-no">No</label>
								</div>
							</div>
						</div>
						<!--End Visibility --> 
						<!--start Allow_Comment-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Allow Commenting</label>
							<div class="col-sm-10 col-md-6">
								<div>
									<input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0){echo 'checked';}?> />
									<label for="com-yes">Yes</label>
								</div>
								<div>
									<input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1){ echo 'checked';}?>/>
									<label for="com-no">No</label>
								</div>
							</div>
						</div>
						<!--End Allow_Comment --> 
						<!--start Allow_Ads-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label"> Allow Ads</label>
							<div class="col-sm-10 col-md-6">
								<div>
									<input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0){echo 'checked';}?>  />
									<label for="ads-yes">Yes</label>
								</div>
								<div>
									<input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1){echo 'checked';}?> />
									<label for="ads-no">No</label>
								</div>
							</div>
						</div>
						<!--End Visibility --> 
						<!--start Allow_Ads-->
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

	}elseif($do=='Update'){

		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				echo "<h1 class='text-center'>Update Category</h1>";
		    	echo "<div class='container'>";

				$id            =$_POST['catid'];
				$name          =$_POST['name'];
				$desc          =$_POST['description'];
				$parent        =$_POST['parent'];
				$ordering      =$_POST['ordering'];
				$visibility    =$_POST['visibility'];
				$commenting    =$_POST['commenting'];
				$ads           =$_POST['ads'];

				
					//vaildate the form

				

				if (!(empty($name))) {
	
							

							$stmt2 = $con->prepare("SELECT * FROM categories WHERE Name = ? AND ID != ?");
						    $stmt2->execute(array($name,$id));
						    $rows = $stmt2->fetch();
						    $count = $stmt2->rowCount();

						   if ($count == 1) {
							
							
							$themsg =  '<div class= "alert alert-danger">Sorry Category already Exit</div>' ;
						    redirecthome($themsg,'back');

						}else{

							// Update in DB
					
							$stmt = $con->prepare("UPDATE categories SET Name = ? ,Description = ?,parent = ?,Ordering = ? ,Visibility =?,Allow_Comment =? , Allow_Ads = ? WHERE ID = ?");
							$stmt->execute(array($name,$desc,$parent,$ordering,$visibility,$commenting,$ads,$id));


					

							$themsg = '<div class= "alert alert-success">' . $stmt->rowCount() . " Record Update </div>";

							redirecthome($themsg,'back');
					    }
			     	 }else{

					echo "<div class='container'>";
					$themsg = "<div class='alert alert-danger'> Category name Can't Be <strong> Empty</strong> </div> ";
					redirecthome($themsg,'back');
					echo "</div>";
				    }

			}else{

				echo "<div class='container'>";
				$themsg = "<div class='alert alert-danger'> Sorry You Can't To Browsing This Page Directly </div> ";
				redirecthome($themsg,'back');
				echo "</div>";
			}



	}elseif($do=='Delete') {

		    //Delete categories page

			echo "<h1 class='text-center'>Delete category</h1>";
		    echo "<div class='container'>";
		    
		    // check category id exit and number
			$cat = (isset($_GET['catid']) && is_numeric($_GET['catid']))? intval($_GET['catid']):0;
			

			
			$count = checkitem("ID","categories",$cat);

			if($count > 0){
				
				$stmt = $con->prepare("DELETE FROM categories WHERE ID = :zcat");
				$stmt->bindparam(":zcat",$cat);
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