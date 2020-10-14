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
                    <form action="stock_report23.php" method="get">
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
    if (isset($_GET['start_date']) && $_GET['start_date'] != '' && isset($_GET['end_date']) && $_GET['end_date'] != '') {
        $start_date = mysqli_real_escape_string($db, $_GET['start_date']);
        $end_date = mysqli_real_escape_string($db, $_GET['end_date']);
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
        $produced1 = 0;
        $consumed1 = 0;
        $purchase1 = 0;
        $q = "SELECT * from resources where is_deleted=0";
        $r = mysqli_query($db, $q);
        while ($res = mysqli_fetch_assoc($r)) {
            $id = $res['id'];
            $resource = $res['resourcename'];
            $unit = $res['unit'];
            echo <<<_END
        <tr>
        <td>$id</td>
        <td>$resource</td>  
        
        
_END;
            /* $q = "SELECT i.country AS group_by
     , COUNT(*)          AS item_count
     , SUM(i.price)      AS cost
     , SUM(p.sum_amount) AS earned
     , SUM(e.sum_amount) AS extra_earned
FROM  items i
LEFT  JOIN (
   SELECT item_id, SUM(amount) AS sum_amount
   FROM   payments
   GROUP  BY 1
   ) p ON p.item_id = i.id
LEFT  JOIN (
   SELECT item_id, SUM(amount) AS sum_amount
   FROM   extras
   GROUP  BY 1
   ) e ON e.item_id = i.id
GROUP BY 1";
// */

            /*SELECT a.resourceid,produced,consumed from (select resourceid,sum(qty) as produced from log_output where logid in (SELECT id FROM `logs` where cast(doe as date)<='2020-10-07' and is_deleted=0) and is_deleted=0 GROUP by resourceid) a 
            inner join (select resourceid,sum(qty) as consumed from log_resource where logid in (SELECT id FROM `logs` where cast(doe as date)<='2020-10-07' and is_deleted=0) and is_deleted=0 GROUP by resourceid ) b on a.resourceid=b.resourceid*/


            $q5 = "SELECT r.id, a.resourceid,b.resourceid,c.resourceid,sum(a.produced) as produced, sum(b.consumed) as con, sum(c.purchase) as pur  from resources r
            LEFT JOIN (select resourceid,sum(qty) as produced from log_output where logid in (SELECT id FROM `logs` where cast(doe as date)>='$start_date' and TIMESTAMP(cast(doe as date))<='$end_date' and is_deleted=0) and is_deleted=0 GROUP BY resourceid) a on r.id = a.resourceid
            LEFT JOIN (select resourceid,sum(qty) as consumed from log_resource where  logid in (SELECT id FROM `logs` where cast(doe as date)>='$start_date' and TIMESTAMP(cast(doe as date))<='$end_date' and is_deleted=0) and is_deleted=0 GROUP BY resourceid) b on r.id = b.resourceid
            LEFT JOIN (select resourceid,sum(qty) as purchase from purchase_items where TIMESTAMP(cast(doe as date))>='$start_date' and TIMESTAMP(cast(doe as date))<='$end_date' and is_deleted=0 GROUP BY resourceid) c on r.id = c.resourceid where id='$id'";

            $r1 = mysqli_query($db, $q5);

            while ($res1 = mysqli_fetch_assoc($r1)) {
                $produced = $res1['produced'];
                $consumed = $res1['con'];
                $purchase = $res1['pur'];

                $produced1 += $produced;
                $consumed1 += $consumed;
                $purchase1 += $purchase;
                $left = ($produced + $purchase) - $consumed;
                echo <<<_END
                <td>$consumed $unit</td>
_END;
                echo <<<_END
            <td>$purchase $unit</td>
_END;
                echo <<<_END
            <td>$produced $unit</td>
            <td>$left $unit</td>
            </tr>
            
_END;
            }
        }
        echo <<<_END
   
_END;
    } else {
        echo 'No items';
    }



    include_once 'foot.php';
    echo <<<_END
    
    <tr><td colspan='2' class='text-center'>Total :</td>
    <td>$consumed1</td>
    <td>$purchase1</td>
    <td>$produced1</td></tr>
            </tbody>
        </table>
        </div>
        </div>
        </div>
        </body>
        </html>
_END;
} else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
