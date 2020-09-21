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
                    <h2 class="h2">Custom Report 2</h2><br>
                    <form action="custom_report2.php" method="get">
                        <div class="row">
                            <div class="col-lg">
                                <input type="date" id="theDate" class="form-control" name="start_date">
                            </div>
                            <div class="col-lg">
                                <input type="date" id="theDate" class="form-control" name="end_date">
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Show Report</button>
                    </form>
                </div>
_END;

if(isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date']!='' && $_GET['end_date']!='')
{
    $start_date = mysqli_real_escape_string($db,$_GET['start_date']);
    $end_date = mysqli_real_escape_string($db,$_GET['end_date']);
    
    $q = "SELECT id,cast(doe as date) as d, activity,people FROM logs WHERE cast(doe as date)>='$start_date' AND cast(doe as date)<='$end_date' and is_deleted=0 ORDER BY doe asc";
    $r = mysqli_query($db,$q);
    
    if(mysqli_num_rows($r)>0){
        echo <<<_END
    <div class="col-lg-12">
        <div class="row">
        <h2 style="margin-left:16px">Data</h2>
        <button style="position: absolute; right:10;" class="btn btn-primary" onclick="window.print()">Print Report</button>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Activity</th>
                        <th>Authorized By</th>
                    </tr>
                </thead>
                <tbody>
_END;

$date = '';
while($res = mysqli_fetch_assoc($r)){
    $logid = $res['id'];
    $d = $res['d'];
    $activity = $res['activity'];
    $people = $res['people'];
    $dt=date("d-m-Y", strtotime($d));
    $q2 = "SELECT activity FROM activities WHERE id='$activity' and is_deleted=0 order by activity asc";
    $r2 = mysqli_query($db,$q2);
    
    $re2 = mysqli_fetch_assoc($r2);
    
    $ac = $re2['activity'];
    
    $q3 = "SELECT fname, lname FROM people WHERE id='$people' and is_deleted=0";
    $r3 = mysqli_query($db,$q3);
    
    $re3 = mysqli_fetch_assoc($r3);
    
    $people = $re3['fname'] . ' ' . $re3['lname'];
    
    if($d != $date){
        echo <<<_END
        <tr>
            <th>$dt</th><th>$ac</th><th>$people</th>
        </tr>
_END;
    }
    else{
        echo <<<_END
        <tr>
            <td></td><th>$ac</th><th>$people</th>
        </tr>
_END;

}
// activity details
echo '<tr>';
    
$q1="SELECT resourceid,sum(qty) as q FROM log_resource WHERE is_deleted=0 and logid in ($logid) GROUP BY resourceid ORDER BY q DESC";
$r1=mysqli_query($db,$q1);
$q4 = "SELECT * from log_resource lr inner join people pe on lr.person=pe.id WHERE lr.is_deleted=0 and lr.person in(23,24,25,26,28)  and logid='$logid'";
$r4 = mysqli_query($db,$q4);
    $row4 = mysqli_num_rows($r4);
    $q7="SELECT SUM(lr.qty) as total,r.unit as unit from log_resource lr inner join resources r on r.id=lr.resourceid WHERE lr.is_deleted=0 and lr.logid IN (SELECT id FROM logs  WHERE doe ='$d' and activity='$activity' order by activity asc) group by lr.logid";
    $r7=mysqli_query($db,$q7);
    if(mysqli_num_rows($r7)>0){
$re7=mysqli_fetch_assoc($r7);
$total=$re7['total'].''.$re7['unit'];
    }


    echo '<td><h5>Resources</h5>';
    if(mysqli_num_rows($r4)>0)
    {
        echo '<table border="0" class="table-striped table-responsive" cellspacing="0"><tr><th>Person</th><th>Resource</th><th>Qty</th></tr>';
        while($re4 = mysqli_fetch_assoc($r4)){
            $resourceId = getDimensionValue($db,'resources',$re4['resourceid'],'resourcename');
            $qty = $re4['qty'] . '' . getDimensionValue($db,'resources',$re4['resourceid'],'unit');
            $pname=$re4['fname'] . ' ' . $re4['lname'];
            echo <<<_END
     <tr>
        <td style="white-space:nowrap;">$pname</td>
        <td style="white-space:nowrap;">$resourceId</td>
        <td style="white-space:nowrap;">$qty</td>
     </tr>       
_END;
        }
       
        echo '</table>';
        
    }

    
    if(mysqli_num_rows($r1)>0){

        echo '<table border="0" class="table-striped table-responsive" cellspacing="0"><tr><th>Resource</th><th>Qty</th></tr>';
    while($re1=mysqli_fetch_assoc($r1)){
        $rid = getDimensionValue($db,'resources',$re1['resourceid'],'resourcename');
        $qty1 = $re1['q'] . '' . getDimensionValue($db,'resources',$re1['resourceid'],'unit');
        echo <<<_END
        <tr>
        <td>$rid</td>
        <td>$qty1</td>
        </tr>
_END;
    }
    echo <<<_END
    <tr>
    <td><b>Total:$total</b></td>
    </tr>

_END;

        echo '</table>';
    }
    else{
        echo '<p>No resources utilized</p>';
    }
    echo '</td>';
    
    $q8="SELECT SUM(lo.qty) as total ,r.unit as unit from log_output lo inner join resources r on r.id=lo.resourceid WHERE lo.is_deleted=0 and lo.logid IN (SELECT id FROM logs  WHERE doe ='$d' and activity='$activity' order by activity asc) group by lo.logid";
    $r8=mysqli_query($db,$q8);
    if(mysqli_num_rows($r8)>0){
    $re8=mysqli_fetch_assoc($r8);
    $total=$re8['total'].''. $re8['unit'];
    
    echo '<td><h5>Output</h5>';
        echo '<table border="0" class="table-striped table-responsive" cellspacing="0"><tr><th>Total Output</th></tr>';

    $q11="SELECT resourceid,sum(qty) as q FROM log_output WHERE is_deleted=0 and logid in ($logid) GROUP BY resourceid ORDER BY q DESC";
    $r11=mysqli_query($db,$q11);

    while($re11=mysqli_fetch_assoc($r11)){
        $rid = getDimensionValue($db,'resources',$re11['resourceid'],'resourcename');
        $qty11 = $re11['q'] . '' . getDimensionValue($db,'resources',$re11['resourceid'],'unit');
        echo <<<_END
        <tr>
        <td>$rid</td>
        <td>$qty11</td>
        </tr>
_END;
    }
echo <<<_END
<tr>
<td><b>Total:$total</b></td>
</tr>

_END;
        echo '</table>';

}
 
    echo '</td>';

    $q6 = "SELECT * from log_assets la inner join people pe on la.person=pe.id WHERE la.is_deleted=0 and logid='$logid'";
    $r6 = mysqli_query($db,$q6);
    $q9="SELECT SUM(usage_time) as total from log_assets WHERE is_deleted=0 and logid IN (SELECT id FROM logs  WHERE doe ='$d' and activity='$activity') group by logid";
    $r9=mysqli_query($db,$q9);
    if(mysqli_num_rows($r9)>0){
    $re9=mysqli_fetch_assoc($r9);
    $total=$re9['total'];
    }
    
    echo '<td><h5>Assets</h5>';

    if(mysqli_num_rows($r6)>0)
    {
        echo '<table border="0" class="table-striped table-responsive" cellspacing="0"><tr><th>Person</th><th>Resource</th><th>Qty</th></tr>';
        while($re6 = mysqli_fetch_assoc($r6)){
            $resourceId = getDimensionValue($db,'assets',$re6['assetid'],'assetname');
            $qty = $re6['usage_time'];
            $pname=$re6['fname']. ' ' . $re6['lname'];
            echo <<<_END
     <tr>
        <td>$pname</td>
        <td>$resourceId</td>
        <td>$qty</td>
     </tr>       
_END;
        }
        echo <<<_END
        <tr>
        <td><b>Total:</b>$total</td>
        </tr>
_END;
        echo '</table>';

    }
    else{
        echo '<p>No Assets</p>';
    }    
    
    echo '</td>';

echo '</tr>';
// end of activity details


    $date = $d;
}

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