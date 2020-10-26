<?php
session_start();
function getDimensionValue($db, $table, $gid, $name)
{
    $q = "SELECT * FROM $table WHERE id=$gid";
    $r = mysqli_query($db, $q);

    $res = mysqli_fetch_assoc($r);

    $value = $res[$name];

    return $value;
}
if (isset($_SESSION['user'])) {
    include_once 'db.php';


    echo <<<_END
    <html>

    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css" />
      
    </head>
<body>
_END;
    include_once 'nav.php';
    echo <<<_END
   
        <div class="container">
_END;
    if (isset($_GET['msg']) && $_GET['msg'] != '') {
        $msg = $_GET['msg'];
        echo <<<_END
        <div class="col-lg-8">
        <div class="alert alert-success alert-dismissible" role="alert">
                <b>$msg</b>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        </div>
_END;
    }
    echo <<<_END
<div class="row">
                <div class="col-lg-12" id="report">
                    <h3 class="mb-4">Customer Delivery Preview</h3>
                        <form action="customer_delivery_preview.php" method="get">
                        <div class="row">
                            <div class="col-lg">
                                <input type="date" class="form-control" name="start_date">
                            </div>
                            <div class="col-lg">
                                <input type="date" class="form-control" name="end_date">
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Show Preview</button>
                    </form>
                </div>
_END;
    if (isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date'] != '' && $_GET['end_date'] != '') {
        $start_date = mysqli_real_escape_string($db, $_GET['start_date']);
        $end_date = mysqli_real_escape_string($db, $_GET['end_date']);
        echo <<<_END
                <div class="col-lg-12 mb-4">
                    <h2 class="mb-4"></h2>
                    <div class="table-responsive">
                        <table id="table" class="table table-striped">
_END;

        echo <<<_END
                            <!-- <h4 class="mb-4">Deliveries Left: <b> </b></h4> -->
                            
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Customer</th>
                                    <th>Milktype</th>
                                    <th>Delivered Quantity</th>
                                    <th>Modify</th>
                                    <th>Action</th>    
                                </tr>
                                </thead>
                                <tbody>
_END;
        $sn=0;
        $q = "SELECT * from customer_delivery_log where cast(dod as date)>='$start_date' and cast(dod as date)<='$end_date' and is_deleted=0 order by cid";
        $r = mysqli_query($db, $q);
        while ($res = mysqli_fetch_assoc($r)) {
            $sn=$sn+1;
            $id = $res['id'];
            $cid =  $res['cid'];
            $csid =  $res['csid'];
            $cid_name = getDimensionValue($db, 'customer', $res['cid'], 'fname').' '.getDimensionValue($db,'customer',$res['cid'],'lname');
            $csid_milktype = getDimensionValue($db, 'customer_subscription', $res['csid'], 'milktype');
            $delivered_qty = $res['delivered_qty'];
            $sub_qty = $res['qty'];
            echo <<<_END
            <tr>
            <td>$sn</td>
            <td>$cid_name</td>
            _END;

            if($csid_milktype == 1) {
                echo <<<_END
                     <td class="mt-4">Cow Milk</td>
_END;
            } 
            elseif($csid_milktype == 2) {
                echo <<<_END
                    <td class="mt-4">Sahiwal Milk</td> 
_END;
            } 
            elseif($csid_milktype == 3) {
                echo <<<_END
                    <td class="mt-4">Buffalo Milk</td>
_END;
            }
            echo <<<_END
                    <form  action="customer_delivery_update.php" method="post">
                    <td class="text-primary">$delivered_qty</td>
                    <td>    
                    <input type="text" name="dlqty" value="$delivered_qty" class="form-control">
                    <input type="hidden" name="id_hide" value="$id" class="form-control">
                    <input type="hidden" name="start_date" value="$start_date" class="form-control">
                    <input type="hidden" name="end_date" value="$end_date" class="form-control">
                    <input type="hidden" name="cid" value="$cid" class="form-control">
                    </td>
                        <td> 
                        <button type="submit" name="update" class="btn  btn-outline-primary btn-block">Update</button>          
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
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
} else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
?>