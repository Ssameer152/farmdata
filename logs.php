<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
    echo <<<_END
<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    
    <body>    
_END;

include_once 'nav.php';

echo <<<_END

		<div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2>Logs</h2>
_END;

$display_area = 1;
$display_people = 0;
$display_assets = 0;
$display_resources = 0;
$display_activities = 0;
$display_log = 0;

if(isset($_GET['assets']) && $_GET['assets']!='')
{
    $display_resources = 1;
}
if(isset($_GET['resources']) && $_GET['resources']!='')
{
    $display_activities = 1;
}
if(isset($_GET['people']) && $_GET['people']!='')
{
    $display_assets = 1;
}
if(isset($_GET['area']) && $_GET['area']!='')
{
    $display_people = 1;
}

    echo <<<_END
                        <form action="logs_add.php" method="post">
                        <div class="form-group">
                            <label for="area">Area</label>
                            <select name="area" class="form-control">
                                <option value="">--Select Area--</option>
_END;

$q = "SELECT * FROM areas";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sid = $res['id'];
    $sitename = $res['sitename'];
    $location = $res['location'];
    
    echo <<<_END
    <option value="$sid">$sitename ($location)</option>
_END;

}

echo <<<_END
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="people">Authorised By</label>
                            <select name="people" class="form-control">
                                <option value="">--Select People--</option>
_END;

$q = "SELECT * FROM people";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sid = $res['id'];
    $fname = $res['fname'];
    $lname = $res['lname'];
    
    echo <<<_END
    <option value="$sid">$fname $lname</option>
_END;

}

echo <<<_END
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="activity">Activity</label>
                            <select name="activity" class="form-control">
                                <option value="">--Select Activity--</option>
_END;

$q = "SELECT * FROM activities";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sid = $res['id'];
    $activity = $res['activity'];
    
    echo <<<_END
    <option value="$sid">$activity</option>
_END;

}

echo <<<_END
                            </select>
                        </div>
						<button type="submit" class="btn btn-primary">Add WorkLog</button>
                    </form>
_END;


echo <<<_END
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
