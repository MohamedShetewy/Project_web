<?php

    ob_start();// output buffering start 

    session_start();
	if (isset($_SESSION['username'])) {

		$pagetitle = 'Dashboard';
		include 'init.php';
		
		// ---------------------Start Dashboard page -------------------
		$numUsers = 5 ; // get latest user display
		$latestUsers = getlatest("*","users","UserID",$numUsers);

		$numItems = 6 ; // get latest Item display
		$latestItems = getlatest("*","items","Item_ID",$numItems);

		$numcomments = 4 ;


		?>

		<div class="container home-stats text-center">
			<h1>Dashboard</h1>

			<div class="row">
				<div class="col-md-3">
					<div class="stat st-members">
						<i class="fa fa-users"></i>
						<div class="info">	
							Total Members
							<span><a href="members.php"><?php echo countitem("UserID","users"); ?></a></span>				
						</div>	
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-pending">
						<i class="fa fa-user-plus"></i>
						<div class="info">
							Pending Members
							<span><a href="members.php?do=Manage&page=pending"><?php echo checkitem("RegStatus","users",0); ?></a></span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-items">
						<i class="fa fa-tag"></i>
						<div class="info">
							Total Items
							<span><a href="items.php"><?php echo countitem("Item_ID","items"); ?></a></span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-comments">
						<i class="fa fa-comments"></i>
						<div class="info">
								Total Comments
								<span><a href="comments.php"><?php echo countitem("c_id","comments"); ?></a></span>
						</div>
					</div>
				</div>
			</div>

		</div>

		<div class="container latest">
			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-users"></i>	latest <?php echo $numUsers;?> Registerd Users
							<span class="toggle-info pull-right">
								<i class="fa fa-plus fa-lg"></i>
							</span>
						</div>
						<div class="panel-body">
							<ul class="list-unstyled latest-users">
								<?php 
								if(!empty($latestUsers)){
								 foreach ($latestUsers as $user) {
								 	
								 	echo '<li>' . $user['Username']. '<a href="members.php?do=Edit&userid='.$user['UserID'].'">
								 
								 	<span class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit</span></a>';
								 	if ($user['RegStatus']==0) {
													
													echo "<a href='members.php?do=Activate&userid=".$user['UserID'] ."'class='btn btn-info pull-right activate'><i class='fa fa-user'></i> Activate</a>";
												}
								 	echo '</li>';
								 }
								}else{

									echo 'There\'s no Record To Show';
								}

								?>
							</ul>
						</div>	
					</div>
				</div>
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-users"></i>	latest <?php echo $numItems;?> Items
							<span class="toggle-info pull-right">
								<i class="fa fa-plus fa-lg"></i>
							</span>
						</div>
						<div class="panel-body">
							
							<ul class="list-unstyled latest-users">
								<?php 
								if(!empty($latestItems)){
								 foreach ($latestItems as $item) {
								 	
								 	echo '<li>' . $item['Name']. '<a href="items.php?do=Edit&itemid='.$item['Item_ID'].'">
								 
								 	<span class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit</span></a>';
								 	if ($item['Approve']==0) {
													
													echo "<a href='items.php?do=Approve&itemid=".$item['Item_ID'] ."'class='btn btn-info pull-right activate'><i class='fa fa-check'></i> Approve</a>";
												}
								 	echo '</li>';
								 }
								}else{

									echo 'There\'s no Record To Show';
								}

								?>
							</ul>
						</div>	
					</div>
				</div>

			</div>

			
				
				<!--==============================comment=====================================-->
				<div class="row">
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-comments-o"></i>							
							Latest <?php echo $numcomments;?>	 Comment
							<span class="toggle-info pull-right">
								<i class="fa fa-plus fa-lg"></i>
							</span>
						</div>
						<div class="panel-body">
								<?php 


										$stmt = $con->prepare("SELECT 
										comments.*,users.username AS Member FROM comments
										INNER JOIN users ON users.UserID = comments.user_id
										ORDER BY c_id DESC
										LIMIT $numcomments
										");
										$stmt->execute();
										$rows = $stmt->fetchAll();

										if(!empty($rows)){

										foreach ($rows as $row) {
											echo '<div class="comment-box">';
											echo '<span class="member-n">' . $row['Member'] . '</span>';
											echo '<p class="member-c">' . $row['comment'] . '</p>';
											echo '</div>';
										}
										
										}else{

									echo 'There\'s no Record To Show';
								}

								?>
						</div>	
					</div>
				</div>
				
			</div>
		</div>


		<?php

		// ---------------------End Dashboard page -------------------

		include $tpl . "footer.php";

    }else{

    	header('location: index.php');
		exit();

    }

    ob_end_flush();
    
    ?>