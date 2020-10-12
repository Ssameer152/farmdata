<?php
session_start();
function getDimensionValue($db,$table,$gid,$name){
    $q = "SELECT * FROM $table WHERE id=$gid";
    $r = mysqli_query($db,$q);
    
    $res = mysqli_fetch_assoc($r);
    
    $value = $res[$name];
    
    return $value;
}
if(isset($_SESSION['user'])){
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
                    <h2 class="h2">Inventory</h2><br>
                    <form action="stock_report.php" method="get">
                            <div class="row">
                            <div class="col-lg">
                                <input type="date" class="form-control" name="start_date">
                            </div>
                            <div class="col-lg">
                            <input type="date" class="form-control" name="end_date">
                        </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Show inventory</button>
                    </form>
                </div>
                </div>
                </div>
_END;
    if(isset($_GET['start_date']) && $_GET['start_date']!='' && isset($_GET['end_date']) && $_GET['end_date']!='')
    {
        $start_date = mysqli_real_escape_string($db,$_GET['start_date']);
        $end_date = mysqli_real_escape_string($db,$_GET['end_date']);
    echo <<<_END
        <div class="container">
        <div class="row">
        <div class="col-lg-12">
        <div class="table table-responsive">
        <table class="table table-bordered">
        <thead>
        <tr>
        <th>Sno.</th>
        <th>Resource</th>
        <th>Consumed</th>
        <th>Purchased</th>
        <th>Produced</th>
        <th>Balance</th>
        </tr>
        </thead>
        <tbody>
_END;
    $q="SELECT * from resources where is_deleted=0";
    $r=mysqli_query($db,$q);
    while($res=mysqli_fetch_assoc($r)){
        $id=$res['id'];
        $resource=$res['resourcename'];
        $unit=$res['unit'];
        echo <<<_END
        <tr>
        <td>$id</td>
        <td>$resource</td>     
_END;
        $q1="SELECT t.qty ,COALESCE(sum(t.qty),0) as q1 from (select qty from log_resource where resourceid='$id' and cast(doe as date)>='$start_date' and cast(doe as date)<='$end_date' and is_deleted=0) as t";
        $r1=mysqli_query($db,$q1);
        while($res1=mysqli_fetch_assoc($r1)){
            $consumed=$res1['q1'];
                echo <<<_END
                <td>$consumed $unit</td>
_END;
    }
        $q2="SELECT t.qty,COALESCE(sum(t.qty),0) as q2 from (select qty from purchase_items where resourceid='$id' and cast(doe as date)<='$start_date' and cast(doe as date)<='$end_date' and is_deleted=0) as t";
        $r2=mysqli_query($db,$q2);
        while($res2=mysqli_fetch_assoc($r2)){
            $purchase=$res2['q2'];
            echo <<<_END
            <td>$purchase $unit</td>
_END;
        }
        $q3="SELECT t.qty ,COALESCE(sum(t.qty),0) as q3 from (select qty from log_output where resourceid='$id' and cast(doe as date)>='$start_date' and cast(doe as date)<='$end_date' and is_deleted=0) as t";
        $r3=mysqli_query($db,$q3);
        while($res3=mysqli_fetch_assoc($r3)){
            $produced=$res3['q3'];
            $left=$purchase+$produced-$consumed;
            echo <<<_END
            <td>$produced $unit</td>
            <td>$left $unit</td>
            </tr>
_END;
        }
    }
    echo <<<_END
        
_END;
}
else {
    echo 'No items';
}
    
        
   
include_once 'foot.php';
    echo <<<_END
            </tbody>
        </table>
        </div>
        </div>
        </div>
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