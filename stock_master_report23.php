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

function getOpeningStock($db, $rid)
{


    $q = "SELECT qty, COALESCE(sum(qty),0) as consumed from log_resource WHERE logid in (select id from logs WHERE cast(doe as date)<cast(current_timestamp as date) and cast(doe as date)>='2020-10-01' and is_deleted=0) AND resourceid='$rid' and is_deleted=0";
    $r = mysqli_query($db, $q);
    $res = mysqli_fetch_assoc($r);
    echo $consumed = $res['consumed'];


    $q2 = "SELECT COALESCE(sum(qty),0) as produced from log_output WHERE logid in (select id from logs WHERE cast(doe as date)<cast(current_timestamp as date) and cast(doe as date)>='2020-10-01' and is_deleted=0) AND resourceid='$rid' and is_deleted=0";
    $r2 = mysqli_query($db, $q2);
    $re2 = mysqli_fetch_assoc($r2);
    $produced = $re2['produced'];

    $q3 = "SELECT sum(costperunit) as purprice, COALESCE(sum(qty),0) as purchased,qty,costperunit from purchase_items WHERE pid in (select id from purchases WHERE is_deleted=0 and cast(dop as date)<cast(current_timestamp as date)) and cast(doe as date)>='2020-10-01' and resourceid='$rid' and is_deleted=0";
    $r3 = mysqli_query($db, $q3);
    $re3 = mysqli_fetch_assoc($r3);
    $purchased = $re3['purchased'];
    $new_pdpqty = $re3['qty'];
    $newpurprice = $re3['costperunit'];

    //Use formula Purchased-Consume Qty For Remained quantity ....
    $remainedQty = $purchased - $consumed;

    $remainedQtyprice = $remainedQty * $newpurprice;

    // Use formula RemainedConsumeCost + PerDayPurchasedCost for Total Purchase Cost..
    $totalpurCost = $newpurprice + $remainedQtyprice;


    // Use formula RemainedQuantity + PerDayPurchasedQuantity for Total Purchase Quantity..
    $totalpurQty = $remainedQty + $new_pdpqty;

    $opening_stock = $purchased + $produced - $consumed;
    @$average = $totalpurCost / $totalpurQty;

    $string = substr($average, 0, 5);
    if ($totalpurQty == NULL) {
        $string = 0;
        return [$totalpurCost, $totalpurQty, $opening_stock, $string];
    } else {

        return [$totalpurCost, $totalpurQty, $opening_stock, $string];
    }
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
    $d1 = date("Y/m/d");
    echo <<<_END
        <div class="container">
        <div class="row">
                <div class="col-lg-12">
                    <h2 class="h2">Inventory as on $d1</h2><br>
_END;


    echo <<<_END
    <div class="table table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Resource</th>
                    <th>Opening Stock</th>
                    <th>Purchased</th>
                    <th>Produced</th>
                    <th>Consumed</th>
                    <th>Balance</th>
                    <th>Total Cost</th>
                    <th>Total ConQty+PDPQty</th>
                    <th>Average</th>
                   
                </tr>
            </thead>
            <tbody>
_END;

    $q = "SELECT * FROM resources WHERE is_deleted=0";
    $r = mysqli_query($db, $q);
    $sn = 1;
    while ($res = mysqli_fetch_assoc($r)) {
        $resourcename = $res['resourcename'];
        $unit = $res['unit'];
        $rid = $res['id'];
        [$a, $b, $c, $d] = getOpeningStock($db, $rid);
        $qc = "select COALESCE(sum(qty),0) consumed from log_resource WHERE logid in (select id from logs WHERE cast(doe as date)=cast(current_timestamp as date) and is_deleted=0) AND resourceid='$rid' and is_deleted=0";
        $rc = mysqli_query($db, $qc);

        $rec = mysqli_fetch_assoc($rc);
        $consumed = $rec['consumed'];

        $qpro = "select COALESCE(sum(qty),0) produced from log_output WHERE logid in (select id from logs WHERE cast(doe as date)=cast(current_timestamp as date) and is_deleted=0) AND resourceid='$rid' and is_deleted=0";
        $rpro = mysqli_query($db, $qpro);

        $repro = mysqli_fetch_assoc($rpro);
        $produced = $repro['produced'];

        $qpur = "select COALESCE(sum(qty),0) as purchased from purchase_items WHERE pid in (select id from purchases WHERE is_deleted=0 and cast(dop as date)=cast(current_timestamp as date)) and resourceid='$rid' and is_deleted=0";
        $rpur = mysqli_query($db, $qpur);

        $repur = mysqli_fetch_assoc($rpur);
        $purchased = $repur['purchased'];
        $balance = $c + $produced + $purchased - $consumed;






        echo <<<_END
        <tr>
            <td>$sn</td>
            <th>$resourcename</th>
            <th>$c $unit</th>
            <td>$purchased $unit</td>
            <td>$produced $unit</td>
            <td>$consumed $unit</td>
            <th>$balance $unit</th>
            <td>$a</td>
            <td>$b</td>  
            <td><b>$d</b></td>
        </tr>
_END;
        $sn = $sn + 1;
    }


    echo <<<_END
            </tbody>
        </table>
    </div>

_END;


    echo '</div></div>';

    include_once 'foot.php';
} else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
