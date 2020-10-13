<?php

include_once 'db.php';

$q = "SELECT * FROM activities WHERE is_daily=1 AND is_deleted=0";
$r = mysqli_query($db,$q);

$row = mysqli_num_rows($r);

if($row>0){
    while($res = mysqli_fetch_assoc($r)){
        $activity_id = $res['id'];
        
        $q2 = "SELECT * FROM logs WHERE activity='$activity_id' AND area=1 and CAST(doe as DATE)=CAST(CURRENT_TIMESTAMP() AS DATE)";
        $r2 = mysqli_query($db,$q2);
        
        $row2 = mysqli_num_rows($r2);
        
        if($row2==0){
            $q3 = "INSERT INTO logs(area,activity,people) VALUES(1,'$activity_id',3)";
            $r3 = mysqli_query($db,$q3);
            echo 'Record Inserted';
        }
        else{
            echo 'Record Exist';
        }
        
    }
}

?>