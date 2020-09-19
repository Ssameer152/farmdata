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
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
        <style>
        @media print { 
            header,#report { 
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
                    <h2 class="h2">Cattle Activity Report</h2><br>
                    <form action="cattle_activity_report.php" method="get">
                        <div class="row">
                            <div class="col-lg">
                                <input type="date" class="form-control" name="start_date">
                            </div>
                            <div class="col-lg">
                                <input type="date" class="form-control" name="end_date">
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-lg-6 mt-2">
                        <select class="form-control" name="caid">
                        <option value="">--select cattle activity--</option>
_END;
$q="SELECT * FROM cattle_activity where is_deleted=0";
$r=mysqli_query($db,$q);
while($res=mysqli_fetch_assoc($r))
{
$id=$res['id'];
$activity=$res['name'];
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
if(isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date']!='' && $_GET['end_date']!='' && isset($_GET['caid']) && $_GET['caid']!='')
{
    $start_date = mysqli_real_escape_string($db,$_GET['start_date']);
    $end_date = mysqli_real_escape_string($db,$_GET['end_date']);
    $cattle_activity = mysqli_real_escape_string($db,$_GET['caid']);

    $q = "SELECT id,cast(doa as date) as d,cid,caid,activity_value,comments FROM cattle_activity_log WHERE cast(doa as date)>='$start_date' AND cast(doa as date)<='$end_date' and caid='$cattle_activity' and is_deleted=0";
    $r=mysqli_query($db,$q);

        echo <<<_END
    <div class="col-lg-12">
        <div class="row">
        <h2>Data</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Cattle Activity</th>
                    </tr>
                </thead>
                <tbody>
_END;
$date = '';
while($res = mysqli_fetch_assoc($r)){
    $cid = $res['cid'];
    $d = $res['d'];
    $dt=date("d-m-Y", strtotime($d));
    $caid=$res['caid'];
    $q2 = "SELECT name FROM cattle_activity WHERE id='$caid' and is_deleted=0";
    $r2 = mysqli_query($db,$q2);
    
    $re2 = mysqli_fetch_assoc($r2);
    
    $ac = $re2['name'];
    
    
    if($d != $date){
        echo <<<_END
        <tr>
            <th>$dt</th><th>$ac</th>
        </tr>
_END;
    }
    else{
        echo <<<_END
        <tr>
            <td></td><th>$ac</th>
        </tr>
_END;
}
    echo '<tr>';

    $q2="SELECT * from cattle  where id='$cid' and is_deleted=0";
    $r2=mysqli_query($db,$q2);
    echo '<td><h4>Cattle</h4>';
    echo '<table border="0" class="table-striped table-responsive" cellspacing="0"><tr><th>Name</th><th>Type</th><th>Breed</th></tr>';
    while($res2=mysqli_fetch_assoc($r2)){
    $cname=$res2['name'];
    $ctltype=getDimensionValue($db,'cattle_type',$res2['type_id'],'name');
    $breed=getDimensionValue($db,'cattle_breed',$res2['breed_id'],'breed');
    echo <<<_END
    <tr>
    <td>$cname</td>
    <td>$ctltype</td>
    <td>$breed</td>
    </tr>
    _END;
    }
    echo '</table>';
    echo '</td>';
    
    $q3="SELECT * from cattle_activity_log where caid='$cattle_activity' and is_deleted=0";
    $r3=mysqli_query($db,$q3);
    echo '<td><h4>logs</h4>';
    echo '<table border="0" class="table-striped table-responsive" cellspacing="0"><tr><th>activity value</th><th>comments</th></tr>';
    while($res3=mysqli_fetch_assoc($r3)){
        $acvalue=$res['activity_value'];
        $comments=$res['comments'];
        echo <<<_END
        <tr>
        <td>$acvalue</td>
        <td>$comments</td>
        </tr>
        _END;
    }
    echo '<table>';
    echo '</td>';
    echo '</tr>';

    $date = $d;
    echo <<<_END
                </tbody>
            </table>
        </div>
    </div>        
_END;
    }
}
elseif(isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date']!='' && $_GET['end_date']!='')
{
    $start_date = mysqli_real_escape_string($db,$_GET['start_date']);
    $end_date = mysqli_real_escape_string($db,$_GET['end_date']);
    
    $q = "SELECT id,cast(doa as date) as d,cid,caid,activity_value,comments FROM cattle_activity_log WHERE cast(doa as date)>='$start_date' AND cast(doa as date)<='$end_date' and is_deleted=0 ORDER BY doa";
    $r=mysqli_query($db,$q);

        echo <<<_END
    <div class="col-lg-12">
        <div class="row">
        <h2>Data</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Cattle Activity</th>
                    </tr>
                </thead>
                <tbody>
_END;
$date = '';
while($res = mysqli_fetch_assoc($r)){
    $cid = $res['cid'];
    $d = $res['d'];
    $dt=date("d-m-Y", strtotime($d));
    $caid=$res['caid'];
    $q2 = "SELECT name FROM cattle_activity WHERE id='$caid' and is_deleted=0";
    $r2 = mysqli_query($db,$q2);
    
    $re2 = mysqli_fetch_assoc($r2);
    
    $ac = $re2['name'];
    
    
    if($d != $date){
        echo <<<_END
        <tr>
            <th>$dt</th><th>$ac</th>
        </tr>
_END;
    }
    else{
        echo <<<_END
        <tr>
            <td></td><th>$ac</th>
        </tr>
_END;
}
    echo '<tr>';

    $q2="SELECT * from cattle where id='$cid' and is_deleted=0";
    $r2=mysqli_query($db,$q2);
    echo '<td><h4>Cattle</h4>';
    echo '<table border="0" class="table-striped table-responsive" cellspacing="0"><tr><th>Name</th><th>Type</th><th>Breed</th></tr>';
    while($res2=mysqli_fetch_assoc($r2)){
    $cname=$res2['name'];
    $ctltype=getDimensionValue($db,'cattle_type',$res2['type_id'],'name');
    $breed=getDimensionValue($db,'cattle_breed',$res2['breed_id'],'breed');
    echo <<<_END
    <tr>
    <td>$cname</td>
    <td>$ctltype</td>
    <td>$breed</td>
    </tr>
    _END;
    }
    echo '</table>';
    echo '</td>';
    
    $q3="SELECT * from cattle_activity_log where cid='$cid' and is_deleted=0 group by cid";
    $r3=mysqli_query($db,$q3);
    echo '<td><h4>logs</h4>';
    echo '<table border="0" class="table-striped table-responsive" cellspacing="0"><tr><th>activity value</th><th>comments</th></tr>';
    while($res3=mysqli_fetch_assoc($r3)){
        $acvalue=$res['activity_value'];
        $comments=$res['comments'];
        echo <<<_END
        <tr>
        <td>$acvalue</td>
        <td>$comments</td>
        </tr>
        _END;
    }
    echo '<table>';
    echo '</td>';
    echo '</tr>';

    $date = $d;
    echo <<<_END
                </tbody>
            </table>
        </div>
    </div>        
_END;
    }
}

else{
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
}
else
{
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

?>