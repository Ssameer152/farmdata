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
        <link rel="stylesheet" href="css/media.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
        <style>
        @media print { 
            header,#report,#btn { 
               display:none; 
            } 
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
                    <h2 class="h2">Cattle Activity Average Report</h2><br>
                    <form action="cattle_activity_report_avg.php" method="get">
                        <div class="row">
                            <div class="col-lg-6" id="d">
                                <input type="date" class="form-control" name="start_date">
                            </div>
                            <div class="col-lg-6">
                                <input type="date" class="form-control" name="end_date">
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-lg-6 mt-2">
                        <select class="form-control" name="caid">
                        <option value="">--select cattle activity--</option>
_END;
    $q = "SELECT * FROM cattle_activity where is_deleted=0";
    $r = mysqli_query($db, $q);
    while ($res = mysqli_fetch_assoc($r)) {
        $id = $res['id'];
        $activity = $res['name'];
        echo <<<_END
<option value="$id">$activity</option>
_END;
    }
    echo <<<_END
                    </select>
                    </div>
                    </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Show Report</button>
                    </form>
                </div>
_END;
    if (isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date'] != '' && $_GET['end_date'] != '' && isset($_GET['caid']) && $_GET['caid'] != '') {
        $start_date = mysqli_real_escape_string($db, $_GET['start_date']);
        $end_date = mysqli_real_escape_string($db, $_GET['end_date']);
        $cattle_activity = mysqli_real_escape_string($db, $_GET['caid']);
        $q = "SELECT t.id,t.cid,t.caid,cast(t.doa as date),COALESCE(sum(t.milkCollectionMorning),0) as milk_collection_morning, COALESCE(sum(t.milkCollectionEvening),0) as milk_collection_evening,COALESCE(sum(t.bhusaMorning),0) as bhusa_morning,COALESCE(sum(t.bhusaEvening),0) as bhusa_evening,COALESCE(sum(t.charaMorning),0) as chara_morning,COALESCE(sum(t.charaEvening),0) as chara_evening,COALESCE(sum(t.danaMorning),0) as dana_morning,COALESCE(sum(t.danaEvening),0) as dana_evening from (SELECT id,cid,caid,doa,case when caid=7 then activity_value end as milkCollectionMorning ,case when caid=8 then activity_value end as milkCollectionEvening,case WHEN caid=9 THEN activity_value end as bhusaMorning,case WHEN caid=10 then activity_value end as bhusaEvening,case WHEN caid=11 then activity_value end as charaMorning,case when caid=12 then activity_value end as charaEvening,case when caid=13 then activity_value end as danaMorning,case when caid=14 then activity_value end as danaEvening  FROM cattle_activity_log where cast(doa as date)>='$start_date' and cast(doa as date)<='$end_date' and caid='$cattle_activity'  and is_deleted=0) as t  ORDER by doa";
        $r = mysqli_query($db, $q);
        $q1 = "SELECT t.id,t.cid,t.caid,cast(t.doa as date) as d,COALESCE(sum(t.milkCollection),0) as milk_collection, COALESCE(sum(t.feeding),0) as 'feeding' from (SELECT id,cid,caid,doa,case when caid=1 then activity_value end as milkCollection ,case when caid=6 then activity_value end as feeding  FROM cattle_activity_log where cast(doa as date)>='$start_date' and  cast(doa as date)<='$end_date' and caid='$cattle_activity' and is_deleted=0) as t";
        $r1 = mysqli_query($db, $q1);
        $res1 = mysqli_fetch_assoc($r1);
        $total1 = $res1['milk_collection'];
        $total2 = $res1['feeding'];

        echo <<<_END
    <div class="col-lg-12">
    <div class="row">
        <h2>Data</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Date</th>
                        <th>Cattle Name</th>
_END;
        if ($cattle_activity == 1) {
            echo <<<_END
                        <th>Milk Collection</th>
_END;
        } elseif ($cattle_activity == 6) {
            echo <<<_END
                        <th>feeding(Bhusa+chara+dana)</th>
_END;
        }

        echo <<<_END
                    </tr>
                </thead>
                <tbody>
_END;
        $date = '';
        while ($res = mysqli_fetch_assoc($r)) {
            $id = $res['id'];
            $cid = $res['cid'];
            $d = $res['d'];
            $dt = date("d-m-Y", strtotime($d));
            $cattle = getDimensionValue($db, 'cattle', $res['cid'], 'name');
            $cattle_activity_value1 = $res['milk_collection'];
            $cattle_activity_value2 = $res['feeding'];
            if ($d != $date) {
                echo <<<_END
        <tr>
            <td>$id</td>
            <td>$dt</td>
            <td>$cattle</td>
_END;
                if ($cattle_activity == 1) {
                    echo <<<_END
            <td>$cattle_activity_value1</td>
_END;
                } elseif ($cattle_activity == 6) {
                    echo <<<_END
            <td>$cattle_activity_value2</td>
_END;
                }
                echo <<<_END
        </tr>
_END;
            }
        }
        echo <<<_END
<tr>
            <th colspan="3">Total</th>
_END;
        if ($cattle_activity == 1) {
            echo <<<_END
            <th>$total1</th>
_END;
        } elseif ($cattle_activity == 6) {
            echo <<<_END
            <th>$total2</th>
_END;
        }
        echo <<<_END
            </tr>
_END;
        echo <<<_END
                </tbody>
            </table>
        </div>
    </div>    
    </div>    
_END;
    } elseif (isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date'] != '' && $_GET['end_date'] != '') {
        $start_date = mysqli_real_escape_string($db, $_GET['start_date']);
        $end_date = mysqli_real_escape_string($db, $_GET['end_date']);

        $q = "SELECT t.id,t.cid,t.caid,cast(t.doa as date) as d,COALESCE(sum(t.milkCollectionMorning),0) as milk_collection_morning, COALESCE(sum(t.milkCollectionEvening),0) as milk_collection_evening,COALESCE(sum(t.bhusaMorning),0) as bhusa_morning,COALESCE(sum(t.bhusaEvening),0) as bhusa_evening,COALESCE(sum(t.charaMorning),0) as chara_morning,COALESCE(sum(t.charaEvening),0) as chara_evening,COALESCE(sum(t.danaMorning),0) as dana_morning,COALESCE(sum(t.danaEvening),0) as dana_evening from (SELECT id,cid,caid,doa,case when caid=1 then activity_value end as milkCollectionMorning ,case when caid=10 then activity_value end as milkCollectionEvening,case WHEN caid=7 THEN activity_value end as bhusaMorning,case WHEN caid=11 then activity_value end as bhusaEvening,case WHEN caid=8 then activity_value end as charaMorning,case when caid=12 then activity_value end as charaEvening,case when caid=9 then activity_value end as danaMorning,case when caid=13 then activity_value end as danaEvening  FROM cattle_activity_log where cast(doa as date)>='$start_date' and cast(doa as date)<='$end_date'  and is_deleted=0 and cid in(66,67,71,72,77,78,79,80,81,82)) as t  group by t.cid ORDER by doa";
        $r = mysqli_query($db, $q);
        $q1 = "SELECT t.id,t.cid,t.caid,cast(t.doa as date),COALESCE(sum(t.milkCollectionMorning),0) as milk_collection_morning, COALESCE(sum(t.milkCollectionEvening),0) as milk_collection_evening,COALESCE(sum(t.bhusaMorning),0) as bhusa_morning,COALESCE(sum(t.bhusaEvening),0) as bhusa_evening,COALESCE(sum(t.charaMorning),0) as chara_morning,COALESCE(sum(t.charaEvening),0) as chara_evening,COALESCE(sum(t.danaMorning),0) as dana_morning,COALESCE(sum(t.danaEvening),0) as dana_evening from (SELECT id,cid,caid,doa,case when caid=1 then activity_value end as milkCollectionMorning ,case when caid=10 then activity_value end as milkCollectionEvening,case WHEN caid=7 THEN activity_value end as bhusaMorning,case WHEN caid=11 then activity_value end as bhusaEvening,case WHEN caid=8 then activity_value end as charaMorning,case when caid=12 then activity_value end as charaEvening,case when caid=9 then activity_value end as danaMorning,case when caid=13 then activity_value end as danaEvening  FROM cattle_activity_log where cast(doa as date)>='$start_date' and cast(doa as date)<='$end_date'  and is_deleted=0 and cid in (66,67,71,72,77,78,79,80,81,82)) as t  ORDER by doa";
        $r1 = mysqli_query($db, $q1);
        $res1 = mysqli_fetch_assoc($r1);
        $total1 = $res1['milk_collection_morning'];
        $total2 = $res1['milk_collection_evening'];
        $total3 = $res1['bhusa_morning'];
        $total4 = $res1['bhusa_evening'];
        $total5 = $res1['chara_morning'];
        $total6 = $res1['chara_evening'];
        $total7 = $res1['dana_morning'];
        $total8 = $res1['dana_evening'];
        $date = '';
        $sdate = date("d-m-Y", strtotime($start_date));
        $edate = date("d-m-Y", strtotime($end_date));
        echo <<<_END
    <div class="col-lg-12">
        <div class="row" id="b">
            <div class="col-lg-6">
                <h5>From $sdate to $edate</h5>
            </div>
            <div class="col-lg-6" id="rb">
                <button id="btn" class="btn btn-primary" onclick="window.print()">Print Report</button>
            </div>
        </div>
    
        <div class="row">
        <div class="table-responsive table-sm">
            <table class="table-sm table-bordered">
                <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Cattle Name</th>
                        <th>Milk Collection Morning</th>
                        <th>Milk Collection Evening</th>
                        <th>Bhusa Morning</th>
                        <th>Chara Morning</th>
                        <th>Dana Morning</th>
                        <th>Bhusa Evening</th>
                        <th>Chara Evening</th>
                        <th>Dana Evening</th>
                    </tr>
                </thead>
                <tbody>
_END;
        $sn = 0;
        while ($res = mysqli_fetch_assoc($r)) {
            $sn = $sn + 1;
            $id = $res['id'];
            $d = $res['d'];
            $cattle = getDimensionValue($db, 'cattle', $res['cid'], 'name');
            $cattle_activity_value1 = $res['milk_collection_morning'];
            $cattle_activity_value2 = $res['milk_collection_evening'];
            $cattle_activity_value3 = $res['bhusa_morning'];
            $cattle_activity_value4 = $res['chara_morning'];
            $cattle_activity_value5 = $res['dana_morning'];
            $cattle_activity_value6 = $res['bhusa_evening'];
            $cattle_activity_value7 = $res['chara_evening'];
            $cattle_activity_value8 = $res['dana_evening'];
            $cattle_activity = getDimensionValue($db, 'cattle_activity', $res['caid'], 'name');
            if ($d != $date) {
                echo <<<_END
                <tr>
                <td>$sn</th>
                <td>$cattle</td>
                <td>$cattle_activity_value1</td>
                <td>$cattle_activity_value2</td>
                <td>$cattle_activity_value3</td>
                <td>$cattle_activity_value4</td>
                <td>$cattle_activity_value5</td>
                <td>$cattle_activity_value6</td>
                <td>$cattle_activity_value7</td>
                <td>$cattle_activity_value8</td>
                </tr>
_END;
            }
        }
        echo <<<_END
        <tr>
                    <th colspan="2">Total</th>
                    <th>$total1</th>
                    <th>$total2</th>
                    <th>$total3</th>
                    <th>$total5</th>
                    <th>$total7</th>
                    <th>$total4</th>
                    <th>$total6</th>
                    <th>$total8</th>
                    </tr>
_END;

        echo <<<_END
                </tbody>
            </table>
            </div>
        </div>
    </div>        
_END;
    } else {
        echo <<<_END
    <p>Add fields are required</p>
_END;
    }

    echo <<<_END
            </div>
        </div>

_END;

    include_once 'foot.php';

    echo <<<_END
    </body>
</html>
_END;
} else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
