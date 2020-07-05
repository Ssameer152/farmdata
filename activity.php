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
                    <h2>Activity</h2>
                    <form action="activity_add.php" method="post">
                        <div class="form-group">
                            <label for="activityname">Title</label>
                            <input type="text" name="activity" class="form-control">
                        </div>
						<button type="submit" class="btn btn-primary">Add Activity</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

$q = "SELECT * FROM activities";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sn = $res['id'];
    $name = $res['activity'];
    
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$name</td>
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
