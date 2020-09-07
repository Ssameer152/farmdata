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
                <div class="col-lg-12">
                    <h2>Transactions</h2>
                    <form action="areas_add.php" method="post">
						<div class="form-group">
							<label for="particular">Particular</label>
							<input type="text" name="sitename" class="form-control">
						</div>
                        <div class="form-row">
                            <div class="col">
                                <label for="particular">Amount Received</label>
                                <input type="text" class="form-control" value="0">
                            </div>
                            <div class="col">
                                <label for="particular">Amount Paid</label>
                                <input type="text" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="leaseduntil">Date of Transaction</label>
                            <input type="date" name="leased_until" class="form-control">
                        </div>
						<button type="submit" class="btn btn-primary">Add Transaction</button>
					</form>
                </div>
                
                <div class="col-lg-12">
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

$q = "SELECT areas.id,sitename,people.fname,people.lname FROM `areas` INNER JOIN people on people.id=areas.manager WHERE areas.is_deleted=0";
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
        <td><a href="delete.php?table=areas&rid=$sn&return=areas"><span class="fa fa-trash fa-lg"></a></td>
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
