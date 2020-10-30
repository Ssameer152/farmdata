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
    $q = "select COALESCE(sum(qty),0) consumed from log_resource WHERE logid in (select id from logs WHERE cast(doe as date)<cast(current_timestamp as date) and cast(doe as date)>='2020-10-01' and is_deleted=0) AND resourceid='$rid' and is_deleted=0";
    $r = mysqli_query($db, $q);
    $res = mysqli_fetch_assoc($r);
    $consumed = $res['consumed'];

    $q2 = "select COALESCE(sum(qty),0) produced from log_output WHERE logid in (select id from logs WHERE cast(doe as date)<cast(current_timestamp as date) and cast(doe as date)>='2020-10-01' and is_deleted=0) AND resourceid='$rid' and is_deleted=0";
    $r2 = mysqli_query($db, $q2);
    $re2 = mysqli_fetch_assoc($r2);
    $produced = $re2['produced'];

    $q3 = "select COALESCE(sum(qty),0) as purchased from purchase_items WHERE pid in (select id from purchases WHERE is_deleted=0 and cast(dop as date)<cast(current_timestamp as date)) and cast(doe as date)>='2020-10-01' and resourceid='$rid' and is_deleted=0";
    $r3 = mysqli_query($db, $q3);
    $re3 = mysqli_fetch_assoc($r3);
    $purchased = $re3['purchased'];
    return $purchased + $produced - $consumed;
}

if (isset($_SESSION['user'])) {
    include_once 'db.php';
    echo <<<_END
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
_END;
    include_once 'nav.php';
    $d = date("d/m/Y");
    echo <<<_END
        <div class="container">
        <div class="row">
                <div class="col-lg-12">
                    <h2 class="h2">Inventory as on $d</h2><br>
_END;


    echo <<<_END
    <div class="table table-responsive">
        <table id="table" class="table table-bordered">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Resource</th>
                    <th>Opening Stock</th>
                    <th>Purchased</th>
                    <th>Produced</th>
                    <th>Consumed</th>
                    <th>Balance</th>
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

        $opening_stock = getOpeningStock($db, $rid);

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

        $balance = $opening_stock + $produced + $purchased - $consumed;

        echo <<<_END
        <tr>
            <td>$sn</td>
            <th>$resourcename</th>
            <th>$opening_stock $unit</th>
            <td>$purchased $unit</td>
            <td>$produced $unit</td>
            <td>$consumed $unit</td>
            <th>$balance $unit</th>
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
else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
