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
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/media.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
        <style>
        @media print  { 
            header,#report,#btn { 
               display:none; 
            } 
         </style>
       

    </head>
    
    <body>    
_END;

    include_once 'nav.php';
    echo <<<_END
        <div class="container">
        <div class="row">
        <div class="col-lg-12" id="report">
        <h3 class="mb-4">Trial Balance</h3>
        <form action="trial_balance.php" method="get">
                        <div class="row">
                            <div class="col-lg" id="d">
                                <input type="date" class="form-control" name="start_date">
                            </div>
                            <div class="col-lg">
                                <input type="date" class="form-control" name="end_date">
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Show Report</button>
                    </form>
                    </div>
_END;

    if (isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date'] != '' && $_GET['end_date'] != '') {
        $start_date = mysqli_real_escape_string($db, $_GET['start_date']);
        $end_date = mysqli_real_escape_string($db, $_GET['end_date']);
        $q = "SELECT sum(amt_paid) as debit,sum(amt_received) as credit,particular,transaction_account,transaction_category,cast(dot as date) as d from transactions where is_deleted=0 and cast(dot as date)>='$start_date' and cast(dot as date)<='$end_date' group by transaction_account order by cast(dot as date)";
        $r = mysqli_query($db, $q);
        $q1 = "SELECT sum(amt_paid) as total1 ,sum(amt_received) as total2 from transactions where is_deleted=0 and cast(dot as date)>='$start_date' and cast(dot as date)<='$end_date'";
        $r1 = mysqli_query($db, $q1);
        $res1 = mysqli_fetch_assoc($r1);
        $total1 = $res1['total1'];
        $total2 = $res1['total2'];
        $sdt = date("d-m-Y", strtotime($start_date));
        $edt = date("d-m-Y", strtotime($end_date));
        $date = '';
        $sn = 0;
        echo <<<_END
<div class="col-lg-12">
<div class="row" id="b">
        <div class="col-lg-6">
            <h4 class="mb-4">From $sdt to $edt</h4>
        </div>
        <div class="col-lg-6" id="rb">
            <button class="btn btn-primary" id="btn" onclick="window.print()">Print Report</button>
        </div>
</div>
_END;
        if (mysqli_num_rows($r) > 0) {
            echo <<<_END
        <div class="row">
        <table class="table table-bordered table-md">
        <tr>
        <th colspan="1">Particulars</th>
        <th colspan="2" class="text-center">Closing Balance</th>
        </tr>
        <tr>
        <th>Purchases Accounts -- Yet to be calculated</th>
        </tr>
        <tr>
        <th>Indirect Expenses</th>
        <th>Debit</th>
        <th>Credit</th>
        </tr>
_END;
            while ($res = mysqli_fetch_assoc($r)) {
                $particular = $res['particular'];
                $account = getDimensionValue($db, 'transactions_accounts', $res['transaction_account'], 'account');
                $debit = $res['debit'];
                $credit = $res['credit'];
                $balance = $credit - $debit;
                echo <<<_END
        <tr>
        <td>$account</td>
        <td>$debit</td>
        <td>$credit</td>
        </tr>
_END;
            }
        }
        echo <<<_END
    <tr>
    <th>Grand Total</th>
    <th>$total1</th>
    <th>$total2</th>
    </tr>
_END;
        echo <<<_END
    </table>
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
    </body>
</html>
_END;
    } else {
        echo 'No expenses found';
    }
} else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
