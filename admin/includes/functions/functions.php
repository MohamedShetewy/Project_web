<?php


//function of  Get ALL Recored 

function getAllFrom($field,$table,$where = NULL ,$and = NULL,$orderfield,$ordering = "DESC"){

	global $con;

	//$sql = $where == NULL ? '' : $where;

	$getAll =$con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
	$getAll->execute(); 
	$rows = $getAll->fetchAll();

	return 	$rows;
}

// function desiplay title

function gettitle(){

	global $pagetitle;

	if (isset($pagetitle)) {
		
		echo $pagetitle;
		
	}else{

		echo "default";
	}
}

//function of redirect of any Msg [Error or success ] found

function redirecthome($themsg,$url= null,$seconds = 3){

	if ($url === null) {
		
		$url='index.php';
		$link = 'Home page';
	}else{

		if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !=='') {

			$url=$_SERVER['HTTP_REFERER'];
			$link ='previous page';
		}else{

			$url='index.php';
			$link = 'Home page';
		}

	}

	echo  $themsg;
	echo "<div class='alert alert-info'> You Will Be Redirected To ".$link." After ".$seconds." Seconds</div>";
	header("refresh:$seconds;url=$url");
	exit();
}

// function to check exit item in DB

function checkitem($select,$from,$value){

	global $con;
	$statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
	$statement->execute(array($value));
	$count = $statement->rowCount();

	return $count;
}

// function to count Number of item in DB


function countitem($item,$table){

	global $con;
	$stmt2 =$con->prepare("SELECT COUNT($item) FROM $table");
	$stmt2->execute();
	return $stmt2->fetchColumn();
}

//function of  Get latest Recored [item , user]

function getlatest($select,$table,$order,$limit = 5){

	global $con;
	$getstmt =$con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
	$getstmt->execute(); 
	$row = $getstmt->fetchAll();

	return 	$row;
}

