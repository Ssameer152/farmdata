<?php
session_start();
function getDimensionValue($db, $table, $gid, $name)
{
    $q = "SELECT * FROM $table WHERE id=$gid";
    $r = mysqli_query($db, $q);

    $res = mysqli_fetch_assoc($r);

    $value = $res[$name];

    return $value;
}
if (isset($_SESSION['user'])) {
    include_once 'db.php';
    if (isset($_GET['pid']) && $_GET['pid'] != '') {
        $mid = $_GET['pid'];
        $q = "SELECT id,fname,lname,email,phone,cast(joined_on as date) as jd,designation,pword from people where id='$mid' and is_deleted=0";
        $r = mysqli_query($db, $q);
        $res = mysqli_fetch_assoc($r);
        $db_fname = $res['fname'];
        $db_lname = $res['lname'];
        $db_email = $res['email'];
        $db_phone = $res['phone'];
        $db_jdate = $res['jd'];
        $db_desig = $res['designation'];
        $db_pword = $res['pword'];
    } else {
        $db_fname = '';
        $db_lname = '';
        $db_email = '';
        $db_phone = '';
        $db_jdate = '';
        $db_desig = '';
        $db_pword = '';
    }
    echo <<<_END
<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
       
        <!-------Set Alert Timing-------------->
        <script>
            $(function(){
            setTimeout(function(){
            $("#success").hide('blind',{}, 400);
            },4000);
            });
        </script>
        <!-------Close Set Alert Timing-------------->

    </head>
    
    <body>    
_END;

    include_once 'nav.php';

    echo <<<_END

		<div class="container">
_END;

    if (isset($_GET['msg']) && $_GET['msg'] != '') {
        $msg = $_GET['msg'];
        echo <<<_END
<div class="col-lg-6">
    <div class="alert alert-primary" id="success" role="alert">
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
                    <h2>Designation</h2>
                    <form action="designation.php" method="post">
                        <div class="form-group">
                            <label for="designation">Title</label>
                            <input type="text" name="designation" class="form-control">
                        </div>
						<button type="submit" class="btn btn-primary">Add Designation</button>
                    </form>
                </div>
            </div>
			<div class="row">
                <div class="col-lg-6">
                    <h2>People</h2>
                    <form action="people_add.php" method="post">
						<div class="form-group">
                            <label for="firstname">First Name</label>
_END;
    if ($db_fname == '') {
        echo <<<_END
                            <input type="text" name="fname" class="form-control">
_END;
    } else {
        echo <<<_END
                            <input type="text" value="$db_fname" name="fname" class="form-control">
_END;
    }
    echo <<<_END
						</div>
						<div class="form-group">
                            <label for="lastname">Last Name</label>
_END;
    if ($db_lname == '') {
        echo <<<_END
                            <input type="text" name="lname" class="form-control">
_END;
    } else {
        echo <<<_END
                            <input type="text" value="$db_lname" name="lname" class="form-control">
_END;
    }
    echo <<<_END
						</div>
                        <div class="form-group">
                            <label for="area">Email</label>
_END;
    if ($db_email == '') {
        echo <<<_END
                            <input type="email" name="email" class="form-control">
_END;
    } else {
        echo <<<_END
                            <input type="email" value="$db_email" name="email" class="form-control">
_END;
    }
    echo <<<_END
						</div>
                        <div class="form-group">
                            <label for="Manager">Designation</label>
                            <select class="form-control" name="desig">
                                <option value="">--Select Designation--</option>
_END;

    $q = "SELECT * FROM designation WHERE is_deleted=0";
    $r = mysqli_query($db, $q);

    while ($res = mysqli_fetch_assoc($r)) {
        $desig = $res['desig'];
        $did = $res['id'];
        if ($did == $db_desig) {
            echo <<<_END
    <option value="$did" selected="selected">$desig</option>
_END;
        } else {
            echo <<<_END
        <option value="$did">$desig</option>
_END;
        }
    }

    echo <<<_END
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="rent">Mobile No.</label>
_END;
    if ($db_phone == '') {
        echo <<<_END
                            <input type="text" name="phone" class="form-control">
_END;
    } else {
        echo <<<_END
                            <input type="text" value="$db_phone" name="phone" class="form-control">
_END;
    }
    echo <<<_END
                        </div>
                        <div class="form-group">
                            <label for="joinedon">Joined On</label>
_END;
    if ($db_jdate == '') {
        echo <<<_END
                            <input type="date" name="joined_on" class="form-control"> 
_END;
    } else {
        echo <<<_END
                            <input type="date" value="$db_jdate" name="joined_on" class="form-control">
_END;
    }
    echo <<<_END
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
_END;
    if ($db_pword == '') {
        echo <<<_END
                            <input type="password" name="pword" class="form-control">
_END;
    } else {
        echo <<<_END
                            <input type="password" value="$db_pword" name="pword" class="form-control">
_END;
    }
    echo <<<_END
                        </div>
_END;
    if (isset($mid)) {
        echo <<<_END
                            <input type="hidden" name="mid" value="$mid">
_END;
    }
    echo <<<_END
						<button type="submit" class="btn btn-primary">Add User</button>
					</form>
                </div>
                
                <div class="col-lg-9">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

    $q = "SELECT * FROM `people` WHERE is_deleted=0";
    $r = mysqli_query($db, $q);

    while ($res = mysqli_fetch_assoc($r)) {
        $sn = $res['id'];
        $name = $res['fname'] . ' ' . $res['lname'];
        $desig = getDimensionValue($db, 'designation', $res['designation'], 'desig');
        $phone = $res['phone'];

        echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$name</td>
        <td>$desig</td>
        <td>$phone</td>
        <td><a href="people.php?table=people&return=people&pid=$sn">Modify</a> | <a href="delete.php?table=people&rid=$sn&return=people">Delete</a></td>
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
} else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
