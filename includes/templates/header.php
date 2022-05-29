<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title><?php gettitle(); ?></title>
		<link rel="stylesheet" type="text/css" href="<?php echo $css ;?>bootstrap.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ;?>font-awesome.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ;?>jquery-ui.css">		
		<link rel="stylesheet" type="text/css" href="<?php echo $css ;?>jquery.selectBoxIt.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ;?>animate.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css ;?>front.css">
	</head>

	<body>
		<div class="upper-bar">
			<div class="container">

				<?php
					if (isset($_SESSION['user'])) {?>

						<img class="my-image img-circle img-thumbnail" src="img.jpg" alt="" />
						<div class="btn-group my-info">				
							<span class="btn dropdown-toggle" data-toggle="dropdown"> 	
								<?php echo $sessionUser ?>
								<span class="caret"></span>
							</span>
							<ul class="dropdown-menu">
								<li><a href="profile.php">My Profile</a></li>
								<li><a href="newad.php">New Item</a></li>
								<li><a href="profile.php#my-ads">My Items</a></li>
								<li><a href="logout.php">Logout</a></li>
							</ul>

						</div>

						<?php
						

					}else{
						?>
						<a href="login.php">
							<span class="pull-right">Login/Siginup</span>
						</a>

					<?php }?>
			</div>
		</div>
	    <nav class="navbar navbar-inverse navbar-fixed-top">
	    <div class="container">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
			<a class="navbar-brand" href="index.php">Homepage</a>
	    </div>
	    <div class="collapse navbar-collapse" id="app-nav">
		<!--  navbar list    -->
		<ul class="nav navbar-nav navbar-right">
			
			<?php
			$allcat = getAllFrom("*","categories","where parent = 0","","ID","ASC");
		     foreach ( $allcat as $cat) {
			 echo  '<li data-value="features">
			 			<a href="categories.php?pageid=' . $cat['ID'] . '">
			 			' .$cat['Name'] .'
			 			</a>
			 		</li>';
		     }
 
			?>

	    
		</ul>
	      
		      <!--  end  navbar list    -->   
		    </div>
		  </div>
		</nav>