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
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
            <style>
        @media print { 
            header,#report,#btn{ 
               display:none; 
            } 
            #t{
                border: solid white !important;
            }
         } 
         </style>
        </head>
        
        <body>    
_END;
include_once 'nav.php';
    echo <<<_END
        <div class="container">
        <div class="row">
        <div class="col-lg-12" id="report">
        <h3>Stock Report(Inventory)</h3>
        <form action="stock_report.php" method="get">
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="date" class="form-control" name="start_date">
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Show Report</button>
                    </form>
                    </div>
_END;
if( isset($_GET['start_date'])  && $_GET['start_date']!='')
{
    $start_date = mysqli_real_escape_string($db,$_GET['start_date']);
   
    
    echo <<<_END
        <div class="col-lg-12">
        <div class="row">
<h4 class="mb-4"> $start_date </h4>
<button class="btn btn-primary" id="btn" style="position: absolute;right:10;" onclick="window.print()">Print Report</button>
</div>
        <div class="row">
        <div class="table table-responsive">
        <table class="table table-bordered">
        <thead>
        <tr>
        <th>Sno.</th>
        <th>Resource</th>
        <th>Consumed</th>
        <th>Produced</th>
        <th>Balance</th>
        </tr>
        </thead>
        <tbody>
_END;
    $q4="SELECT * from logs where cast(doe as date)<='$start_date' and is_deleted=0";
    $r4=mysqli_query($db,$q4);
    while($res4=mysqli_fetch_assoc($r4)){
    $q="SELECT * from resources where is_deleted=0";
    $r=mysqli_query($db,$q);
    while($res=mysqli_fetch_assoc($r)){
        $id=$res['id'];
        $resource=$res['resourcename'];
        $unit=$res['unit'];
        echo <<<_END
        <tr>
        <td>$id</td>
        <td>$resource</td>     
_END;
    }
        $q1="SELECT t.qty,t.doe ,COALESCE(sum(t.qty),0) as q1 from (select qty,doe from log_resource where resourceid='$id' and is_deleted=0 and cast(doe as date)='$start_date') as t";
        $r1=mysqli_query($db,$q1);
        while($res1=mysqli_fetch_assoc($r1)){
            $consumed=$res1['q1'];
                echo <<<_END
                <td>$consumed $unit</td>
_END;
    }
        
        $q3="SELECT t.qty,t.doe ,COALESCE(sum(t.qty),0) as q3 from (select qty,doe from log_output where resourceid='$id'  and is_deleted=0 and cast(doe as date)='$start_date') as t";
        $r3=mysqli_query($db,$q3);
        while($res3=mysqli_fetch_assoc($r3)){
            $produced=$res3['q3'];
            $left=$produced-$consumed;
            echo <<<_END
            <td>$produced $unit</td>
            <td>$left $unit</td>
            </tr>
_END;
        }
    }
}
else{
    echo <<<_END
        <p>No record found</p>
_END;
}
    
include_once 'foot.php';
    echo <<<_END
            </tbody>
        </table>
        </div>
        </div>
        </div>
        </div>
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