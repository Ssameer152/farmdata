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
    echo <<<_END
<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.js"></script>        
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
    </head>
    
    <body>    
_END;

include_once 'nav.php';

    echo <<<_END
    <div class="container">
    <div class="row">
        <div class="col-lg-6">
            <h3 class="mb-4 mt-2">Log Resource Info</h3>

    <form action="log_resource_data_ap.php" method="post">
        <label class="form-check-inline mr-4"><b>Type</b></label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" id="rad1" type="radio" name="rad" value="1" checked />
            <label class="form-check-label" for="Produced">Produced</label>
        </div>
        <div class="form-check form-check-inline ml-4">
            <input class="form-check-input" id="rad2" type="radio" name="rad" value="2" />
            <label class="form-check-label" for="Consumed">Consumed</label>
        </div>
        <h6 class="mt-2 mb-2">Activity</h6>
        <div class="form-check form-group">

_END;
        $q="SELECT * from logs where cast(doe as date)=cast(current_timestamp() as date) and  is_deleted=0";
        $r=mysqli_query($db,$q);
        while($res=mysqli_fetch_assoc($r)){
            $logid=$res['id'];
            $activity=$res['activity'];
            $ac=getDimensionValue($db,'activities',$res['activity'],'activity');
            echo <<<_END
            <input class="form-check-input" id="rad2" type="radio" name="logid" value="$logid" checked/>
            <label class="form-check-label mb-2">$ac</label><br/>
_END;
        }
        echo <<<_END
        </div>
        <div class="form-group mb-4">
        <label for="person">Person</label>
        <select name="person" class="form-control">
        <option value="">--Select Person--</option>
_END;
        $q = "SELECT * FROM people WHERE is_deleted=0 order by fname asc";
        $r = mysqli_query($db,$q);
        while($res=mysqli_fetch_assoc($r)){
            $id=$res['id'];
            $name=$res['fname'].' '.$res['lname'];
            echo <<<_END
            <option value="$id">$name</option>
_END;
        }
        echo <<<_END
        </select>
        </div>
        <div class="form-group">
        <label for="resource">Resource</label>
        <select name="resource" class="form-control">
        <option value="">--Select Resource--</option>
_END;
        $q = "SELECT * FROM resources WHERE is_deleted=0 order by resourcename asc";
        $r = mysqli_query($db,$q);
        while($res=mysqli_fetch_assoc($r)){
            $id=$res['id'];
            $rname=$res['resourcename'];
            echo <<<_END
            <option value="$id">$rname</option>
_END;
        }
        echo <<<_END
        </select>
        </div>
        <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="text" name="qty" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary mt-2">Add To Log</button>
    </form>
_END;

        echo <<<_END
        </div>
        </div>
        <div class="row">
        <div class="col-lg-6">
            <div class="table table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th>Person</th>
                            <th>Resource</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
_END;
//          Display part ...
//         $sn=0;
//         $q="SELECT * from log_resource_data where type=1 and is_deleted=0";
//         $r=mysqli_query($db,$q);
//         while($res=mysqli_fetch_assoc($r)){
//             $sn=$sn+1;
//             $id=$res['id'];
//             $rname=$res['resource'];
//             $person=$res['person'];
//             $qty=$res['quantity'];
//             $resource=getDimensionValue($db,'resources',$res['resource'],'resourcename');
//             $people=getDimensionValue($db,'people',$res['person'],'fname').' '.getDimensionValue($db,'people',$res['person'],'lname');
//             echo <<<_END
//             <tr>
//             <td>$sn</td>
//             <td>$people</td>
//             <td>$resource</td>
//             <td>$qty</td>
//             </tr>
// _END;
//         }

include_once 'foot.php';

echo <<<_END
    </div>
    </div>
    </body>
    </html>
_END;
}
else{
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
?>