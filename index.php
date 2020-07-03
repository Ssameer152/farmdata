<?php
session_start();
?>

<html>
    <head>
        <title>FarmDB</title>
        
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    
    <body>

<?php include_once 'nav.php'; ?>
	
		<div class="container">
			<div class="row">
			<?php
			
				if(isset($_SESSION['user']))
				{
					echo <<<_END
					<div class="col-lg-6">
						Add logs of day to day activities...
					</div>
_END;
				}
				else
				{
				echo <<<_END
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
_END;
				}

			?>
			</div>
		</div>

        
<?php
include_once 'foot.php';
?>
    </body>
</html>