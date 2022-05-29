
   <nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
		<a class="navbar-brand" href="dashboard.php"><?php echo lang('HOME_ADMIN'); ?></a>
    </div>
    <div class="collapse navbar-collapse" id="app-nav">
	<!--  navbar list    -->
	<ul class="nav navbar-nav">
		<li data-value='features'><a href="categories.php">Categories</a></li>
		<li data-value='features'><a href="items.php">Items</a></li>
		<li data-value='features'><a href="members.php">Members</a></li>
		<li data-value='features'><a href="comments.php">Comments</a></li>
    
	</ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo$_SESSION['username']; ?><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="../index.php">Visit Shop</a></li>
            <li ><a href="members.php?do=Edit&userid=<?php echo $_SESSION['ID']; ?>">Edit Profile</a></li>
            <li ><a href="#">Setting</a></li>
            <li role="separator" class="divider"></li>
            <li ><a href="logout.php">Logout</a></li>
            
          </ul>
        </li>
      </ul>
      <!--  end  navbar list    -->   
    </div>
  </div>
</nav>