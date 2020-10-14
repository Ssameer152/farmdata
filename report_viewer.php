<?php
session_start();

if (isset($_SESSION['user'])) {
    include_once 'db.php';


    function getDimensionValue($db, $table, $gid, $name)
    {
        $q = "SELECT * FROM $table WHERE id=$gid";
        $r = mysqli_query($db, $q);

        $res = mysqli_fetch_assoc($r);

        $value = $res[$name];

        return $value;
    }


    if (isset($_GET['areas']) && $_GET['areas'] != '' && isset($_GET['dates']) && $_GET['dates'] != '') {
        $area = $_GET['areas'];
        $date = $_GET['dates'];

        $q = "SELECT * FROM logs WHERE cast(doe as date)='$date' and area=$area and is_deleted=0";
        $r = mysqli_query($db, $q);
        echo <<<_END
<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
        <style>
        @media print { 
            header,#report { 
               display:none; 
            } 
         } 
         </style>
    </head>
_END;
        include_once 'nav.php';
        echo <<<_END
    <body>    
_END;



        $dt = date("d-m-Y", strtotime($date));
        echo <<<_END

		<div class="container">
            <div class="row">
            <h3>$dt</h3>
            <button class="btn btn-primary"  style="position: absolute; right:120;" onclick="window.print()">Print Report</button>
        
_END;

        $row = mysqli_num_rows($r);
        if ($row > 0) {
            while ($res = mysqli_fetch_assoc($r)) {
                $activity = getDimensionValue($db, 'activities', $res['activity'], 'activity');
                $authorizer = $res['people'];
                $status = $res['status'];
                $logid = $res['id'];

                echo <<<_END
            <div class="col-lg-12">
                <h2>$activity</h2>
                <hr>
_END;

                $q2 = "SELECT * FROM log_resource WHERE logid='$logid' and is_deleted=0";
                $r2 = mysqli_query($db, $q2);

                $row2 = mysqli_num_rows($r2);

                if ($row2 > 0) {
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
                    while ($re2 = mysqli_fetch_assoc($r2)) {
                        $resourceid = getDimensionValue($db, 'resources', $re2['resourceid'], 'resourcename');
                        $person = getDimensionValue($db, 'people', $re2['person'], 'fname') . ' ' . getDimensionValue($db, 'people', $re2['person'], 'lname');
                        $qty = $re2['qty'] . ' ' .  getDimensionValue($db, 'resources', $re2['resourceid'], 'unit');
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
                } else {
                    echo <<<_END
    <h4>Resources Utilized</h4>
    <p>No resources were utilized in this activity.</p>
_END;
                }

                // output

                $q2 = "SELECT * FROM log_output WHERE logid='$logid' and is_deleted=0";
                $r3 = mysqli_query($db, $q2);

                $row3 = mysqli_num_rows($r3);

                if ($row3 > 0) {
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
                    while ($re3 = mysqli_fetch_assoc($r3)) {
                        $resourceid = getDimensionValue($db, 'resources', $re3['resourceid'], 'resourcename');
                        $qty = $re3['qty'] . ' ' .  getDimensionValue($db, 'resources', $re3['resourceid'], 'unit');
                        $person = getDimensionValue($db, 'people', $re3['person'], 'fname') . ' ' . getDimensionValue($db, 'people', $re3['person'], 'lname');
                        $comments = $re3['comments'];
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
                } else {
                    echo <<<_END
    <h4>Output Generated</h4>
    <p>No output was generated in this activity.</p>
_END;
                }

                // end of output


                // assets

                $q4 = "SELECT * FROM log_assets WHERE logid='$logid' and is_deleted=0";
                $r4 = mysqli_query($db, $q4);

                $row4 = mysqli_num_rows($r4);

                if ($row4 > 0) {
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
                    while ($re4 = mysqli_fetch_assoc($r4)) {
                        $asset = getDimensionValue($db, 'assets', $re4['assetid'], 'assetname');
                        $qty = $re4['usage_time'];
                        $person = getDimensionValue($db, 'people', $re4['person'], 'fname') . ' ' . getDimensionValue($db, 'people', $re4['person'], 'lname');
                        $comments = $re4['comments'];
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
                } else {
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
        } else {
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
    } else {
        $msg = "Please select a work log";
        echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
    }
} else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
