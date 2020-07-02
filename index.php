<html>
    <head>
        <title>FarmDB</title>
        
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    
    <body>
        <nav class="navbar navbar-dark navbar-expand-sm fixed-top bg-success">
		<div class="container">
			<a class="navbar-brand" href="#">FarmDB</a>
			<div class="collapse navbar-collapse" id="Navbar">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active"><a class="nav-link" href="#"><span class="fa fa-home fa-lg"></span> Home</a></li>
					<li class="nav-item"><a class="nav-link" href="./aboutus.html"><span class="fa fa-info fa-lg"></span> About</a></li>
					<li class="nav-item"><a class="nav-link" href="#"><span class="fa fa-list fa-lg"></span> Menu</a></li>
					<li class="nav-item"><a class="nav-link" href="./contactus.html"><span class="fa fa-address-card fa-lg"></span> Contact</a></li>
				</ul>
				<span class="navbar-text">
					<a href="logout.php">
						<span class="fa fa-sign-in"></span> Logout
					</a>
				</span>
			</div>
		</div>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#Navbar"><span class="navbar-toggler-icon"></span></button>
		</nav>
		
		<header class="jumbotron"></header>
	
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<h2>Login</h2>
					<form action="login.php" method="post">
						<div class="form-group">
							<label for="exampleInputEmail1">Email address</label>
							<input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
							<small id="emailHelp" class="form-text text-muted">Example: username@example.com</small>
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1">Password</label>
							<input type="password" name="pword" class="form-control" id="exampleInputPassword1">
						</div>
						<button type="submit" class="btn btn-primary">Login</button>
					</form>
				</div>
			</div>
		</div>

        
        <script src="js/jquery.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>