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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
        <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
          });
        </script>
        <script>
        $(function(){
        setTimeout(function(){
        $('#success').hide('blind',{},400);
        },4000);
        });
        </script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css"/>
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
    <div class="alert alert-primary" id="success" role="alert">
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
                <div class="col-lg-12">
                    <h2>Purchases</h2>
                    <form action="purchases_add.php" method="post">
                        
						<div class="form-group">
							<label for="particular">Date of Purchase</label>
							<input type="date" name="dop" class="form-control">
						</div>
                        <div class="form-row">
                            <div class="col">
                                <label for="particular">Delivery Status</label>
                                <select class="form-control" name="delivery_status">
                                    <option value="0">Undelivered</option>
                                    <option value="1">Delivered</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="particular">Payment Status</label>
                                <select class="form-control" name="payment_status">
                                    <option value="0">Un-Paid</option>
                                    <option value="1">Paid</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="leaseduntil">Vendor</label>
                            <select name="vendor" class="form-control">
                                <option value=''>--Select vendor--</option>
_END;

$q = 'SELECT * FROM vendor WHERE is_deleted=0';
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r)){
    $vid = $res['id'];
    $vname = $res['name'];
    echo <<<_END
    <option value="$vid">$vname</option>
_END;
}

echo <<<_END
                            </select>
                        </div>
						<button type="submit" class="btn btn-primary">Add Purchase</button>
					</form>
                </div>
                
                <div class="col-lg-12 mb-4">
                <h4 class="text-center mb-4">Purchase Details</h4>
                    <div class="table-responsive">
                        <table id="table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Purchase Date</th>
                                    <th>Delivery</th>
                                    <th>Vendor</th>
                                    <th>Delivery Status</th>
                                    <th>Payment Status</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

$q = "SELECT * FROM purchases WHERE is_deleted=0 ORDER BY id DESC";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sn = $res['id'];
    $dop = $res['dop'];
    $dop=date("d/m/Y",strtotime($dop));
    $dod = $res['dod'];
    $vendorid = getDimensionValue($db,'vendor',$res['vendorid'],'name');
    $delivery_status = $res['delivery_status'];
    
    if($delivery_status == 0){$delivery_status = 'Undelivered';}
    else if($delivery_status == 1){$delivery_status = 'Delivered';}
    
    $payment_status = $res['payment_status'];
    
    if($payment_status == 0){$payment_status = 'Un-Paid';}
    else if($payment_status == 1){$payment_status = 'Paid';}
    
   


    $status = $res['status'];
    
    if($status == 0){$status = 'Placed';}
    else if($status == 1){$status = 'Completed';}
    else if($status == 2){$status = 'Cancelled';}

    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$dop</td>
        <td>$dod</td>
        <td>$vendorid</td>
        <td>
_END;
        if($delivery_status=='Undelivered'){
            echo <<<_END
        $delivery_status
        <a href="update.php?table=purchases&rid=$sn&ds=$delivery_status&return=purchases" data-toggle="tooltip" data-placement="top" title="For mark delivery as delivered"><span class="fa fa-edit"></span></a> 
_END;
        }
        else{
            echo <<<_END
            $delivery_status
_END;
        }
        echo <<<_END
        </td>
        <td>
_END;
        if($payment_status=='Un-Paid'){
            echo <<<_END
            $payment_status
            <a href="update.php?table=purchases&rid=$sn&ps=$payment_status&return=purchases"><span data-toggle="tooltip"   title="For mark payment as paid" class="d-inline-block fa fa-edit"></span></a> 
_END;
        }
        else{
        echo <<<_END
            $payment_status
_END;
        }
        echo <<<_END
        </td>
        <td>
_END;
        if($payment_status=='Paid' && $delivery_status=='Delivered'){
            if($status=='Placed'){
        echo <<<_END
        $status
        <a href="update.php?table=purchases&rid=$sn&ps=$payment_status&ds=$delivery_status&s=$status&return=purchases"><span data-toggle="tooltip" data-placement="top" title="For mark status as completed" class="fa fa-edit"></span></a>
_END;
        }
        else{
            echo <<<_END
            $status
_END;
        }
    }
    else{
        echo <<<_END
        $status
_END;
    }
       
        echo <<<_END
        </td>
        <td>
         <a href="purchases_additem.php?pid=$sn">Add items</a> | <a href="delete.php?table=purchases&rid=$sn&return=purchases">Delete</a></td>
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
