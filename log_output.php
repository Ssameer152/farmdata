<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';


if(isset($_GET['logid']) && $_GET['logid']!='')
{    
    $logid = $_GET['logid'];
    
    
    if(isset($_GET['id']) && $_GET['id']!=''){
        $mid = $_GET['id'];
        $q = "select * from log_output WHERE id='$mid'";
        $r = mysqli_query($db,$q);
        
        $res = mysqli_fetch_assoc($r);
        
        $db_person = $res['person'];
        $db_resource = $res['resourceid'];
        $db_qty = $res['qty'];
        $db_comments = $res['comments'];
        
    }
    else
    {
        $db_person = '';
        $db_resource = '';
        $db_qty = '';
        $db_comments = '';
    }
    
    
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
_END;
if(isset($_GET['msg']) && $_GET['msg']!=''){
    $msg = $_GET['msg'];
    echo<<<_END
<div class="col-lg-6">
    <div class="alert alert-primary" role="alert">
$msg
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
</div>
_END;
} 
            echo <<<_END
            <div class="row">
                <div class="col-lg-6">
                    <h2>Log Outputs of Work</h2>
_END;


    echo <<<_END
                        <form action="log_output_ap.php" method="post">
                        <div class="form-group">
                            <label for="person">Person</label>
                            <select name="person" class="form-control">
                                <option value="">--Select Person--</option>
_END;

$q = "SELECT * FROM people WHERE is_deleted=0 order by fname asc";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sid = $res['id'];
    $rname = $res['fname'] . ' ' . $res['lname'];
    
    if($sid == $db_person)
    {
        echo <<<_END
    <option value="$sid" selected="selected">$rname</option>
_END;
    }
    else{
    echo <<<_END
    <option value="$sid">$rname</option>
_END;
    }

}

echo <<<_END
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="area">Resource</label>
                            <select name="area" class="form-control">
                                <option value="">--Select Resource--</option>
_END;

$q = "SELECT * FROM resources WHERE is_deleted=0 order by resourcename asc";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sid = $res['id'];
    $rname = $res['resourcename'];
    $unit = $res['unit'];
    
    if($db_resource == $sid){
        echo <<<_END
    <option value="$sid" selected="selected">$rname ($unit)</option>
_END;
    }
    else{
    echo <<<_END
    <option value="$sid">$rname ($unit)</option>
_END;
}

}

echo <<<_END
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="text" name="qty" value="$db_qty" class="form-control">
                        </div>
                        <div class="form-group">
                        <label for="comments">Comments</label>
                        <input type="text" name="comments" value="$db_comments" class="form-control">
                        </div>
                        <input type="hidden" name="logid" value="$logid">
_END;

if(isset($mid)){
    echo <<<_END
    <input type="hidden" name="mid" value="$mid">
_END;
}

echo <<<_END
						<button type="submit" class="btn btn-primary">Add Resource</button>
                    </form>
_END;


echo <<<_END
                </div>
<div class="col-lg-12">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Person</th>
                    <th>Resource</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
_END;

$q = "SELECT sum(qty) as qty,resourceid,person FROM log_output WHERE logid='$logid' AND is_deleted=0 GROUP BY resourceid,person";
$r = mysqli_query($db,$q);
$sn = 0;
$q1="SELECT sum(qty) as total FROM log_output WHERE logid='$logid' AND is_deleted=0";
$r1=mysqli_query($db,$q1);
$res1=mysqli_fetch_assoc($r1);
$total=$res1['total'];

while($res = mysqli_fetch_assoc($r))
{
    $qty = $res['qty'];
    $rid = $res['resourceid'];
    
    $q2 = "SELECT resourcename,unit FROM resources WHERE id='$rid'";
    $r2 = mysqli_query($db,$q2);
    
    $re2 = mysqli_fetch_assoc($r2);
    
    $unit = $re2['unit'];
    $resourcename  = $re2['resourcename'];
    $sn = $sn + 1;
    
    $person = $res['person'];
    
    $q3 = "SELECT * FROM people WHERE id='$person'";
    $r3 = mysqli_query($db,$q3);
    
    $re3 = mysqli_fetch_assoc($r3);
    
    $fullname = $re3['fname'] . ' ' . $re3['lname'];
    
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$fullname</td>
        <td>$resourcename</td>
        <td>$qty $unit</td>
    </tr>
_END;
}
    
echo <<<_END
            </tbody>
        </table>
        <h6 class="text-primary" style="float:right; padding-right:200px"><b>Total : $total $unit</b></h6>
    </div>

</div>


<div class="col-lg-12">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Person</th>
                    <th>Resource</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
_END;

$q = "SELECT id,qty,resourceid,person FROM log_output WHERE logid='$logid' AND is_deleted=0";
$r = mysqli_query($db,$q);
$sn = 0;
while($res = mysqli_fetch_assoc($r))
{
    $qty = $res['qty'];
    $rid = $res['resourceid'];
    $id = $res['id'];
    $q2 = "SELECT resourcename,unit FROM resources WHERE id='$rid'";
    $r2 = mysqli_query($db,$q2);
    
    $re2 = mysqli_fetch_assoc($r2);
    
    $unit = $re2['unit'];
    $resourcename  = $re2['resourcename'];
    $sn = $sn + 1;
    
    $person = $res['person'];
    
    $q3 = "SELECT * FROM people WHERE id='$person'";
    $r3 = mysqli_query($db,$q3);
    
    $re3 = mysqli_fetch_assoc($r3);
    
    $fullname = $re3['fname'] . ' ' . $re3['lname'];
    
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$fullname</td>
        <td>$resourcename</td>
        <td>$qty $unit</td>
        <td><a href="log_output.php?logid=$logid&id=$id">Modify</a> | <a href="delete.php?table=log_output&return=log_output&rid=$id&logid=$logid">Delete</a></td>
    </tr>
_END;
}

echo <<<_END
            </tbody>
        </table>
    </div>

</div>



                
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
