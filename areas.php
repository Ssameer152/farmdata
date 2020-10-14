<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_GET['aid']) && $_GET['aid']!=''){
        $mid = $_GET['aid'];
        $q="SELECT id,sitename,location,area,manager,monthly_rent,cast(leased_until as date) as ld from areas where id='$mid' and is_deleted=0";
        $r=mysqli_query($db,$q);
        $res=mysqli_fetch_assoc($r);
        $db_sitename=$res['sitename'];
        $db_location=$res['location'];
        $db_area=$res['area'];
        $db_manager=$res['manager'];
        $db_monthly_rent=$res['monthly_rent'];
        $db_leased=$res['ld'];
    }
    else{
        $db_sitename='';
        $db_location='';
        $db_area='';
        $db_manager='';
        $db_monthly_rent='';
        $db_leased='';
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
                    <h2>Areas</h2>
                    <form action="areas_add.php" method="post">
						<div class="form-group">
                            <label for="sitename">Site Name</label>
_END;
                        if($db_sitename==''){
                            echo <<<_END
                            <input type="text" name="sitename" class="form-control">
_END;
                        }
                        else{
                        echo <<<_END
                            <input type="text" name="sitename" value="$db_sitename" class="form-control">
_END;
                        }
                            echo <<<_END
						</div>
						<div class="form-group">
                            <label for="location">Location</label>
_END;
                        if($db_location==''){
                            echo <<<_END
                            <input type="text" name="location" class="form-control">
_END;
                        }
                        else{
                            echo <<<_END
                            <input type="text" name="location" value="$db_location" class="form-control">
_END;
                        }
                        echo <<<_END
						</div>
                        <div class="form-group">
                            <label for="area">Size</label>
_END;
                        if($db_area==''){
                            echo <<<_END
                            <input type="text" name="areasize" class="form-control" placeholder="sq. mtr.">
_END;
                        }
                        else{
                        echo <<<_END
                            <input type="text" name="areasize" value="$db_area" class="form-control" placeholder="sq. mtr.">
_END;
                        }
                        echo <<<_END
						</div>
                        <div class="form-group">
                            <label for="Manager">Manager</label>
                            <select class="form-control" name="manager">
                                <option value="">--Select Manager--</option>
_END;

$q = "SELECT people.id,fname,lname,phone,d.desig FROM `people` INNER JOIN designation d ON people.designation=d.id";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sn = $res['id'];
    $name = $res['fname'] . ' ' . $res['lname'];
    $desig = $res['desig'];
    $phone = $res['phone'];
    if($db_manager==$sn){
    echo <<<_END
    <option value="$sn" selected="selected">$name - $phone ($desig)</option>
_END;
    }
    else{
        echo <<<_END
        <option value="$sn">$name - $phone ($desig)</option>
_END;
    }
}
echo <<<_END
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="rent">Monthly Rent</label>
_END;
                        if($db_monthly_rent==''){
                            echo <<<_END
                            <input type="text" name="rent" placeholder="&#8377;" class="form-control">
_END;
                        }
                        else{
                            echo <<<_END
                            <input type="text" name="rent" value="$db_monthly_rent" placeholder="&#8377;" class="form-control">
_END;
                        }
                        echo <<<_END
                        </div>
                        <div class="form-group">
                            <label for="leaseduntil">Leased Until</label>
_END;
                        if($db_leased==''){
                            echo <<<_END
                            <input type="date" name="leased_until" class="form-control">
_END;
                        }
                        else{
                            echo <<<_END
                            <input type="date" value="$db_leased" name="leased_until" class="form-control">
_END;
                        }
                        echo <<<_END
                        </div>
_END;
if(isset($mid)){
    echo <<<_END
    <input type="hidden" name="mid" value="$mid">
_END;
}
                        echo <<<_END
						<button type="submit" class="btn btn-primary">Add Area</button>
					</form>
                </div>
                
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Site Name</th>
                                    <th>Manager</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

$q = "SELECT areas.id,sitename,people.fname,people.lname FROM `areas` INNER JOIN people on people.id=areas.manager WHERE areas.is_deleted=0";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sn = $res['id'];
    $sitename = $res['sitename'];
    $fname = $res['fname'];
    $lname = $res['lname'];
    
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$sitename</td>
        <td>$fname $lname</td>
        <td><a href="areas.php?table=areas&return=areas&aid=$sn">Modify</a> | <a href="delete.php?table=areas&rid=$sn&return=areas"><span class="fa fa-trash fa-lg"></a></td>
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
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

?>	
