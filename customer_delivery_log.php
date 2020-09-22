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
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
    </head>
    
    <body>    

<?php include_once 'nav.php';?>


		<div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2>Customer Delivery Log</h2>
<?php
        $q="SELECT * from customer_subscription where is_active=1 and is_deleted=0 and id not in (select csid from customer_delivery_log where cast(dod as date)=cast(current_timestamp() as date)) order by cid";
        $r=mysqli_query($db,$q); 
        while($res=mysqli_fetch_assoc($r)){
            $cid=$res['cid'];
        $q1="SELECT * from customer where is_deleted=0 and id='$cid'";
        $r1=mysqli_query($db,$q1);
        while($res1=mysqli_fetch_assoc($r1)){
            $Name=$res1['fname'] .' '.$res1['lname'];
                    echo <<<_END
                    <h5 class="mt-4">Customer : $Name </h5>
_END;
        }
                $qty=$res['qty'];
                $csid=$res['id'];
                $milktype=$res['milktype'];
                if($milktype==1){
                    echo <<<_END
                     <h6>Cow Milk</h6>
_END;
                    }
                elseif($milktype==2){
                    echo <<<_END
                    <h6>Sahiwal Milk</h6>
_END;
                }
                elseif($milktype==3){
                    echo <<<_END
                    <h6>HF Milk</h6>
_END;
                }

                    echo <<<_END
                    <form  action="customer_delivery_log_ap.php"  method="post">
                    <h5 class="mt-4 mb-4 text-primary">Quantity : $qty</h5>
                        <div class="form-group">
                            <label for="dlqty">Delivered Quantity</label>
                            <input type="text" name="dlqty" value="$qty" class="form-control">
                        </div>
                        <input type="hidden" name="qty" value="$qty">
                        <input type="hidden" name="csid" value="$csid">
                        <input type="hidden" name="cid" value="$cid">                  
						<button onclick="return confirm('Do you want to mark delivered?')" type="submit" class="btn btn-primary">Mark delivered</button>
                    </form>
_END;
}      
echo <<<_END
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
