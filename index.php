<?php
session_start();
?>

<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
        <script>
            function ask(anchor){
                var conf=confirm("Do you want to delete?");
                if(conf) 
                window.location=anchor.attr("href");
            }
        </script>
    </head>
    
    <body>

<?php include_once 'nav.php'; ?>
	
		<div class="container">
			<div class="row">
                
			<?php
			
				if(isset($_SESSION['user']))
				{
                    
                    echo <<<_END
                        <h2 class="display-5">Work Log</h2>
                        <form method="get" class="form-inline" action="index.php">
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-5"><input type="date" name="start_date" placeholder="Start Date"></div>
                                    <div class="form-group col-md-5"><input type="date" name="end_date" placeholder="End Date"></div>
                                    <div class="col"><input type="submit" class="btn btn-primary"></div>
                                </div>
                            </div>
                        </form>
_END;

if(isset($_GET['msg']) && $_GET['msg']!=''){
    $msg = $_GET['msg'];
    echo<<<_END
<div class="col-lg-12">
    <div class="alert alert-primary" role="alert">
$msg
</div>
</div>
_END;
}

echo <<<_END
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
                    
                    if(isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date']!='' && $_GET['end_date']!='')
                    {
                        $start_date = $_GET['start_date'];
                        $end_date = $_GET['end_date'];
                        
                        $q = "SELECT * FROM logs WHERE cast(doe as date) between '$start_date' and '$end_date' AND is_deleted=0 ORDER BY doe DESC LIMIT 50";
                    }
                    else if(isset($_GET['start_date']) && $_GET['start_date']!=''){
                        $start_date = $_GET['start_date'];
                        $q = "SELECT * FROM logs WHERE cast(doe as date)='$start_date' AND is_deleted=0 ORDER BY doe DESC LIMIT 50";
                    }
                    else{
                        $q = "SELECT * FROM logs WHERE is_deleted=0 ORDER BY doe DESC LIMIT 50";
                    }
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
                        
                        $link = 'report_viewer.php?area=' . $area .' &date=' . explode(' ',$doe)[0];
                        
                        echo <<<_END
                        <tr>
                            <td>$sid</td>
                            <td><a href="$link">$doe</a></td>
                            <td>$sitename ($location)</td>
                            <td>$activity</td>
                            <td>$people</td>
                            <td>$status</td>
                            <td>$dou</td>
                            <td><a href="log_resource.php?logid=$sid">Add Resource Usage</a> | <a href="log_output.php?logid=$sid">Add Output</a> |
                             <a href="log_asset.php?logid=$sid">Add Asset Usage</a> | <a href="log_estimate_output.php?logid=$sid">Estimate Output</a> | <a href="log_estimate_resource.php?logid=$sid">Estimate Resource Usage</a> | <a onclick='javascript:ask($(this));return false;' href="delete.php?table=logs&return=index&rid=$sid">Delete</a>
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