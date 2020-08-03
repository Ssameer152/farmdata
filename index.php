<?php
session_start();
?>

<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
                    <h2>Work Log</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Start Date</th>
                                <th>Site Name</th>
                                <th>Activity</th>
                                <th>Authorizer</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
_END;
                    include_once 'db.php';
                    
                    $q = "SELECT * FROM logs ORDER BY doe DESC";
                    $r = mysqli_query($db,$q);
                    
                    while($res = mysqli_fetch_assoc($r))
                    {
                        $sid = $res['id'];
                        $area = $res['area'];
                        
                        $q2 = "SELECT * FROM areas WHERE id='$area'";
                        $r2 = mysqli_query($db,$q2);
                        
                        $re2 = mysqli_fetch_assoc($r2);
                        
                        $sitename = $re2['sitename'];
                        $location = $re2['location'];
                        
                        $activity = $res['activity'];
                        
                        $q3 = "SELECT * FROM activities WHERE id='$activity'";
                        $r3 = mysqli_query($db,$q3);
                        
                        $re3 = mysqli_fetch_assoc($r3);
                        
                        $activity = $re3['activity'];
                        
                        $people = $res['people'];
                        
                        $q4 = "SELECT * FROM people WHERE id='$people'";
                        $r4 = mysqli_query($db,$q4);
                        
                        $re4 = mysqli_fetch_assoc($r4);
                        
                        $people = $re4['fname'] . ' ' . $re4['lname'];
                        
                        $status = $res['status'];
                        
                        if($status == 0)
                        {
                            $status = "Open";
                        }
                        else if($status == 1)
                        {
                            $status = "Complete";
                        }
                        else if($status == 2)
                        {
                            $status = "Cancelled";
                        }
                        
                        $doe = $res['doe'];
                        $dou = $res['dou'];
                        
                        echo <<<_END
                        <tr>
                            <td>$sid</td>
                            <td>$doe</td>
                            <td>$sitename ($location)</td>
                            <td>$activity</td>
                            <td>$people</td>
                            <td>$status</td>
                            <td>$dou</td>
                            <td><a href="log_resource.php?logid=$sid">Add Resource Usage</a> | <a href="log_output.php?logid=$sid">Add Output</a> |
                             <a href="log_asset.php?logid=$sid">Add Asset Usage</a>
                            </td>
                        </tr>
_END;
                    }
					echo <<<_END
					</tbody>
					</table>
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