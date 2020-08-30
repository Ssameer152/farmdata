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
    </head>
    
    <body>    
_END;

include_once 'nav.php';

echo <<<_END

		<div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="h2">Report</h2><br>
                    <form action="reports.php" method="get">
                        <div class="row">
                            <div class="col-lg">
                                <input type="date" class="form-control" name="start_date">
                            </div>
                            <div class="col-lg">
                                <input type="date" class="form-control" name="end_date">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg">
                                <select class="form-control" name="activity">
                                    <option value="">--select activity--</option>
_END;

$q = "SELECT * FROM activities WHERE is_deleted=0";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $activity = $res['activity'];
    $id = $res['id'];
    
    echo <<<_END
<option value="$id">$activity</option>
_END;
}

echo <<<_END
                                </select>
                            </div>
                            <div class="col-lg">
                                <select class="form-control" name="person">
                                    <option value="">--select person--</option>
_END;

$q = "SELECT * FROM people WHERE is_deleted=0";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $name = $res['fname'] . ' ' . $res['lname'];;
    $id = $res['id'];
    
    echo <<<_END
<option value="$id">$name</option>
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


if(isset($_GET['start_date']) && isset($_GET['end_date']) && isset($_GET['activity']) && isset($_GET['person']) && $_GET['start_date']!='' && $_GET['end_date']!='' && $_GET['activity']!='')
{
    $start_date = mysqli_real_escape_string($db,$_GET['start_date']);
    $end_date = mysqli_real_escape_string($db,$_GET['end_date']);
    $activity = mysqli_real_escape_string($db,$_GET['activity']);
    
    
    $q = "SELECT id,cast(doe as date) as d, activity,people FROM logs WHERE cast(doe as date)>='$start_date' AND cast(doe as date)<='$end_date' AND activity='$activity'";
    $r = mysqli_query($db,$q);
    
    if(mysqli_num_rows($r)>0){
        echo <<<_END
    <div class="col-lg-12">
        <h2>Data</h2>
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
    
    $q2 = "SELECT activity FROM activities WHERE id='$activity'";
    $r2 = mysqli_query($db,$q2);
    
    $re2 = mysqli_fetch_assoc($r2);
    
    $ac = $re2['activity'];
    
    $q3 = "SELECT fname, lname FROM people WHERE id='$people'";
    $r3 = mysqli_query($db,$q3);
    
    $re3 = mysqli_fetch_assoc($r3);
    
    $people = $re3['fname'] . ' ' . $re3['lname'];
    
    if($d != $date){
        echo <<<_END
        <tr>
            <th>$d</th><th>$ac</th><th>$people</th>
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
    
    
    $q4 = "SELECT * from log_resource lr inner join people pe on lr.person=pe.id WHERE logid='$logid' and lr.is_deleted=0";
    $r4 = mysqli_query($db,$q4);
    $row4 = mysqli_num_rows($r4);
    $q7="SELECT SUM(lr.qty) as total,r.unit as unit from log_resource lr inner join resources r on r.id=lr.resourceid WHERE lr.is_deleted=0 and lr.logid IN (SELECT id FROM logs  WHERE doe ='$d' and activity='$activity') group by lr.logid";
    $r7=mysqli_query($db,$q7);
$re7=mysqli_fetch_assoc($r7);
$total=$re7['total'].''.$re7['unit'];
    echo '<td>';
    if(mysqli_num_rows($r4)>0)
    {
        echo '<table border="1" cellspacing="0"><tr><th>Resource</th><th>Qty</th><th>Person</th></tr>';
        while($re4 = mysqli_fetch_assoc($r4)){
            $resourceId = getDimensionValue($db,'resources',$re4['resourceid'],'resourcename');
            $qty = $re4['qty'] . ' ' . getDimensionValue($db,'resources',$re4['resourceid'],'unit');
            $pname=$re4['fname'] . ' ' . $re4['lname'];
            echo <<<_END
     <tr>
        <td>$resourceId</td>
        <td>$qty</td>
        <td>$pname</td>
     </tr>       
_END;
        }
        echo <<<_END
        <tr>
        <td><b>Total:<b>$total</td>
        </tr>
_END;
        echo '</table>';

    }
    else{
        echo '<p>No resources utilized</p>';
    }
    echo '</td>';
    
    $q8="SELECT SUM(lo.qty) as total ,r.unit as unit from log_output lo inner join resources r on r.id=lo.resourceid WHERE lo.is_deleted=0 and lo.logid IN (SELECT id FROM logs  WHERE doe ='$d' and activity='$activity') group by lo.logid";
    $r8=mysqli_query($db,$q8);
    $re8=mysqli_fetch_assoc($r8);
    $total=$re8['total']. '' . $re8['unit'];
    $q5 = "SELECT * from log_output lo inner join people pe on lo.person=pe.id WHERE lo.is_deleted=0 and logid='$logid'";
    $r5 = mysqli_query($db,$q5);
    echo '<td width="33%">';
    if(mysqli_num_rows($r5)>0)
    {
        echo '<table border="1" cellspacing="0"><tr><th>Resource</th><th>Qty</th><th>Person</th></tr>';
        while($re5 = mysqli_fetch_assoc($r5)){
            $resourceId = getDimensionValue($db,'resources',$re5['resourceid'],'resourcename');
            $qty = $re5['qty'] . ' ' . getDimensionValue($db,'resources',$re5['resourceid'],'unit');
            $pname=$re5['fname']. ' ' . $re5['lname'];
            echo <<<_END
     <tr>
        <td>$resourceId</td>
        <td>$qty</td>
        <td>$pname</td>
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
        echo '<p>No output</p>';
    }    
    
    echo '</td>';

    $q9="SELECT SUM(usage_time) as total from log_assets WHERE is_deleted=0 and logid IN (SELECT id FROM logs  WHERE doe ='$d' and activity='$activity') group by logid";
    $r9=mysqli_query($db,$q9);
    $re9=mysqli_fetch_assoc($r9);
    $total=$re9['total'];
    $q6 = "SELECT * from log_assets la inner join people pe on la.person=pe.id WHERE logid='$logid'";
    $r6 = mysqli_query($db,$q6);
    echo '<td>';

    if(mysqli_num_rows($r6)>0)
    {
        echo '<table border="1" cellspacing="0"><tr><th>Resource</th><th>Qty</th><th>Person</th></tr>';
        while($re6 = mysqli_fetch_assoc($r6)){
            $resourceId = getDimensionValue($db,'assets',$re6['assetid'],'assetname');
            $qty = $re6['usage_time'];
            $pname=$re6['fname']. ' ' . $re6['lname'];
            echo <<<_END
     <tr>
        <td>$resourceId</td>
        <td>$qty</td>
        <td>$pname</td>
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
else if(isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date']!='' && $_GET['end_date']!='')
{
    $start_date = mysqli_real_escape_string($db,$_GET['start_date']);
    $end_date = mysqli_real_escape_string($db,$_GET['end_date']);
    
    $q = "SELECT id,cast(doe as date) as d, activity,people FROM logs WHERE cast(doe as date)>='$start_date' AND cast(doe as date)<='$end_date' ORDER BY doe";
    $r = mysqli_query($db,$q);
    
    if(mysqli_num_rows($r)>0){
        echo <<<_END
    <div class="col-lg-12">
        <h2>Data</h2>
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
    
    $q2 = "SELECT activity FROM activities WHERE id='$activity'";
    $r2 = mysqli_query($db,$q2);
    
    $re2 = mysqli_fetch_assoc($r2);
    
    $ac = $re2['activity'];
    
    $q3 = "SELECT fname, lname FROM people WHERE id='$people'";
    $r3 = mysqli_query($db,$q3);
    
    $re3 = mysqli_fetch_assoc($r3);
    
    $people = $re3['fname'] . ' ' . $re3['lname'];
    
    if($d != $date){
        echo <<<_END
        <tr>
            <th>$d</th><th>$ac</th><th>$people</th>
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
    
    
    $q4 = "SELECT * from log_resource WHERE logid='$logid' and is_deleted=0";
    $r4 = mysqli_query($db,$q4);
    $row4 = mysqli_num_rows($r4);
    
    echo '<td>';
    if(mysqli_num_rows($r4)>0)
    {
        echo '<table border="1" cellspacing="0"><tr><th>Resource</th><th>Qty</th></tr>';
        while($re4 = mysqli_fetch_assoc($r4)){
            $resourceId = getDimensionValue($db,'resources',$re4['resourceid'],'resourcename');
            $qty = $re4['qty'] . ' ' . getDimensionValue($db,'resources',$re4['resourceid'],'unit');
            
            echo <<<_END
     <tr>
        <td>$resourceId</td>
        <td>$qty</td>
     </tr>       
_END;
        }
        echo '</table>';

    }
    else{
        echo '<p>No resources utilized</p>';
    }
    echo '</td>';
    
    $q5 = "SELECT * from log_output WHERE logid='$logid' and is_deleted=0";
    $r5 = mysqli_query($db,$q5);
    echo '<td width="33%">';
    if(mysqli_num_rows($r5)>0)
    {
        echo '<table border="1" cellspacing="0"><tr><th>Resource</th><th>Qty</th></tr>';
        while($re5 = mysqli_fetch_assoc($r5)){
            $resourceId = getDimensionValue($db,'resources',$re5['resourceid'],'resourcename');
            $qty = $re5['qty'] . ' ' . getDimensionValue($db,'resources',$re5['resourceid'],'unit');
            
            echo <<<_END
     <tr>
        <td>$resourceId</td>
        <td>$qty</td>
     </tr>       
_END;
        }
        echo '</table>';

    }
    else{
        echo '<p>No output</p>';
    }    
    
    echo '</td>';


    $q6 = "SELECT * from log_assets WHERE logid='$logid' and is_deleted=0";
    $r6 = mysqli_query($db,$q6);
    echo '<td>';

    if(mysqli_num_rows($r6)>0)
    {
        echo '<table border="1" cellspacing="0"><tr><th>Resource</th><th>Qty</th></tr>';
        while($re6 = mysqli_fetch_assoc($r6)){
            $resourceId = getDimensionValue($db,'assets',$re6['assetid'],'assetname');
            $qty = $re6['usage_time'];
            
            echo <<<_END
     <tr>
        <td>$resourceId</td>
        <td>$qty</td>
     </tr>       
_END;
        }
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
