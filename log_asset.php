<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';


if(isset($_GET['logid']) && $_GET['logid']!='')
{    
    $logid = $_GET['logid'];
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
                    <h2>Log Asset Usage</h2>
_END;


    echo <<<_END
                        <form action="log_asset_ap.php" method="post">
                        <div class="form-group">
                            <label for="person">Person</label>
                            <select name="person" class="form-control">
                                <option value="">--Select Person--</option>
_END;

$q = "SELECT * FROM people WHERE is_deleted=0";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sid = $res['id'];
    $rname = $res['fname'] . ' ' . $res['lname'];
    
    echo <<<_END
    <option value="$sid">$rname</option>
_END;

}

echo <<<_END
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="area">Asset</label>
                            <select name="area" class="form-control">
                                <option value="">--Select Asset--</option>
_END;

$q = "SELECT * FROM assets WHERE is_deleted=0";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sid = $res['id'];
    $rname = $res['assetname'];
    
    
    echo <<<_END
    <option value="$sid">$rname</option>
_END;

}

echo <<<_END
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Usage Time(6 mins = 0.1)</label>
                            <input type="text" name="qty" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="comments">Comments</label>
                            <input type="text" name="comments" class="form-control">
                        </div>
                        <input type="hidden" name="logid" value="$logid">
						<button type="submit" class="btn btn-primary">Add Log</button>
                    </form>
_END;


echo <<<_END
                </div>
<div class="col-lg-12">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Person</th>
                    <th>Asset</th>
                    <th>Total Usage</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
_END;

$q = "SELECT id,sum(usage_time) as qty,assetid,person FROM log_assets WHERE logid='$logid' GROUP BY assetid,person";
$r = mysqli_query($db,$q);

if(!$r){
    echo mysqli_error($db);
}

$sn = 0;
while($res = mysqli_fetch_assoc($r))
{
    $sid= $res['id'];
    $qty = $res['qty'];
    $rid = $res['assetid'];
    
    $q2 = "SELECT assetname FROM assets WHERE id='$rid'";
    $r2 = mysqli_query($db,$q2);
    
    $re2 = mysqli_fetch_assoc($r2);
    

    $resourcename  = $re2['assetname'];
    $sn = $sn + 1;
    
    $person = $res['person'];
    
    $q3 = "SELECT * FROM people WHERE id='$person'";
    $r3 = mysqli_query($db,$q3);
    
    $re3 = mysqli_fetch_assoc($r3);
    
    $fullname = $re3['fname'] . ' ' . $re3['lname'];
    
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$fullname</td>
        <td>$resourcename</td>
        <td>$qty</td>
        <td><a href="delete.php?table=log_assetst&rid=$sid&return=log_output.php?logid=$logid"><span class="fa fa-trash fa-lg"></a></td>
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
    $msg = "Please select a work log";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}


}
else
{
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

?>	
