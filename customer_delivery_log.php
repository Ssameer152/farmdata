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
    if(isset($_GET['csid']) && $_GET['csid']!='' && isset($_GET['cid']) && $_GET['cid']!='' )
    {
        $csid=$_GET['csid'];
        $cid=$_GET['cid'];

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
                    <h2>Customer Delivery Log</h2>
_END;
        $q="SELECT fname,lname from customer where id='$cid' and is_deleted=0";
        $r=mysqli_query($db,$q);
        while($res=mysqli_fetch_assoc($r)){
            $Name=$res['fname'] .' '.$res['lname'];
                    echo <<<_END
                    <h5>Customer : $Name </h5>
                    <form action="customer_delivery_log_ap.php" method="post">
                        <div class="form-group">
                            <label for="qty">Quantity</label>
                            <input type="text" name="qty" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="dlqty">Delivered Quantity</label>
                            <input type="text" name="dlqty" class="form-control">
                        </div>
                        <input type="hidden" name="csid" value="$csid">
                        <input type="hidden" name="cid" value="$cid">
						<button type="submit" class="btn btn-primary">Mark delivered</button>
                    </form>
_END;
                }
                echo <<<_END
                </div>
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
_END;

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
    else {
        $msg = "please fill all fields";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=customer_subscription.php?msg=$msg'>
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
