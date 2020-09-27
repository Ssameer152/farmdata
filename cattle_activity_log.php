<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
    if(isset($_GET['cid']) && $_GET['cid']!='')
    {
        $cid=$_GET['cid'];

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
                <div class="col-lg-6">
                    <h2>Cattle Activity Log</h2>
                    <form action="cattle_activity_log_ap.php" method="post">
                        <div class="form-group">
                            <label for="cattle activity">Cattle Activity</label>
                            <select name="cactivity" class="form-control">
                            <option value="">--Select Cattle Activity--</option>
_END;
        $q="SELECT * from cattle_activity where is_deleted=0 order by name asc";
        $r=mysqli_query($db,$q);
        while($res=mysqli_fetch_assoc($r)){
            $id=$res['id'];
            $name=$res['name'];
            echo <<<_END
            <option value="$id">$name</option>
_END;
        }
                                    
        echo <<<_END
                        </select>
                        </div>
                        <div class="form-group">
                            <label>Activity Value</label>
                            <input type="text" name="acvalue" class="form-control"/>
                        </div>
                        <div class="form-group">
                        <label>Comments</label>
                        <input type="text" name="comments" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Date of Activity</label>
                        <input type="date" name="doa" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="cid" value="$cid"/>
                        <button type="submit" class="btn btn-primary">Add Cattle Activity</button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6">
                <div class="table-responsive">
                    <h2>For Cattle id : $cid</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Cattle Name</th>
                                <th>Cattle Type</th>
                                <th>Breed</th>
                            </tr>
                        </thead>
                        <tbody>
_END;
            $q = "SELECT id,name,type_id,breed_id from cattle where id='$cid' and is_deleted=0";
            $r = mysqli_query($db,$q);
            $sn = 0;
            while($res = mysqli_fetch_assoc($r))
            {
                $id=$res['id'];
                $cname = $res['name'];
                $ctype = $res['type_id'];
               
                $q2 = "SELECT * FROM cattle_type WHERE id='$ctype'";
                $r2 = mysqli_query($db,$q2);
                
                $re2 = mysqli_fetch_assoc($r2);
                $cattleType= $re2['name'];
                $sn = $sn + 1;
            
                $breed = $res['breed_id'];
                
                $q3 = "SELECT * FROM cattle_breed WHERE id='$breed'";
                $r3 = mysqli_query($db,$q3);
                
                $re3 = mysqli_fetch_assoc($r3);
                
                $cbreed = $re3['breed'];
            
                echo <<<_END
                <tr>
                    <td>$sn</td>
                    <td>$cname</td>
                    <td>$cattleType</td>
                    <td>$cbreed</td>
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
                <div class="container">
                <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Cattle Activity</th>
                                    <th>Activity Value</th>
                                    <th>Comments</th>
                                    <th>Activity Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

$q = "SELECT * FROM cattle_activity_log where id='$cid' and is_deleted=0";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sn = $res['id'];
    $c_activity = $res['caid'];
    $doa=$res['doa'];
    $q1="SELECT name from cattle_activity where id='$c_activity' and is_deleted=0";
    $r1=mysqli_query($db,$q1);
    $re1=mysqli_fetch_assoc($r1);
    $ct_activity=$re1['name'];
    $acvalue=$res['activity_value'];
    $comments=$res['comments'];

    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$ct_activity</td>
        <td>$acvalue</td>
        <td>$comments</td>
        <td>$doa</td>
        <td><a href="delete.php?table=cattle_activity_log&rid=$sn&return=cattle_activity_log">Delete</a> | <a href="cattle_view.php?vid=$sn">View cattle report</a></td>
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
    $msg = "please fill all fields";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=cattle_activity_log.php?msg=$msg'>
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
