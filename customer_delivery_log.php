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
    ?>
<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css"/>
    </head>
    <body>    

<?php include_once 'nav.php';?>
		<div class="container">
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <h2 class="mb-4">Customer Delivery Log</h2>
                    <div class="table-responsive">
                    <table id="table" class="table table-striped">
                <?php
                $q1="SELECT count(*) as ct from customer_subscription where is_active=1 and is_deleted=0  and id not in (select csid from customer_delivery_log where cast(dod as date)=cast(current_timestamp() as date)) order by cid";
                $r1=mysqli_query($db,$q1);
                $res1=mysqli_fetch_assoc($r1);
                $deliveries=$res1['ct'];
                ?>
                    <h4 class="mb-4">Deliveries Left: <b><?php echo $deliveries;?></b></h4>
                    <thead>
                    <tr>
                    <th>Customer</th>
                    <th>Milktype-Time</th>
                    <th>Quantity</th>
                    <th>Delivered Quantity</th>
                    <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
<?php
        $q="SELECT * from customer_subscription where is_active=1 and is_deleted=0  and id not in (select csid from customer_delivery_log where cast(dod as date)=cast(current_timestamp() as date)) order by cid";
        $r=mysqli_query($db,$q); 
        while($res=mysqli_fetch_assoc($r)){
            $cid=$res['cid'];
        $q1="SELECT * from customer where is_deleted=0 and id='$cid'";
        $r1=mysqli_query($db,$q1);
        while($res1=mysqli_fetch_assoc($r1)){
            $Name=$res1['fname'] .' '.$res1['lname'];
                    echo <<<_END
                    <tr>
                    <td>$Name</td>
_END;
        }
                $qty=$res['qty'];
                $csid=$res['id'];
                $milktype=$res['milktype'];
                $deliverytime=$res['delivery_time'];
                
                if($milktype==1){
                    echo <<<_END
                     <td class="mt-4">Cow Milk - 
_END;
                    }
                else if($milktype==2){
                    echo <<<_END
                    <td class="mt-4">Sahiwal Milk - 
_END;
                }
                elseif($milktype==3){
                    echo <<<_END
                    <td class="mt-4">Buffalo Milk - 
_END;
                }
                if($deliverytime==1){
                    echo <<<_END
                     Morning</td>
_END;
                }
                elseif($deliverytime==2){
                    echo <<<_END
                     Evening</td>
_END;
                }
                    echo <<<_END
                    <form  action="customer_delivery_log_ap.php"  method="post">
                    <td class="text-primary">$qty</td>
                    <td>    
                    <input type="text" name="dlqty" value="$qty" class="form-control">
                    </td>
                    <input type="hidden" name="qty" value="$qty">
                        <input type="hidden" name="csid" value="$csid">
                        <input type="hidden" name="cid" value="$cid"> 
                        <td>        
                        <button onclick="return confirm('Do you want to mark delivered?')" type="submit" class="btn btn-primary">Mark delivered</button>
                        </td>
                    </form>
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
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> 
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
$('#table').DataTable();
});
</script> 
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
