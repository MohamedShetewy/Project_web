<?php 
	ob_start();
	session_start();
	$pagetitle = 'login';
	$pagefooter = '';
	if (isset($_SESSION['user'])) {
	header('location: index.php');
    }

	include "init.php";

	//check request

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		if (isset($_POST['login'])) {

				$user = $_POST['username'];
				$pass = $_POST['password'];

				$hashedPass = sha1($pass);

				// check user found on database

				$stmt = $con->prepare("SELECT UserID,Username,Password FROM users WHERE Username = ? AND Password = ?");
				$stmt->execute(array($user,$hashedPass));
				$get = $stmt->fetch();
				$count = $stmt->rowCount();

				if($count > 0){
					$_SESSION['user'] = $user;
					$_SESSION['uid'] = $get['UserID'];
					header('location: index.php');
					exit();
				}

		}else{

			$formError = array();

			$username = $_POST['username'];
			$password = $_POST['password'];
			$password2 = $_POST['password2'];
			$email = $_POST['email'];

			if (isset($username)) {

				$filterUser = filter_var($_POST['username'],FILTER_SANITIZE_STRING);  // convert user name to string

				if (strlen($filterUser) < 4) {
					$formError[] = 'Username  Must be Larger Than  4 characters';
				}

			}

			if (isset($password) && isset($password2)) {

				if (empty($password)) {
					
					$formError[] = 'Sorry Password can\'t be Empty';
				}

				

				if ( sha1($password) !== sha1($password2) ) {
					
					$formError[] = 'Sorry Password Not Match';
				}

			}

			if (isset($email)) {

				$filterEmail = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);  

				if (filter_var($filterEmail,FILTER_VALIDATE_EMAIL) != true) {
					
					$formError[] = 'This Email Is Not Vaild';
				}

			}

			// add User in DB

			if (empty($formError)) {

					//check if user exit in DB

					$check = checkitem("Username","users",$username);

					if ($check == 1) {
						
						
						$formError[] =  'Sorry User already Exist' ;
					    

					}else{
						

					// Insert in DB
				
						$stmt = $con->prepare("INSERT INTO 
												users (Username,Password,Email,RegStatus,Date)
												VALUES (:zuser,:zpass,:zemail,0,now())");
						$stmt->execute(array(
							'zuser' => $username,
							'zpass' => sha1($password),
							'zemail' =>$email,
							
						));

						$sucessMsg = 'Congrats You Are Now Registerd';
				}
			}
		}
		
	}

 ?>

<div class="container login-page">
	<h1 class="text-center"><span class="sinb selected" data-class="login">Login</span> | <span  data-class="signup">Signup</span></h1>


	<!-- start sign in form -->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
		<div class="input-container">
			<input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type your username" required>
		</div>

		<div class="input-container">
		<input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your Password" required>
		</div>

		<input class="btn btn-primary btn-block" name="login" type="submit" value="Login" >
	</form>


	<!-- start signout in form-->
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
		<div class="input-container">
		<input pattern=".{4,}" title="Username Must be 4 chars" class="form-control" type="text" name="username" autocomplete="off" placeholder="Type your username" required />
		</div>

		<div class="input-container">
		<input minlength="4" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type a Complex Password " required />
		</div>

		<div class="input-container">
		<input minlength="4" class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Type Password Again " required />
		</div>

		<div class="input-container">
		<input class="form-control" type="email" name="email" placeholder="Type Vaild E-mail" required />
		</div>

		<input class="btn btn-success btn-block" name="signup" type="submit" value="Signup" >
	</form>

	<div class="the-errors text-center">
		
			<?php 
			if (!empty($formError)) {
				
				foreach ($formError as $error) {
					
					echo '<div class="msg error">' . $error .  '</div>';
				}
				
			}

			if (isset($sucessMsg)) {
				
				echo '<div class="msg success">' . $sucessMsg .  '</div>';
			}

			?>
		
	</div>

</div>

<?php include $tpl . "footer.php";
ob_end_flush();
?>