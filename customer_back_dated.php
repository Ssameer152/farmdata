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
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>   
            
            <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css"/>  
        </head>
_END;
    include_once 'nav.php';
    echo <<<_END
    <body>
        <div class="container">
        <div class="row">
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
    if (!isset($_GET['start_date'])) {
        $start_date = NULL; 
    } elseif (isset($_GET['start_date'])) {
        $start_date = $_GET['start_date'];
    }
    echo <<<_END
                <div class="col-lg-12 mb-4">
                    <div class="table-responsive">
                   
                        <table id="table" class="table table-striped">
_END;
    echo <<<_END
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>   
                                        <th>Customer</th>
                                        <th>Milktype</th>
                                        <th>Subscription Quantity</th>
                                        <th>Delivered Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                        <tbody>
_END;
    
    $q = "SELECT * from customer_subscription where is_deleted=0 order by cid";
    $r = mysqli_query($db, $q);
    while ($res = mysqli_fetch_assoc($r)) {
        $id = $res['id'];
        $cid =  $res['cid'];
        $cid_name = getDimensionValue($db, 'customer', $res['cid'], 'fname');
        $milktype =  $res['milktype'];
        $qty = $res['qty'];
        $delivery_time = $res['delivery_time'];

        echo <<<_END
                <tr>
                <form action="customer_back_dated_ap.php" method="post">
                <td style="width:7%; height:20%;"><input type="date" class="form-control" value="$start_date" name="sDate" ></td>
_END;

        if($delivery_time == 1) {
            echo <<<_END
                        <td class="mt-4">Morning</td>
_END;
        } 
        elseif($delivery_time == 2) {
            echo <<<_END
                <td class="mt-4">Evening</td>
_END;
        }
        echo <<<_END
                        <td>$cid_name</td>
_END;

        if($milktype == 1) {
            echo <<<_END
                        <td class="mt-4">Cow Milk</td>
_END;
        } 
        elseif($milktype == 2) {
            echo <<<_END
                        <td class="mt-4">Sahiwal Milk</td> 
_END;
        } 
        elseif($milktype == 3) {
            echo <<<_END
                        <td class="mt-4">Buffalo Milk</td>
_END;
        }
        echo <<<_END
            
                            <td class="text-primary">$qty<input type="hidden" name="sub_qty" value="$qty" class="form-control"></td>
                            <td> 
                            <input type="text" name="dlqty" value="$qty" class="form-control">
                            <input type="hidden" name="delivery_time" value="$delivery_time" class="form-control"> 
                            <input type="hidden" name="id_hide" value="$id" class="form-control">
                            <input type="hidden" name="cid" value="$cid" class="form-control">
                            </td>
                            <td>
                            <input type="submit" name="add" value="Add Back Dated" class="btn btn-outline-dark btn-block">      
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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}