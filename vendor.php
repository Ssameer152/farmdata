<?php
session_start();


function getDimensionValue($db,$table,$gid,$name){
    $q = "SELECT * FROM $table WHERE id=$gid";
    $r = mysqli_query($db,$q);
    
    $res = mysqli_fetch_assoc($r);
    
    $value = $res[$name];
    
    return $value;
}


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
                <div class="col-lg-12">
                    <h2>Vendors</h2>
                    <form action="vendors_add.php" method="post">
                        
						<div class="form-group">
							<label for="particular">Name</label>
							<input type="text" name="vname" class="form-control">
						</div>
                        <div class="form-row">
                            <div class="col">
                                <label for="particular">Mobile No.</label>
                                <input type="text" class="form-control" name="mobile">
                            </div>
                            <div class="col">
                                <label for="particular">Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="leaseduntil">Contact Person</label>
                            <input type="text" name="cperson" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="leaseduntil">Address</label>
                            <input type="text" name="address" class="form-control">
                        </div>
						<button type="submit" class="btn btn-primary">Add Vendor</button>
					</form>
                </div>
                
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Mobile</th>
                                    <th>email</th>
                                    <th>Contact Person</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

$q = "SELECT * FROM vendor WHERE is_deleted=0 ORDER BY id DESC";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sn = $res['id'];
    $name = $res['name'];
    $address = $res['address'];
    $email = $res['email'];
    $phone = $res['phone'];
    $cperson = $res['contact_person'];
    
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$name</td>
        <td>$address</td>
        <td>$phone</td>
        <td>$email</td>
        <td>$cperson</td>
        <td><a href="delete.php?table=vendor&rid=$sn&return=vendor">Delete</a></td>
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
