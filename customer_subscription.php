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
    if(isset($_GET['cid']) && $_GET['cid']){
        $cid=$_GET['cid'];

        if(isset($_GET['id']) && $_GET['id']!=''){
            $mid = $_GET['id'];
            $q = "SELECT cast(start_date as date) as sd,id,qty,cid,is_active from customer_subscription WHERE id='$mid' and is_deleted=0";
            $r = mysqli_query($db,$q);
            
            $res = mysqli_fetch_assoc($r);
            
            $db_stdate = $res['sd'];
            $db_qty = $res['qty'];            
        }
        else
        {
            $db_stdate = '';
            $db_qty = '';
        }
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
                    <h2>Customer Subscription</h2>
                    <form action="customer_subscription_add.php" method="post">
                    <div class="form-group">
                    <label for="startdate">Start Date</label>
_END;
                    if($db_stdate==''){
                        echo <<<_END
                    <input type="date" name="sdate" class="form-control">
_END;
                    }
                    else{
                        echo <<<_END
                        <input type="date" name="sdate" value="$db_stdate" class="form-control">  
_END;
                    }
                    echo <<<_END
                </div>
                <div class="form-group">
                <label for="Quantity">Quantity</label>
_END;
                    if($db_qty==''){
                        echo <<<_END
                <input type="text" name="qty" class="form-control">
_END;
                    }
                    else{
                        echo <<<_END
                        <input type="text" name="qty" value="$db_qty" class="form-control">
_END;
                    }
                    echo <<<_END
            </div>
                    <input type="hidden" name="cid" value="$cid"/>
_END;
                    if(isset($mid)){
                    echo <<<_END
                    <input type="hidden" name="mid" value="$mid">
_END;
}
                    echo <<<_END
						<button type="submit" class="btn btn-primary">Add Subscription</button>
                    </form>
                </div>

                <div class="col-lg-6">
                <div class="table-responsive">
                    <h2>For Customer : #$cid</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Customer Name</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
_END;

$q = "SELECT * FROM customer WHERE is_deleted=0";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sn = $res['id'];
    $Name= $res['fname'] .' '.$res['lname'];
   $address=$res['address'];
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$Name</td>
        <td>$address</td>
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
        <div class="container">
        <div class="row">
        <div class="col-lg-7">
        <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Start date</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
_END;
    $q="SELECT id,cast(start_date as date) as sd,qty from customer_subscription where cid='$cid' and is_deleted=0";
    $r=mysqli_query($db,$q);
    while($res=mysqli_fetch_assoc($r)){
        $sn=$res['id'];
        $dt=$res['sd'];
        $date=date("d-m-Y", strtotime($dt));
        $qty=$res['qty'];
        echo <<<_END
        <tr>
        <td>$sn</td>
        <td>$date</td>
        <td>$qty</td>
        <td><a href="customer_subscription.php?cid=$cid&id=$sn">Modify</a> | <a href="delete.php?table=customer_subscription&return=customer_subscription&rid=$sn&cid=$cid">Delete</a></td>
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
    else {
        $msg = "please fill all fields";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=customer.php?msg=$msg'>
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
