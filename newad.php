<?php 

	ini_set('display_errors','Off');
	error_reporting(E_ALL);
	ob_start();	
	session_start();
	$pagetitle = 'Create New Item';
	include "init.php";
	if(isset($_SESSION['user'])){

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		

		

		$formErrors = array();


		

		$name       =filter_var($_POST['name'],FILTER_SANITIZE_STRING);
		$desc       =filter_var($_POST['description'],FILTER_SANITIZE_STRING);
		$price      =filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
		$country    =filter_var($_POST['country'],FILTER_SANITIZE_STRING);
		$status     =filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);				
		$cat        =filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);
		$tags       =filter_var($_POST['tags'],FILTER_SANITIZE_STRING);
		


		//upload Variables

		$imgitem       =filter_var($_FILES['imgitem'],FILTER_SANITIZE_STRING);

		$imgitemName   = $_FILES['imgitem']['name'];
		$imgitemSize   = $_FILES['imgitem']['size'];
		$imgitemTmp    = $_FILES['imgitem']['tmp_name'];
		$imgitemType   = $_FILES['imgitem']['type'];

		// list allow file types

		$imgitemAllowExtension = array("jpeg","jpg","png","gif");
		
		// Get Extension from file

		$imgitemExtension = strtolower(end(explode('.',$imgitemName)));

		if (strlen($name) < 4) {
			
			$formErrors[] = ' Item Title Must Be At Least 4 Characters';
		}
		if (strlen($desc) < 10) {
			
			$formErrors[] = ' Item Description Must Be At Least 10 Characters';
		}
		if (strlen($country) < 2) {
			
			$formErrors[] = ' Item Country Must Be At Least 2 Characters';
		}
		if (empty($price)) {
			
			$formErrors[] = 'Price Can\'t Be <strong>Empty</strong>';
		}
		if (empty($status)) {
			
			$formErrors[] = ' you must choose the <strong>Status</strong>';
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


				if (empty($formErrors)) {

					$imgg = rand(0,10000000) . '_' . $imgitemName;

					move_uploaded_file($imgitemTmp,"admin\upload\item\\" . $imgg );
					

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
							'zmember'  => $_SESSION['uid'],
							'zcat'     => $cat,
							'zimgg'    => $imgg,
							'ztags'    => $tags

						));

						if($stmt){

						$sucessMsg = 'Item Add' ;

						}
					
				}


	}
?>
<h1 class="text-center"><?php echo $pagetitle?></h1>
<div class="create-ad block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading"><?php echo $pagetitle?></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-8">		
						<form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">
							<!--start Name-->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label"> Name</label>
								<div class="col-sm-10 col-md-9">
									<input
									pattern=".{4,}"
									title="Item Title Must Be At Least 4 Characters"
									type="text"
									name="name" 
									class="form-control live"   
									required="required"
									placeholder="Name Of The Item"
									data-class=".live-name"
									/>
								</div>
							</div>
							<!--End Name -->
							<!--start Description-->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label"> Description</label>
								<div class="col-sm-10 col-md-9">
									<input 
									pattern=".{10,}"
									title="Item Description Must Be At Least 10 Characters"
									type="text"
									name="description" 
									class="form-control live"   
									required="required"
									placeholder="Description Of The Item"
									data-class=".live-desc"
									/>
								</div>
							</div>
							<!--End Description -->
							<!--start Price-->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label"> Price</label>
								<div class="col-sm-10 col-md-9">
									<input 
									type="text"
									name="price" 
									class="form-control live"  
									required="required"
									placeholder="Price Of The Item"
									data-class=".live-price"
									/>
								</div>
							</div>
							<!--End Price -->
							<!--start Country_Made-->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label"> Country</label>
								<div class="col-sm-10 col-md-9">
									<input 
									type="text"
									name="country" 
									class="form-control"   
									required
									placeholder="Country of Made  "/>
								</div>
							</div>
							<!--End Country_Made -->
							<!--start Status-->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label"> Status</label>
								<div class="col-sm-10 col-md-9">								
										<select name="status" required>
											<option value="0">....</option>
											<option value="1">New</option>
											<option value="2">Like New</option>
											<option value="3">Used</option>
											<option value="4">Very old</option>
										</select>
								</div>
							</div>
							<!--End Status -->


							<!--start Category field-->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label"> Category</label>
								<div class="col-sm-10 col-md-9">								
										<select name="category" required>
											<option value="0">....</option>
											<?php
												$cats= getAllFrom('*','categories','','','ID');
												foreach ($cats as $cat) {
													echo "<option value='".$cat['ID'] ."'>".$cat['Name']."</option>";

												}
											?>
										</select>
								</div>
							</div>

						<!--start  image-->
						<div class="form-group form-group-lg">
							<label class="col-sm-3 control-label"> Image</label>
							<div class="col-sm-10 col-md-9">
								<input type="file" name="imgitem" class="form-control" required="required"/>
							</div>
						</div>
						<!--End  image --> 
						<!--start tags -->
						<div class="form-group form-group-lg">
							<label class="col-sm-3 control-label"> tags</label>
							<div class="col-sm-10 col-md-9">
								<input 
								type="text"
								name="tags" 
								class="form-control"   
								placeholder="Separate tags with comma (,)"
								/>

							</div>
						</div>
						<!--End tags -->
							<!--start submit-->
							<div class="form-group form-group-lg">
								
								<div class="col-sm-offset-3 col-md-9">
									<input type="submit" value="Add Item" class="btn btn-primary btn-sm" />
								</div>
							</div>
							<!--End submit -->

						</form>
					</div>
					<div class="col-md-4">
						<div class="thumbnail item-box live-preview">
							<span class="price-tag">$
								<span class="live-price"> 0 </span>
							</span>
							<img class="img-responsive" src="admin/upload/item/o.png" alt="" />
							<div class="caption">
								<h3 class="live-name">Title</h3>
								<p class="live-desc">Description</p>
							</div>

						</div>
					</div>
				</div>

				<!--Start Looping through Errors-->

				<?PHP

					if (!empty($formErrors)) {
						
						foreach ($formErrors as $error) {
							
							echo '<div class="alert alert-danger">'. $error .'</div>';
						}
					}

					if (isset($sucessMsg)) {
				
						echo '<div class="alert alert-success">' . $sucessMsg .  '</div>';
					}
				?>

				<!--End Looping through Errors-->
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
