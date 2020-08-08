<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
    echo <<<_END
<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
    </head>
    
    <body>    
_END;

include_once 'nav.php';

echo <<<_END

		<div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2>Designation</h2>
                    <form action="designation.php" method="post">
                        <div class="form-group">
                            <label for="designation">Title</label>
                            <input type="text" name="designation" class="form-control">
                        </div>
						<button type="submit" class="btn btn-primary">Add Designation</button>
                    </form>
                </div>
            </div>
			<div class="row">
                <div class="col-lg-6">
                    <h2>People</h2>
                    <form action="people_add.php" method="post">
						<div class="form-group">
							<label for="firstname">First Name</label>
							<input type="text" name="fname" class="form-control">
						</div>
						<div class="form-group">
							<label for="lastname">Last Name</label>
							<input type="text" name="lname" class="form-control">
						</div>
                        <div class="form-group">
							<label for="area">Email</label>
							<input type="email" name="email" class="form-control">
						</div>
                        <div class="form-group">
                            <label for="Manager">Designation</label>
                            <select class="form-control" name="desig">
                                <option value="">--Select Designation--</option>
_END;

$q = "SELECT * FROM designation WHERE is_deleted=0";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $desig = $res['desig'];
    $did = $res['id'];
    echo <<<_END
    <option value="$did">$desig</option>
_END;
}

echo <<<_END
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="rent">Mobile No.</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="joinedon">Joined On</label>
                            <input type="date" name="joined_on" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="pword" class="form-control">
                        </div>
						<button type="submit" class="btn btn-primary">Add User</button>
					</form>
                </div>
                
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

$q = "SELECT people.id,fname,lname,phone,d.desig FROM `people` INNER JOIN designation d ON people.designation=d.id WHERE people.is_deleted=0";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sn = $res['id'];
    $name = $res['fname'] . ' ' . $res['lname'];
    $desig = $res['desig'];
    $phone = $res['phone'];
    
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$name</td>
        <td>$desig</td>
        <td>$phone</td>
        <td><a href="delete.php?table=people&rid=$sn&return=people"><span class="fa fa-trash fa-lg"></a></td>
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
