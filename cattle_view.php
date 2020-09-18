<?php
session_start();
function getDimensionValue($db,$table,$gid,$name){
    $q = "SELECT * FROM $table WHERE id=$gid";
    $r = mysqli_query($db,$q);
    
    $res = mysqli_fetch_assoc($r);
    
    $value = $res[$name];
    
    return $value;
}
if(isset($_SESSION['user'])){
    include_once 'db.php';
    if(isset($_GET['vid']) && $_GET['vid']!='')
    {
        $vid=$_GET['vid'];
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
        <div class="table-responsive">
            <h2 class="mb-4">Cattle Report view</h2>
        <table class="table">
        <thead>
        <tr>
            <th>S.no</th>
            <th>Cattle Name</th>
            <th>Cattle Type</th>
            <th>Breed</th>
            <th>Cattle Activity</th>
            <th>Actual Date</th>
            <th>Activity Date</th>
            <th>Activity Value</th>
            <th>Comments</th>
        </tr>
        </thead>
        <tbody>
    _END;
    $q="SELECT cast(doe as date) as e,cast(doa as date) as a,id,cid,caid,activity_value,comments from cattle_activity_log where is_deleted=0 and id='$vid'";
    $r=mysqli_query($db,$q);
    while($res=mysqli_fetch_assoc($r)){
        $cid=$res['cid'];
        $id=$res['id'];
        $ctlactivity=getDimensionValue($db,'cattle_activity',$res['caid'],'name');
        $d1=$res['e'];
        $doe=date("d-m-Y", strtotime($d1));
        $d2=$res['a'];
        $doa=date("d-m-Y", strtotime($d2));
        $acvalue=$res['activity_value'];
        $comments=$res['comments'];
        
        echo <<<_END
        <tr>
        <td>$id</td>
        _END;
        $q1="SELECT * from cattle where id='$cid' and is_deleted=0";
        $r1=mysqli_query($db,$q1);
        while($res1=mysqli_fetch_assoc($r1)){
            $cname=$res1['name'];
            $ctype=getDimensionValue($db,'cattle_type',$res1['type_id'],'name');
            $breed=getDimensionValue($db,'cattle_breed',$res1['breed_id'],'breed');
        echo <<<_END
        <td>$cname</td>
        <td>$ctype</td>
        <td>$breed</td>
        _END;
        }
        echo <<<_END
        <td>$ctlactivity</td>
        <td>$doe</td>
        <td>$doa</td>
        <td>$acvalue</td>
        <td>$comments</td>
        </tr>
        _END;
    }
    
 include_once 'foot.php';

echo <<<_END
    </tbody>
    </table>
    </div>
    </div>
    </div>
    </body>
</html>
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