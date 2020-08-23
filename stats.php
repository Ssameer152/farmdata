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
                    <h2 class="h2">Stats Viewer</h2><br>
                    <form action="stats.php" method="get">
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
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Show Stats</button>
                    </form>
                </div>
_END;


if(isset($_GET['start_date']) && isset($_GET['end_date']) && isset($_GET['activity']) && isset($_GET['person']) && $_GET['start_date']!='' && $_GET['end_date']!='' && $_GET['activity']!='')
{
    $start_date = mysqli_real_escape_string($db,$_GET['start_date']);
    $end_date = mysqli_real_escape_string($db,$_GET['end_date']);
    $activity = mysqli_real_escape_string($db,$_GET['activity']);
    
    $q = "SELECT id, activity FROM logs WHERE cast(doe as date)>='$start_date' AND cast(doe as date)<='$end_date' AND activity='$activity'";
    $r = mysqli_query($db,$q);
    
}
else if(isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date']!='' && $_GET['end_date']!='')
{
    $start_date = mysqli_real_escape_string($db,$_GET['start_date']);
    $end_date = mysqli_real_escape_string($db,$_GET['end_date']);
    
    $q = "SELECT id, activity FROM logs WHERE cast(doe as date)>='$start_date' AND cast(doe as date)<='$end_date' ORDER BY doe";
    $r = mysqli_query($db,$q);
    
    if(!$r){
        echo mysqli_error($db);
    }
    
}


if(isset($_GET['start_date']))
{
    if(mysqli_num_rows($r)>0)
    {
        $c = 0;
        $logid = '';
        while($res = mysqli_fetch_assoc($r)){
            if($c == 0){
                $logid = $res['id'];
            }
            else{
                $logid = $logid . ',' . $res['id'];
            }
            $c = $c+1;
            
        }
        
        
        // query for resource stats
        $q2 = "SELECT resourceid,sum(qty) as q FROM log_resource WHERE logid in ($logid) GROUP BY resourceid ORDER BY q DESC";
        $r2 = mysqli_query($db,$q2);
        
        echo <<<_END
            <div class="col-lg-6">
                <h2>Consumption Stats</h2>
                <div class="table table-responsive">
                    <table class="table-striped">
                        <thead>
                            <tr>
                                <th>Resource</th><th>Consumption</th>
                            </tr>
                        </thead>
                        <tbody>
_END;

        while($re2 = mysqli_fetch_assoc($r2)){
            $rid = getDimensionValue($db,'resources',$re2['resourceid'],'resourcename');
            $qty = $re2['q'] . ' ' . getDimensionValue($db,'resources',$re2['resourceid'],'unit');
            echo <<<_END
            <tr>
                <td>$rid</td>
                <td>$qty</td>
            </tr>
_END;
        }

echo <<<_END
                        </tbody>
                    </table>
                </div>
            </div>
_END;


// query for output

        $q2 = "SELECT resourceid,sum(qty) as q FROM log_output WHERE logid in ($logid) GROUP BY resourceid ORDER BY q DESC";
        $r2 = mysqli_query($db,$q2);
        
        echo <<<_END
            <div class="col-lg-6">
                <h2>Output Stats</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Resource</th><th>Production</th>
                            </tr>
                        </thead>
                        <tbody>
_END;

        while($re2 = mysqli_fetch_assoc($r2)){
            $rid = getDimensionValue($db,'resources',$re2['resourceid'],'resourcename');
            $qty = $re2['q'] . ' ' . getDimensionValue($db,'resources',$re2['resourceid'],'unit');
            echo <<<_END
            <tr>
                <td>$rid</td>
                <td>$qty</td>
            </tr>
_END;
        }

echo <<<_END
                        </tbody>
                    </table>
                </div>
            </div>
_END;




    }
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
