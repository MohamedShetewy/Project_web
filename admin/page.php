<?php


	ob_start(); // output buffering start 
    session_start();

    $pagetitle = '';

	if (isset($_SESSION['username'])) {
		include 'init.php';


	$do = isset($_GET['do']) ?  $_GET['do']: 'Manage';




	if ($do=='Manage') {

		echo "welcome you on page Manage";
		echo "<br>";
		echo '<a href="page.php?do=Add">click to move to add page +</a>';

	} elseif($do=='Add') {

		echo "welcome you on page Add";

	}elseif($do=='Insert') {

		echo "welcome you on page Add";

	}elseif($do=='Edit') {

		echo "welcome you on page Add";

	}elseif($do=='Update'){

		echo "welcome you on page Insert";
	}elseif($do=='Delete') {

		echo "welcome you on page Add";

	}elseif($do=='Activate') {

		echo "welcome you on page Add";

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