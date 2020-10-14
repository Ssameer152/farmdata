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
            $q = "SELECT cast(start_date as date) as sd,id,qty,cid,is_active,milktype,delivery_time from customer_subscription WHERE id='$mid' and is_deleted=0";
            $r = mysqli_query($db,$q);
            
            $res = mysqli_fetch_assoc($r);
            
            $db_stdate = $res['sd'];
            $db_qty = $res['qty'];   
            $db_milktype=$res['milktype']; 
            $db_deliverytime=$res['delivery_time'];        
        }
        else
        {
            $db_stdate = '';
            $db_qty = '';
            $db_milktype='';
            $db_deliverytime='';
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
_END;
if(isset($_GET['msg']) && $_GET['msg']!=''){
    $msg = $_GET['msg'];
    echo<<<_END
<div class="col-lg-6">
    <div class="alert alert-primary" role="alert">
$msg
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
</div>
_END;
} 
            echo <<<_END
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
            <div class="form-group">
                        <label for="milktype">Milk Type</label>
                        <select class="form-control" name="milktype">
_END;
                    if($db_milktype==1){
                    echo <<<_END
                                    <option value="1" selected="selected">Cow Milk</option>
                                    <option value="2">Sahiwal Milk</option>
                                    <option value="3">Buffalo Milk</option>
_END;
                    }
                        elseif($db_milktype==2){
                            echo <<<_END
                                    <option value="2" selected="selected">Sahiwal Milk</option>
                                    <option value="1">Cow Milk</option>
                                    <option value="3">Buffalo Milk</option>
_END;
                        }
                        elseif($db_milktype==3){
                            echo <<<_END
                                    <option value="3" selected="selected">Buffalo Milk</option>
                                    option value="2">Sahiwal Milk</option>
                                    <option value="1">Cow Milk</option>
_END;
                        }
                        else {
                            echo <<<_END
                                    <option value="1">Cow Milk</option>
                                    <option value="2">Sahiwal Milk</option>
                                    <option value="3">Buffalo Milk</option>
_END;
                        }
                            echo <<<_END
                                </select>
                    </div>
                    <div class="form-group">
                        <label for="dltime">Delivery Time</label>
                        <select class="form-control" name="dltime">
_END;
                        if($db_deliverytime==1){
                            echo <<<_END
                        <option value="1" selected="selected">Morning</option>
                        <option value="2">Evening</option>
_END;
                        }
                        elseif($db_deliverytime==2){
                            echo <<<_END
                        <option value="2" selected="selected">Evening</option>
                        <option value="1">Morning</option>
_END;
                        }
                        else {
                            echo <<<_END
                            <option value="1">Morning</option>
                            <option value="2">Evening</option>
_END;
                        }
                        echo <<<_END
                        </select>
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

$q = "SELECT * FROM customer WHERE id='$cid' and is_deleted=0";
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
        <div class="col-lg-8">
        <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Start date</th>
                                    <th>Quantity</th>
                                    <th>Milk Type</th>
                                    <th>Delivery Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
_END;
    $q="SELECT id,cast(start_date as date) as sd,qty,milktype,delivery_time from customer_subscription where cid='$cid' and is_deleted=0";
    $r=mysqli_query($db,$q);
    while($res=mysqli_fetch_assoc($r)){
        $sn=$res['id'];
        $dt=$res['sd'];
        $date=date("d-m-Y", strtotime($dt));
        $qty=$res['qty'];
        $mt = $res['milktype'];
        $deliverytime=$res['delivery_time'];
        if($deliverytime==1){$deliverytime='Morning';}
        else if($deliverytime==2){$deliverytime='Evening';}
        if($mt == 1){$mt = 'Cow Milk';}
        else if($mt == 2){$mt = 'Sahiwal Milk';}
        else if($mt == 3){$mt = 'Buffalo Milk';}
        echo <<<_END
        <tr>
        <td>$sn</td>
        <td>$date</td>
        <td>$qty</td>
        <td>$mt</td>
        <td>$deliverytime</td>
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
