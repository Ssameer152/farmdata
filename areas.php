<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
    echo <<<_END
<html>
    <head>
        <title>FarmDB</title>
        
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    
    <body>    
_END;

include_once 'nav.php';

echo <<<_END

		<div class="container">
			<div class="row">
                <div class="col-lg-6">
                    <h2>Areas</h2>
                    <form action="areas_add.php" method="post">
						<div class="form-group">
							<label for="sitename">Site Name</label>
							<input type="text" name="sitename" class="form-control">
						</div>
						<div class="form-group">
							<label for="location">Location</label>
							<input type="text" name="location" class="form-control">
						</div>
                        <div class="form-group">
							<label for="area">Size</label>
							<input type="text" name="areasize" class="form-control" placeholder="sq. mtr.">
						</div>
                        <div class="form-group">
                            <label for="Manager">Manager</label>
                            <select class="form-control" name="manager">
                                <option value="">--Select Manager--</option>
_END;

$q = "SELECT people.id,fname,lname,phone,d.desig FROM `people` INNER JOIN designation d ON people.designation=d.id";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sn = $res['id'];
    $name = $res['fname'] . ' ' . $res['lname'];
    $desig = $res['desig'];
    $phone = $res['phone'];
    
    echo <<<_END
    <option value="$sn">$name - $phone ($dwsig)</option>
_END;
}

echo <<<_END
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="rent">Monthly Rent</label>
                            <input type="text" name="rent" placeholder="&#8377;" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="leaseduntil">Leased Until</label>
                            <input type="date" name="leased_until" class="form-control">
                        </div>
						<button type="submit" class="btn btn-primary">Add Area</button>
					</form>
                </div>
                
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Site Name</th>
                                    <th>Manager</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

$q = "SELECT areas.id,sitename,people.fname,people.lname FROM `areas` INNER JOIN people on people.id=areas.manager";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sn = $res['id'];
    $sitename = $res['sitename'];
    $fname = $res['fname'];
    $lname = $res['lname'];
    
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$sitename</td>
        <td>$fname $lname</td>
        <td><a href="#">Updates</a></td>
    </tr>
_END;
}

echo <<<_END
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        </div>

_END;

include_once 'foot.php';

echo <<<_END
    </body>
</html>
_END;

}
else
{
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

?>	
