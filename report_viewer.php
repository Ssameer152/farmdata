<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';


function getDimensionValue($db,$table,$gid,$name){
    $q = "SELECT * FROM $table WHERE id=$gid";
    $r = mysqli_query($db,$q);
    
    $res = mysqli_fetch_assoc($r);
    
    $value = $res[$name];
    
    return $value;
}


if(isset($_GET['date']) && $_GET['date']!='' && isset($_GET['area']) && $_GET['area']!='')
{    
    $date = $_GET['date'];
    $area = $_GET['area'];
    
    $q = "SELECT * FROM logs WHERE cast(doe as date)='$date' AND area='$area'";
    $r = mysqli_query($db,$q);
    
    
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
_END;

$row = mysqli_num_rows($r);

if($row>0)
{
    while($res = mysqli_fetch_assoc($r)){
        $activity = getDimensionValue($db,'activities',$res['activity'],'activity');
        $authorizer = $res['people'];
        $status = $res['status'];
        $logid = $res['id'];
        
        echo <<<_END
            <div class="col-lg-12">
                <h2>$activity</h2>
                <hr>
_END;

$q2 = "SELECT * FROM log_resource WHERE logid='$logid'";
$r2 = mysqli_query($db,$q2);

$row2 = mysqli_num_rows($r2);

if($row2>0)
{
    echo <<<_END
    
    <h4>Resources Utilized</h4>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Person</th>
                    <th>Resource</th>
                    <th>Quantity</th>
                    <th>Cost Per Unit</th>
                </tr>
            </thead>
            <tbody>
_END;
$sn = 0;
while($re2 = mysqli_fetch_assoc($r2)){
    $resourceid = getDimensionValue($db,'resources',$re2['resourceid'],'resourcename');
    $person = getDimensionValue($db,'people', $re2['person'],'fname') . ' ' . getDimensionValue($db,'people', $re2['person'],'lname');
    $qty = $re2['qty'] . ' ' .  getDimensionValue($db,'resources',$re2['resourceid'],'unit');
    $costperunit = $re2['costperunit'];
    $sn = $sn + 1;
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$person</td>
        <td>$resourceid</td>
        <td>$qty</td>
        <td>&#8377; $costperunit</td>
    </tr>
_END;
    
}

echo <<<_END
            </tbody>
        </table>
    </div>
    
_END;
}
else{
    echo <<<_END
    <h4>Resources Utilized</h4>
    <p>No resources were utilized in this activity.</p>
_END;
}
                

// output

$q2 = "SELECT * FROM log_output WHERE logid='$logid'";
$r2 = mysqli_query($db,$q2);

$row2 = mysqli_num_rows($r2);

if($row2>0)
{
    echo <<<_END
    
    <h4>Output Generated</h4>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Resource</th>
                    <th>Quantity</th>
                    <th>Person</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
_END;
$sn = 0;
while($re2 = mysqli_fetch_assoc($r2)){
    $resourceid = getDimensionValue($db,'resources',$re2['resourceid'],'resourcename');
    $qty = $re2['qty'] . ' ' .  getDimensionValue($db,'resources',$re2['resourceid'],'unit');
    $person = getDimensionValue($db,'people', $re2['person'],'fname') . ' ' . getDimensionValue($db,'people', $re2['person'],'lname');
    $comments = $re2['comments'];
    $sn = $sn + 1;
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$resourceid</td>
        <td>$qty</td>
        <td>$person</td>
        <td>$comments</td>
    </tr>
_END;
    
}

echo <<<_END
            </tbody>
        </table>
    </div>
    
_END;
}
else{
    echo <<<_END
    <h4>Output Generated</h4>
    <p>No output was generated in this activity.</p>
_END;
}

// end of output


// assets

$q2 = "SELECT * FROM log_assets WHERE logid='$logid'";
$r2 = mysqli_query($db,$q2);

$row2 = mysqli_num_rows($r2);

if($row2>0)
{
    echo <<<_END
    
    <h4>Assets Used</h4>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Asset</th>
                    <th>Usage(Time)</th>
                    <th>Person</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
_END;
$sn = 0;
while($re2 = mysqli_fetch_assoc($r2)){
    $asset = getDimensionValue($db,'assets',$re2['assetid'],'assetname');
    $qty = $re2['usage_time'];
    $person = getDimensionValue($db,'people', $re2['person'],'fname') . ' ' . getDimensionValue($db,'people', $re2['person'],'lname');
    $comments = $re2['comments'];
    $sn = $sn + 1;
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$asset</td>
        <td>$qty</td>
        <td>$person</td>
        <td>$comments</td>
    </tr>
_END;
    
}

echo <<<_END
            </tbody>
        </table>
    </div>
    
_END;
}
else{
    echo <<<_END
    <h4>Assets Used</h4>
    <p>No output was generated in this activity.</p>
_END;
}


// end of assets

                
        echo <<<_END
            <hr><hr>
            </div>
            
_END;
        
    }
}
else{
    echo <<<_END
    <p>No activity information available for the day</p>
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
    $msg = "Please select a work log";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}


}
else
{
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

?>	
