<?php
error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);

include "../focinc/dbhands.php";
include "../focinc/i_envirovar.php";
include "i_functions.php"; 
if(isset($_COOKIE["id"]))  { $id=$_COOKIE["id"]; }
?>

<?php

if($_POST['cat'] =="loadusers") {
?>
<script src="multiselect.min.js"></script>
<?php
$ForCompany = $_POST['company'];
$UserCodeName_arr = array(); 

    $query11="SELECT t1.RefUSR, t1.FirstName, t1.LastName FROM `tUser` AS t1, `tUserAccessLevels` AS t2  
              WHERE t1.RefUSR=t2.RefUSR AND t1.Status='ACT' AND t2.FCompany='$ForCompany' 
              GROUP BY t1.RefUSR ORDER BY t1.FirstName, t1.LastName "; 
    //echo '<br>-----'.$query11;
    $sql11 = mysqli_query($mysqli, $query11);
    $i=0;
    while($row11=mysqli_fetch_array($sql11))						//------------------- Store Practice ID & Full Name from database to AllPractice_arr ------
        {
        $UserCodeName_arr[$i][0]=$row11['RefUSR'];
        $UserCodeName_arr[$i][1]=$row11['FirstName'].' '.$row11['LastName'];
        // echo '<br><br> Yes '.$UserCodeName_arr[$i][0].'--'.$UserCodeName_arr[$i][1];
        $i++;
    }
?>
    <select class="total_fields forminput" onChange ="checkedvalues()" name="ForRefUSR" id="ForRefUSR" multiple>
        <?php  $i=0; 
        $maxusercodename = sizeof($UserCodeName_arr);
        while($i<$maxusercodename)
        {   
            $valueof= $UserCodeName_arr[$i][0] ; ?>
                <option value="<?php echo $valueof ?>"> <?php echo $UserCodeName_arr[$i][1] ?> </option>
        <?php  $i++; 
        } ?>
    </select>
    <script>
    	document.multiselect('#ForRefUSR')
		.setCheckBoxClick("checkboxAll", function(target, args) {
			console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
		})
		.setCheckBoxClick("1", function(target, args) {
			console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
		});
	</script>
<?php }

if($_POST['cat'] =="loadsubgrp") {
$MainGroup = $_POST['group'];
?>
<select class="total_fields forminput" name="SubGroup" id="SubGroup" >
                <option value="">----- Select -----</option>
                    <?php  
                    $SubGroupsOfMain_arr=getSubGroupOfMain($MainGroup);
                    if ($SubGroupsOfMain_arr!="") {
                    $maxtasksubgrouptitle = sizeof($SubGroupsOfMain_arr);
                    } else {
                     $maxtasksubgrouptitle=0;
                    }
                    $i=0; 
                    while($i<$maxtasksubgrouptitle)
                    {   
                        $valueof= $SubGroupsOfMain_arr[$i][0] ;
                        if ($SubGroup == $valueof) {  ?>
                            <option value="<?php echo $valueof; ?>" selected> <?php echo $SubGroupsOfMain_arr[$i][1] ?></option>
                        <?php } else{ ?>    
                            <option value="<?php echo $valueof ?>"> <?php echo $SubGroupsOfMain_arr[$i][1] ?> </option>
                    <?php  }  $i++; } ?>
</select>
<?php
}

if($_POST['cat'] =="createtask") {

$ForCompany=$_POST['ForCompany'];
$sTaskName=$_POST['sTaskName'];
$sTRecRef=$_POST['sTRecRef'];
$MainGroup=$_POST['MainGroup'];
$SubGroup=$_POST['SubGroup'];
$sTaskGroup=$_POST['sTaskGroup'];       //------ old Group
$TaskDescription=$_POST['TaskDescription'];
$ForRefUSR=$_POST['ForRefUSR'];
$ForRefUSR1=$_POST['ForRefUSR1'];
$user_array = explode(',',$ForRefUSR1);
$chkPrivateTask=$_POST['chkPrivateTask'];
$StartDate=$_POST['StartDate'];
$sqlStartDate = date('Y-m-d', strtotime($StartDate));
$DueDate=$_POST['DueDate'];
$sqlDueDate = date('Y-m-d', strtotime($DueDate));
$now = time(); // or your date as well
$datediff = strtotime($DueDate) - strtotime($StartDate);    //------------ get two dates difference
$DueDays = floor($datediff / (60 * 60 * 24));                //------------ cinvert to days
$DueDays = $DueDays+1;
$RepeatSchedule=$_POST['RepeatSchedule'];
$awdays = $_POST['awdays'];
//$awdays=implode(',', (array)$_POST['cbxDays']);		//--------------- store multiple selection in one variable, seprated by , comma sign [implode/explode]
$NextAfter=$_POST['NextAfter'];
$radioNoOfTimes=$_POST['radioNoOfTimes'];     //   	EndAfter, EndBy, NoEnd
$EndAfterOccur=$_POST['EndAfterOccur'];
$EndByDate=$_POST['EndByDate'];
$sqlEndByDate = date('Y-m-d', strtotime($EndByDate));
$Priority = $_POST["Priority"];
$FlagNewTaskSchedule=0;
/*    if ($sTRecRef!='' && $sTaskName!='')    //----- update Main and Sub Group
    {
        $query3="UPDATE `tTasks` SET `TaskMainGroup`='$MainGroup',`TaskSubGroup`='$SubGroup' WHERE `TRecRef`='$sTRecRef' " ;
        $sql3 = mysqli_query($mysqli, $query3);
    }
*/    
    if ( $sTaskName!='')    //----------- if new task title & group is added
    {
        $query3="INSERT INTO `tTasks`(`TaskMainGroup`, `TaskSubGroup`, `TaskGroup`,  `TaskTitle`, `Status`, `Priority`, `CreatedBy`, `CreatedDateTime`) 
                              VALUES ( '$MainGroup',    '$SubGroup',  '$sTaskGroup', '$sTaskName',  'ACT', '$Priority', '$id', '$currdatetime' ) " ;
        $sql3 = mysqli_query($mysqli, $query3);
        $sTRecRef= $mysqli->insert_id;          //------------ if its new task title then get last title inserted record number
    }   //---- end if new task title & group is added
    
    if ($sTRecRef!='' && $RepeatSchedule==""){
    $query4="INSERT INTO `tSchedule`(`TRecRef`, `TaskDescription`, `ForCoRecRef`, `PrivateTask`,  `StartDate`,    `DueDate`,  `DueDays`, `RepeatSchedule`, `AssignDT`, `AssignedBy`) 
                             VALUES ('$sTRecRef','$TaskDescription','$ForCompany', '$chkPrivateTask','$sqlStartDate','$sqlDueDate','$DueDays','$RepeatSchedule', '$currdatetime','$id' ) " ;
    }
    if ($sTRecRef!='' && $RepeatSchedule!=""){
    $query4="INSERT INTO `tSchedule`(`TRecRef`, `TaskDescription`, `ForCoRecRef`, `PrivateTask`,  `StartDate`,    `DueDate`,  `DueDays`, `RepeatSchedule`, `DaysInWeek`, `NextAfter`,  `NoOfTimes`,   `EndAfterOccur`, `RepeatCount`,   `StopDate`,    `AssignDT`, `AssignedBy`) 
                             VALUES ('$sTRecRef','$TaskDescription','$ForCompany', '$chkPrivateTask','$sqlStartDate','$sqlDueDate','$DueDays','$RepeatSchedule', '$awdays', '$NextAfter','$radioNoOfTimes','$EndAfterOccur','$RepeatCount','$sqlEndByDate', '$currdatetime','$id' ) " ;
    }
    $sql4 = mysqli_query($mysqli, $query4);
    if ($sql4) {$FlagNewTaskSchedule=1;} 
    $NewRecID= $mysqli->insert_id;
       
    $FlagSuccess=0;
    
    $i = 0;
    $userselectcount = sizeof($user_array);
    while($i<$userselectcount) {
    $ForRefUSR = $user_array[$i];
    
    if ($sTRecRef!='' && $RepeatSchedule=="" && $FlagNewTaskSchedule==1)    //--------- If task has NO repeat  --------------- START
    {
        $query5="INSERT INTO `tCalendar`( `SRecRef`,`TRecRef`, `ForRefUSR`, `cScheduleDate`, `cDueDate`) 
                                 VALUES ('$NewRecID','$sTRecRef', '$ForRefUSR','$sqlStartDate','$sqlDueDate' ) " ;
        $sql5 = mysqli_query($mysqli, $query5);
        if ($sql5) { $FlagSuccess=1; }
        echo $query5.'<br/>';
    }
    

    if ($RepeatSchedule!="" && $FlagNewTaskSchedule==1)         //--------- If task has NO repeat  --------------- START
    {
    //--------- If task has Repeat  ------------------ START


        if ($RepeatSchedule=="Daily")  {$increaseby="+$NextAfter day"; }
        if ($RepeatSchedule=="Weekly") {$increaseby="+$NextAfter week"; }
        if ($RepeatSchedule=="Monthly"){$increaseby="+$NextAfter month"; }
        if ($RepeatSchedule=="Yearly") {$increaseby="+$NextAfter year"; }

        $Starttimestamp = strtotime($StartDate);
        $Stoptimestamp = strtotime($EndByDate);
        //--------------------------------------Repeat NoEnd Date -------------------------------- START
        if ($radioNoOfTimes=='NoEnd')
        {
            $radioNoOfTimes='EndBy';        //----- as only increasing date till 10 years, therefore use $radioNoOfTimes=='EndBy' loop with 10 year date
            $Stoptimestamp=strtotime("+9 year", strtotime($StartDate));
        }
        //--------------------------------------Repeat NoEnd Date -------------------------------- START

        // ---------------- $radioNoOfTimes=EndAfter, EndBy, NoEnd
        //--------------------------------------Repeat EndByDate -------------------------------- START

        $uptomaxcount=0;

        if ($radioNoOfTimes=='EndBy')
        {
            for($Loopdate = $Starttimestamp; $Loopdate <= $Stoptimestamp; $Loopdate = strtotime($increaseby, $Loopdate))       //----- run loop from start date till end Date, increment by 1 day/week/month/year
            {
            $uptomaxcount++;
                //echo "<script> alert ('Tasks is Scheduled ! = $Loopdate '); </script>";
            $InsertFlag=0;
                $ThisDate = date('Y-m-d',$Loopdate);
                $ThisDay = date('D', strtotime($ThisDate));
                //echo ' '.$ThisDate.'--'.$ThisDay;
                
                if ($RepeatSchedule=="Weekly") {
                    if($ThisDay != "Mon") {
                    $mondate = date('m/d/y', strtotime('last monday',strtotime($ThisDate)));
                    }else {
                       $mondate =  $ThisDate;
                    }
                    //echo '$mondate - '. $mondate;
                    $j =0;
                    while ($j < 7) {
                    $Loopdate1 = date('Y-m-d', strtotime($mondate. ' + ' . $j .' days'));
                   // echo ' $Starttimestamp - '. date('Y-m-d',$Starttimestamp);
                   // echo ' $Loopdate1 - '. $Loopdate1;
                   // echo ' $Stoptimestamp - '. date('Y-m-d',$Stoptimestamp);
                    $ThisDay1 = date('D', strtotime($Loopdate1));
                    echo ' $ThisDay1 - '. $ThisDay1;
                    if ((strpos($awdays,$ThisDay1) !== false) && strtotime($Loopdate1) >= $Starttimestamp && strtotime($Loopdate1) <= $Stoptimestamp) {
                        if ($DueDays=='') {$DueDays=1;}
                            $DueInDays=$DueDays-1;
                            $cDueDate=date('Y-m-d',strtotime("+$DueInDays day", strtotime($Loopdate1)));
                    
                            $query6="INSERT INTO `tCalendar`( `SRecRef`,`TRecRef`, `ForRefUSR`, `cScheduleDate`, `cDueDate`) 
                                             VALUES ('$NewRecID','$sTRecRef', '$ForRefUSR','$Loopdate1','$cDueDate' ) " ;
                            echo $query6;
                            $sql6 = mysqli_query($mysqli, $query6);
                            if ($sql6) { $FlagSuccess=1; }
                    }   //----- endif selected days
                    
                    $j++;
                    }
                }   //----------- end if daily/weekly
                else {
                    $InsertFlag=1;
                }

                if ($InsertFlag==1) {
                    if ($DueDays=='') {$DueDays=1;}
                    $DueInDays=$DueDays-1;
                    $cDueDate=date('Y-m-d',strtotime("+$DueInDays day", strtotime($ThisDate)));
                    //echo '<br>INSERT='.$ThisDate.'--'.$ThisDay.'--DUE='.$cDueDate;
                    $query6="INSERT INTO `tCalendar`( `SRecRef`,`TRecRef`, `ForRefUSR`, `cScheduleDate`, `cDueDate`) 
                                             VALUES ('$NewRecID','$sTRecRef', '$ForRefUSR','$ThisDate','$cDueDate' ) " ;
                    $sql6 = mysqli_query($mysqli, $query6);
                    if ($sql6) { $FlagSuccess=1; }

                }
                
                    if ($uptomaxcount>50){break;}  //---------- safety limit if by error any dates are wrong then terminate the loop after 500 inserts
                
            }   //--------- end for loop
        } //--------- end if $radioNoOfTimes=='EndBy'
        //--------------------------------------Repeat EndByDate -------------------------------- END
        
        //--------------------------------------Repeat EndAfter -------------------------------- START
       
        if ($radioNoOfTimes=='EndAfter')
        {
            $Loopdate=$Starttimestamp;
            for($Loopcount = 1; $Loopcount <= $EndAfterOccur; $Loopcount++)
            {
            $InsertFlag=0;
                $ThisDate = date('Y-m-d',$Loopdate);
                $ThisDay = date('D', strtotime($ThisDate));
                //echo ' '.$ThisDate.'--'.$ThisDay;
                //echo "<script> alert ('Tasks is Scheduled ! = $Loopcount / Date= $Loopdate'); </script>";
                if ($RepeatSchedule=="Weekly") {
                    $Loopcount--;
                    if($ThisDay != "Mon") {
                    $mondate = date('m/d/y', strtotime('last monday',strtotime($ThisDate)));
                    } else {
                       $mondate =  $ThisDate;
                    }
                    //echo '$mondate - '. $mondate;
                    $j =0;
                    while ($j < 7) {
                    $Loopdate1 = date('Y-m-d', strtotime($mondate. ' + ' . $j .' days'));
                    //echo ' $Starttimestamp - '. date('Y-m-d',$Starttimestamp);
                    //echo ' $Loopdate1 - '. $Loopdate1;
                    //echo ' $Stoptimestamp - '. date('Y-m-d',$Stoptimestamp);
                    $ThisDay1 = date('D', strtotime($Loopdate1));
                   // echo ' $ThisDay1 - '. $ThisDay1;
                    if ((strpos($awdays,$ThisDay1) !== false) && strtotime($Loopdate1) >= $Starttimestamp && $Loopcount <= $EndAfterOccur) {
                        if ($DueDays=='') {$DueDays=1;}
                            $DueInDays=$DueDays-1;
                            $cDueDate=date('Y-m-d',strtotime("+$DueInDays day", strtotime($Loopdate1)));
                    
                            $query6="INSERT INTO `tCalendar`( `SRecRef` ,`TRecRef`, `ForRefUSR`, `cScheduleDate`, `cDueDate`) VALUES ('$NewRecID','$sTRecRef', '$ForRefUSR' ,'$Loopdate1','$cDueDate' ) " ;
                           // echo $query6;
                           // $Loopcount++;
                            $sql6 = mysqli_query($mysqli, $query6);
                            if ($sql6) { $FlagSuccess=1; $Loopcount++; }
                    }   //----- endif selected days
                    
                    $j++;
                    }
                }   //----------- end if daily/weekly
                else {
                    if ($DueDays=='') {$DueDays=1;}
                    $DueInDays=$DueDays-1;
                    $cDueDate=date('Y-m-d',strtotime("+$DueInDays day", strtotime($ThisDate)));
                    //echo '<br>INSERT='.$ThisDate.'--'.$ThisDay.'--DUE='.$cDueDate;
                    $query7="INSERT INTO `tCalendar`( `SRecRef`, `TRecRef`, `ForRefUSR`, `cScheduleDate`, `cDueDate`) 
                                             VALUES ('$NewRecID','$sTRecRef', '$ForRefUSR','$ThisDate','$cDueDate' ) " ;
                    $sql7 = mysqli_query($mysqli, $query7);
                    if ($sql7) { $FlagSuccess=1; }
                }   
            $Loopdate = strtotime($increaseby, $Loopdate);      //---------- add incremental day/week/month/year for next loop
           // echo $Loopdate;
            } //--------- end for loop
        } //--------- end if $radioNoOfTimes=='EndAfter'        
        //--------------------------------------Repeat EndAfter -------------------------------- END

        
        
    //--------- If task has Repeat  ------------------ END
        
    } //------ end if $RepeatSchedule!="" && $FlagNewTaskSchedule==1
    $i++;
    } //end while of user
        if ($FlagSuccess==1) {
            echo "<script> alert ('Tasks is Scheduled !'); document.location='paddtask.php';</script>";
        }
        else
        {
            echo "<script> alert ('Tasks is NOT Scheduled, please check criteria and try again !'); </script>";
        }



}

    

if($_POST['cat'] =="deletecurrent") {
    $ForCalid=$_POST['ForCalid'];
    $ForTaskid=$_POST['ForTaskid'];
    
    $query5="UPDATE `tCalendar` SET Status='I' WHERE `SRecRef` in (SELECT `SRecRef` FROM `tSchedule` WHERE `TRecRef`='$ForTaskid') AND  (`cScheduleDate`, `cDueDate`) IN (SELECT `cScheduleDate`, `cDueDate` FROM `tCalendar` where cRecRef = '$ForCalid') " ;
                       
    $sql5 = mysqli_query($mysqli, $query5);
    if ($sql5) {
            $query4="INSERT INTO `tTaskNotes`(  `TRecRef`, `cRecRef` ,  `Stage`,       `Notes`,       `NotesDT`,  `NotesBy`) 
                                                VALUES ('$ForTaskid','$ForCalid','SCHDELETED','Schedule Deleted!', '$currdatetime','$id' ) " ;
            $sql4 = mysqli_query($mysqli, $query4);
            echo "Schedule Deleted!";
    }
    else
    {
            echo "Schedule is NOT Deleted!";
    }
}

if($_POST['cat'] =="deletefuture") {
    $ForCalid=$_POST['ForCalid'];
    $ForTaskid=$_POST['ForTaskid'];
    
    $query5="UPDATE `tCalendar` SET Status='I'  WHERE `TRecRef`='$ForTaskid' AND  cScheduleDate > (SELECT `cScheduleDate` FROM `tCalendar` where cRecRef = '$ForCalid' ) " ;
                       
    $sql5 = mysqli_query($mysqli, $query5);
    if ($sql5) {
            $query4="INSERT INTO `tTaskNotes`(  `TRecRef`, `cRecRef` ,   `Stage`,       `Notes`,       `NotesDT`,  `NotesBy`) 
                                                VALUES ('$ForTaskid','$ForCalid','SCHDELETED','All future Schedules Deleted!', '$currdatetime','$id' ) " ;
            $sql4 = mysqli_query($mysqli, $query4);
            echo "Schedule Deleted!";
    }
    else
    {
            echo "Schedule is NOT Deleted!";
    }

}
if($_POST['cat'] =="reschedulecurrent") {
$NewStartDate = $_POST['newstartdate'];
$NewDueDate   = $_POST['newduedate'];
$sqlOldStartDate = $_POST['oldstartdate'];
$sqlOldDueDate   = $_POST['oldduedate'];
$EditTaskRef     = $_POST['ForTaskid'];
$EditCalRef     = $_POST['ForCalid'];

$sqlNewStartDate = substr($NewStartDate,6,4)."-".substr($NewStartDate,3,2)."-".substr($NewStartDate,0,2);
$sqlNewDueDate = substr($NewDueDate,6,4)."-".substr($NewDueDate,3,2)."-".substr($NewDueDate,0,2);

$query5="UPDATE `tCalendar` SET `cScheduleDate`='$sqlNewStartDate',`cDueDate`='$sqlNewDueDate'  
             WHERE `SRecRef` in (SELECT `SRecRef` FROM `tSchedule` WHERE `TRecRef`='$EditTaskRef') AND  `cScheduleDate`='$sqlOldStartDate' AND `cDueDate`='$sqlOldDueDate' " ;
                    
$sql5 = mysqli_query($mysqli, $query5);

$query4="INSERT INTO `tTaskNotes`(`TRecRef`,   `Stage`,  `Notes`, `TimeTaken`,  `NotesDT`, `NotesBy`) 
             VALUES ('$EditTaskRef','$EditCalRef','CURRRESCHEDULED','Task Rescheduled Old Start Date - $sqlOldStartDate New Start Date - $sqlNewStartDate, Old Due Date - $sqlOldDueDate New Due Date - $sqlNewDueDate ','00:00:00','$currdatetime','$id' ) " ;
$sql4 = mysqli_query($mysqli, $query4);
                        
}

if($_POST['cat'] == "addtag") {
    $tag=$_POST['tag'];
    $rowid=$_POST['row'];
    $ForTaskid=$_POST['ForTaskid'];
    $ForCalid=$_POST['ForCalid'];
    
    $query="INSERT INTO `tTaskTags`(`TaRecRef`,`cRecRef` , `RefUSR`, `TagTitle`) 
                                           VALUES ('$ForTaskid','$ForCalid','$id','$tag') " ;
    $sql = mysqli_query($mysqli, $query);
    
    $query21="SELECT * FROM `tTaskTags` WHERE `TaRecRef`='$ForTaskid' AND `RefUSR` = '$id' AND cRecRef='$ForCalid' ORDER BY `TagTitle` ";
                           //echo 'Q21='.$query21;
    $sql21 = mysqli_query($mysqli, $query21);
    while($row21=mysqli_fetch_array($sql21))                        
    {
       $ThisTRecRef=$row21['TRecRef'];
       $ThisTaskcRecRef=$row21['TaRecRef'];
       $ThisTagTitle=$row21['TagTitle'];
       echo "<a href='#' onClick='removetag($rowid,$ThisTRecRef)'><img src='../focinc/images/imgRemove.png' alt='X' height='15' width='15' border=0/></a>
      $ThisTagTitle&nbsp;<br/> ";
    } 
}

if($_POST['cat'] == "removetag") {
    $tagid=$_POST['tagid'];
    $rowid=$_POST['row'];
    $ForTaskid=$_POST['ForTaskid'];
    
    $query="DELETE FROM  `tTaskTags` WHERE TRecRef ='$tagid' " ;
    $sql = mysqli_query($mysqli, $query);
    
    $query21="SELECT * FROM `tTaskTags` WHERE `TaRecRef`='$ForTaskid' AND `RefUSR` = '$id' ORDER BY `TagTitle` ";
                           //echo 'Q21='.$query21;
    $sql21 = mysqli_query($mysqli, $query21);
    while($row21=mysqli_fetch_array($sql21))                        
    {
       $ThisTRecRef=$row21['TRecRef'];
       $ThisTaskcRecRef=$row21['TaRecRef'];
       $ThisTagTitle=$row21['TagTitle'];
       //$ThisTagTitle= ucfirst(strtolower($ThisTagTitle));
       echo "<a href='#' onClick='removetag($rowid,$ThisTRecRef)'><img src='../focinc/images/imgRemove.png' alt='X' height='15' width='15' border=0/></a>
      $ThisTagTitle&nbsp;<br/> ";
    } 
}

if($_POST['cat'] == "addnewnote") {
    $NewNote=$_POST['note'];
    $ForTaskid=$_POST['ForTaskid'];
    $ForCalid=$_POST['ForCalid'];
    $noteupTimeTaken=$_POST['noteupTimeTaken'];
    
    if($noteupTimeTaken!=""){
    $noteupTimeTaken_hm=$noteupTimeTaken;
    }else{
    $noteupTimeTaken_hm="00:00:00";    
    }
    $query4="INSERT INTO `tTaskNotes`(`TRecRef`,`cRecRef`,  `Stage`,  `Notes`, `TimeTaken`,  `NotesDT`, `NotesBy`) 
                                                VALUES ('$ForTaskid','$ForCalid','NEWNOTE','$NewNote','$noteupTimeTaken_hm','$currdatetime','$id' ) " ;
    $sql4 = mysqli_query($mysqli, $query4);
}

if($_POST['cat'] == "addnewsubnote") {
    $NewNote=$_POST['note'];
    $ForSTaskid=$_POST['ForSTaskid'];
    $ForTaskid=$_POST['ForTaskid'];
    $ForCalid=$_POST['ForCalid'];
    $noteupTimeTaken=$_POST['noteupTimeTaken'];
    
    if($noteupTimeTaken!=""){
    $noteupTimeTaken_hm=$noteupTimeTaken;
    }else{
    $noteupTimeTaken_hm="00:00:00";    
    }
    $query4="INSERT INTO `tTaskNotes`(`TRecRef`,`cRecRef`, `STRecRef`, `Stage`,  `Notes`, `TimeTaken`,  `NotesDT`, `NotesBy`) 
                                                VALUES ('$ForTaskid','$ForCalid','$ForSTaskid','SUBNEWNOTE','$NewNote','$noteupTimeTaken_hm','$currdatetime','$id' ) " ;
    $sql4 = mysqli_query($mysqli, $query4);
}

if($_POST['cat'] == "addstartnote") {
    $NewNote=$_POST['note'];
    $ForTaskid=$_POST['ForTaskid'];
    $noteid=$_POST['noteid'];
    
     $query4="UPDATE `tTaskNotes` SET `Notes`='$NewNote' WHERE NRecRef = '$noteid'" ;
     $sql4 = mysqli_query($mysqli, $query4);
}

if($_POST['cat'] == "starttime") {
    $ForTaskid=$_POST['ForTaskid'];
    $ForCalid=$_POST['ForCalid'];
    $query4="INSERT INTO `tTaskNotes`(`TRecRef`, `cRecRef`, `Stage`,  `Notes`, `TimeTaken`,  `NotesDT`, `NotesBy`) 
                                                VALUES ('$ForTaskid','$ForCalid','STARTTIME',' ','00:00:00','$currdatetime','$id' ) " ;
    $sql4 = mysqli_query($mysqli, $query4);
    $NewRecID= $mysqli->insert_id;
    
    echo "noteid_".$NewRecID;
}

if($_POST['cat'] == "substarttime") {
    $ForSTaskid=$_POST['ForSTaskid'];
    $ForTaskid=$_POST['ForTaskid'];
    $ForCalid=$_POST['ForCalid'];
    $query4="INSERT INTO `tTaskNotes`(`TRecRef`, `cRecRef`, `STRecRef`, `Stage`,  `Notes`, `TimeTaken`,  `NotesDT`, `NotesBy`) 
                                                VALUES ('$ForTaskid','$ForCalid','$ForSTaskid','SUBSTARTTIME',' ','00:00:00','$currdatetime','$id' ) " ;
    $sql4 = mysqli_query($mysqli, $query4);
    $NewRecID= $mysqli->insert_id;
    
    echo "noteid_".$NewRecID;
}

if($_POST['cat'] == "addendnote") {
    $NewNote=$_POST['note'];
    $noteid=$_POST['noteid'];
    $ForTaskid=$_POST['ForTaskid'];
    $ForCalid=$_POST['ForCalid'];
    
    $query11="SELECT `NotesDT` FROM `tTaskNotes` WHERE NRecRef = '$noteid'";
    $sql11 = mysqli_query($mysqli, $query11);
    while($row11=mysqli_fetch_array($sql11))
    {
        $starttime=new DateTime($row11['NotesDT']);
    }
    //$expiry_time = new DateTime($row['fromdb']);
    $current_date = new DateTime();
    $diff = $starttime->diff($current_date);
    $timetaken = $diff->format('%H:%I:%S');
    
    $query4="INSERT INTO `tTaskNotes`(`TRecRef`, `cRecRef` , `Stage`,  `Notes`, `TimeTaken`,  `NotesDT`, `NotesBy`) 
                                                VALUES ('$ForTaskid','$ForCalid','ENDTIME','$NewNote','$timetaken','$currdatetime','$id' ) " ;
    $sql4 = mysqli_query($mysqli, $query4);
    
}

if($_POST['cat'] == "addsubendnote") {
    $NewNote=$_POST['note'];
    $noteid=$_POST['noteid'];
    $ForTaskid=$_POST['ForTaskid'];
    $ForSTaskid=$_POST['ForSTaskid'];
    $ForCalid=$_POST['ForCalid'];
    
    $query11="SELECT `NotesDT` FROM `tTaskNotes` WHERE NRecRef = '$noteid'";
    $sql11 = mysqli_query($mysqli, $query11);
    while($row11=mysqli_fetch_array($sql11))
    {
        $starttime=new DateTime($row11['NotesDT']);
    }
    //$expiry_time = new DateTime($row['fromdb']);
    $current_date = new DateTime();
    $diff = $starttime->diff($current_date);
    $timetaken = $diff->format('%H:%I:%S');
    
    $query4="INSERT INTO `tTaskNotes`(`TRecRef`, `cRecRef` , `STRecRef`, `Stage`,  `Notes`, `TimeTaken`,  `NotesDT`, `NotesBy`) 
                                                VALUES ('$ForTaskid','$ForCalid','$ForSTaskid','SUBENDTIME','$NewNote','$timetaken','$currdatetime','$id' ) " ;
    $sql4 = mysqli_query($mysqli, $query4);
    
}

if($_POST['cat'] == "regroup") {
    $ForTaskid=$_POST['ForTaskid'];
    $oldmaingroup=$_POST['oldmaingroup'];
    $oldsubgroup=$_POST['oldsubgroup'];
    $maingroup=$_POST['maingroup'];
    $subgroup=$_POST['subgroup'];
    
    $query58="UPDATE `tTasks` SET `TaskMainGroup`='$maingroup',`TaskSubGroup`='$subgroup' WHERE `TRecRef`='$ForTaskid' " ;
    $sql58 = mysqli_query($mysqli, $query58);
    
    $query4="INSERT INTO `tTaskNotes`(`TRecRef`,   `Stage`,  `Notes`, `TimeTaken`,  `NotesDT`, `NotesBy`) 
                                                VALUES ('$ForTaskid','REGROUP','Task Regrouped!','00:00:00','$currdatetime','$id' ) " ;
    $sql4 = mysqli_query($mysqli, $query4);
    $TaskMainGroupTitle=getTaskMainGroupTitle($maingroup);
    $TaskSubGroupTitle=getTaskSubGroupTitle($subgroup);
    echo "(".$TaskMainGroupTitle." - ". $TaskSubGroupTitle.")";
}

if($_POST['cat'] == "addfile") {
    $target_path = "../uploads/SupportingDoc/";
    $ForTaskid=$_POST['ForTaskid'];
    $sDocNote = $_POST['note'];
    $AttachDoc = $_POST['attachfile'];
    $ForCalid=$_POST['ForCalid'];
    
    $path_parts = pathinfo($_FILES['attachfile']["name"]);		//--- store file name in array to get extension
    $fullfile= $_FILES['attachfile']["name"];
    
    $newfilename=$currdatetime.'_'.$fullfile ;	//--- set new file name  User+Type.extension
                    
    if(move_uploaded_file($_FILES['attachfile']['tmp_name'], $target_path.$newfilename)) {
    $query64="INSERT INTO `tTaskNotes`(`TRecRef`, `cRecRef`,  `Stage`,  `Notes`, `TimeTaken`,  `NotesDT`, `NotesBy`) 
            VALUES ('$ForTaskid','$ForCalid','FILEUPLOAD','$sDocNote','00:00:00','$currdatetime','$id' ) " ;
    $sql64 = mysqli_query($mysqli, $query64);
    $NRecRef= $mysqli->insert_id;
                    
    $query63="INSERT INTO `tTaskDocuments`( `NRecRef`, `DocName`, `DocLink`, `UploadDT`) 
               VALUES ('$NRecRef','$fullfile','$newfilename','$currdatetime') " ;
    $sql63 = mysqli_query($mysqli, $query63);
                                
    echo " Successfully uploaded ";
    } else{
    echo " There was an error uploading the file, please try again! ";
    }
}
if($_POST['cat'] == "completetask") {
    $ForCalid=$_POST['ForCalid'];
    $ForTaskid=$_POST['ForTaskid'];
    $NewCompNote = $_POST['NewCompNote'];
    $upTimeTaken = $_POST['upTimeTaken'];
    
    $upTimeTaken_hm=$upTimeTaken;

    $query5="UPDATE `tCalendar` SET Stage='Completed', CompleteBy='$id', CompleteDT='$currdatetime',TimeTaken='$upTimeTaken' WHERE cRecRef in (SELECT cRecRef from tCalendar where SRecRef in (select SRecRef from tSchedule where TRecRef='$ForTaskid') and (cScheduleDate, cDueDate)  in (select cScheduleDate, cDueDate from tCalendar where cRecRef='$ForCalid')  )  " ;
    $sql5 = mysqli_query($mysqli, $query5);
    
    $query4="INSERT INTO `tTaskNotes`(`TRecRef`,`cRecRef`, `Stage`, `Notes`, `TimeTaken`, `NotesDT`, `NotesBy`) 
                             VALUES ('$ForTaskid','$ForCalid', 'COMPLETED','$NewCompNote','$upTimeTaken_hm','$currdatetime','$id' ) " ;
    $sql4 = mysqli_query($mysqli, $query4);
    echo $query5;
}


if($_POST['cat'] == "addsubfile") {
    $target_path = "../uploads/SupportingDoc/";
    $ForTaskid=$_POST['ForTaskid'];
    $ForSTaskid=$_POST['ForSTaskid'];
    $sDocNote = $_POST['note'];
    $AttachDoc = $_POST['attachfile'];
    $ForCalid=$_POST['ForCalid'];
    
    $path_parts = pathinfo($_FILES['attachfile']["name"]);		//--- store file name in array to get extension
    $fullfile= $_FILES['attachfile']["name"];
    
    $newfilename=$currdatetime.'_'.$fullfile ;	//--- set new file name  User+Type.extension
                    
    if(move_uploaded_file($_FILES['attachfile']['tmp_name'], $target_path.$newfilename)) {
    $query64="INSERT INTO `tTaskNotes`(`TRecRef`, `cRecRef`,  `STRecRef`, `Stage`,  `Notes`, `TimeTaken`,  `NotesDT`, `NotesBy`) 
            VALUES ('$ForTaskid','$ForCalid','$ForSTaskid','FILEUPLOAD','$sDocNote','00:00:00','$currdatetime','$id' ) " ;
    $sql64 = mysqli_query($mysqli, $query64);
    $NRecRef= $mysqli->insert_id;
                    
    $query63="INSERT INTO `tTaskDocuments`( `NRecRef`, `DocName`, `DocLink`, `UploadDT`) 
               VALUES ('$NRecRef','$fullfile','$newfilename','$currdatetime') " ;
    $sql63 = mysqli_query($mysqli, $query63);
                                
    echo " Successfully uploaded ";
    } else{
    echo " There was an error uploading the file, please try again! ";
    }
}
if($_POST['cat'] == "completesubtask") {
    $ForCalid=$_POST['ForCalid'];
    $ForTaskid=$_POST['ForTaskid'];
    $ForSTaskid=$_POST['ForSTaskid'];
    $NewCompNote = $_POST['NewCompNote'];
    $upTimeTaken = $_POST['upTimeTaken'];
    
    $upTimeTaken_hm=$upTimeTaken;

    $query5="UPDATE `tSubTasks` SET Stage='Completed', CompleteBy='$id', CompleteDT='$currdatetime',TimeTaken='$upTimeTaken' WHERE  STRecRef ='$ForSTaskid' " ;
    $sql5 = mysqli_query($mysqli, $query5);
    
    $query4="INSERT INTO `tTaskNotes`(`TRecRef`,`cRecRef`,`STRecRef`, `Stage`, `Notes`, `TimeTaken`, `NotesDT`, `NotesBy`) 
                             VALUES ('$ForTaskid','$ForCalid','$ForSTaskid', 'COMPLETED','$NewCompNote','$upTimeTaken_hm','$currdatetime','$id' ) " ;
    $sql4 = mysqli_query($mysqli, $query4);
    echo $query5;
}

if($_POST['cat'] == "quicktaskpopup") {
        
    $CompanyCode_arr = array();
        $query11="SELECT t1.*,t2.CoRecRef,t2.CoCode,t2.CoName FROM `tUserAccessLevels` as t1, `tCompany` AS t2 
                  WHERE t1.FCompany=t2.CoRecRef AND t2.CoType='COMPANY' AND t2.Status='ACT' AND t1.RefUSR='$id'
                  GROUP BY t1.FCompany ORDER BY t2.CoName ";
        $sql11 = mysqli_query($mysqli, $query11);
        $i=0;
        while($row11=mysqli_fetch_array($sql11))
        {
            $CompanyCode_arr[$i][0]=$row11['CoRecRef'];
            $CompanyCode_arr[$i][1]=$row11['CoCode'];
            $CompanyCode_arr[$i][2]=$row11['CoName'];
            $i++;
        }
	$maxcompanycode = sizeof($CompanyCode_arr);
?>
<br/>
<div class="labelcust" style='width:85px'>Company*: </div>
           <select class="total_fields forminput" name="ForCompany" id="ForCompany" style='width:290px'>
            <option value="">----- Select -----</option>
                        <?php  
                        $maxcompanycode = sizeof($CompanyCode_arr);
                        $i=0; 
                        while($i<$maxcompanycode)
                        {   
                            $valueof= $CompanyCode_arr[$i][0] ;
                            ?>    
                                <option value="<?php echo $valueof ?>"> <?php echo $CompanyCode_arr[$i][2] ?> </option>
                        <?php  $i++; } ?>
                </select>   <br clear="all"/>  <br clear="all"/>
           
    <div class="labelcust" style='width:85px'>Task Title*: </div>
    <input type=text class="total_fields forminput" name="sTaskName" id="sTaskName" value="" style='width:290px' /><br clear="all"/>  <br clear="all"/>
<!--    <div class="labelcust" style='width:85px'>Main Group*: </div>
    <select class="total_fields forminput" name="MainGroup" id="MainGroup" onchange="loadsubgrp()" style='width:290px'>
                <option value="">----- Select Main Group-----</option>
                    <?php  
                    $maxtaskmaingrouptitle = sizeof($AllTaskMainGroups_arr);
                    $i=0; 
                    while($i<$maxtaskmaingrouptitle)
                    {   
                        $valueof= $AllTaskMainGroups_arr[$i][0] ;
                     ?>    
                        <option value="<?php echo $valueof ?>"> <?php echo $AllTaskMainGroups_arr[$i][1] ?> </option>
                    <?php   $i++; } ?>
    </select><br clear="all"/>  <br clear="all"/>
    <div class="labelcust" style='width:85px'>Sub Group*: </div>
    <div id="subgroup">
       <select class="total_fields forminput" name="SubGroup" id="SubGroup" style='width:290px'>
                <option value="">----- Select Main Group First-----</option>
       </select>
    </div><br clear="all"/>  <br clear="all"/>
-->
    <div class="labelcust" style='width:85px'>Description:</div> 
    <textarea name=TaskDescription class="total_fields forminput" id=TaskDescription style="vertical-align: top;height:60px;border-radius:4px;width:290px" rows=3  placeholder='Please provide task detail' ></textarea>
    <br clear="all"/><br clear="all"/><br clear="all"/>  <br clear="all"/>
    <input type=button name="btnSave"  value="Save" style="margin-left:10%;font-weight:bold;width:100px" class='btn' onclick="quicktask()" />
<?php
}

if($_POST['cat'] == "quicktask") {
$sTaskName= $_POST['task'];
$ForCompany=$_POST['company'];
$TaskDescription=$_POST['descr'];
//$TaskMainGroup=$_POST['TaskMainGroup'];
//$TaskSubGroup=$_POST['TaskSubGroup'];

$query3="INSERT INTO `tTasks`(`TaskMainGroup`, `TaskSubGroup`, `TaskGroup`,  `TaskTitle`, `Status`, `Priority`, `CreatedBy`, `CreatedDateTime`) 
                              VALUES ( '0',    '0',  '0', '$sTaskName',  'ACT', 'P3', '$id', '$currdatetime' ) " ;
$sql3 = mysqli_query($mysqli, $query3);
$sTRecRef= $mysqli->insert_id;

$query4="INSERT INTO `tSchedule`(`TRecRef`, `TaskDescription`, `ForCoRecRef`,  `PrivateTask`,  `StartDate`,    `DueDate`,  `DueDays`, `RepeatSchedule`, `AssignDT`, `AssignedBy`) 
                             VALUES ('$sTRecRef','$TaskDescription','$ForCompany', '0','$current_date','$current_date','1','', '$currdatetime','$id' ) " ;
$sql4 = mysqli_query($mysqli, $query4);
$NewRecID= $mysqli->insert_id;

$query5="INSERT INTO `tCalendar`( `SRecRef`, `TRecRef`, `ForRefUSR` ,`cScheduleDate`, `cDueDate`) 
                                 VALUES ('$NewRecID', '$sTRecRef' ,'$id','$current_date','$current_date' ) " ;
$sql5 = mysqli_query($mysqli, $query5);
    
}

if($_POST['cat'] == "subtaskpopup") {
  $ForCalendarid= $_POST['ForCalendarid'];
  $query = "SELECT TRecRef from tCalendar where cRecRef= '$ForCalendarid' limit 1";
  $sql = mysqli_query($mysqli, $query);
  $row=mysqli_fetch_array($sql);
  $ForTaskid=$row['TRecRef'];

  echo "<input type=hidden id=taskidsub value='$ForTaskid' />";
  echo "<input type=hidden id=calendaridsub value='$ForCalendarid' />";
  echo "<span style='font-size:16px;font-weight:bold'>Task#$ForTaskid</span><br clear='all'><br clear='all'>";      
  $assigneduser=array();
    $x=0;
    $query3011 = "SELECT ForRefUSR from tCalendar where TRecRef='$ForTaskid' and (cScheduleDate,cDueDate)=(select cScheduleDate,cDueDate from tCalendar where cRecRef='$ForCalendarid') and Status='A' ";
    $sql3011 = mysqli_query($mysqli, $query3011);
    while($row3011=mysqli_fetch_array($sql3011))
    {
        $ForRefUSR=$row3011['ForRefUSR'];
        $query31="SELECT `RefUSR`, `FirstName`, `LastName`  FROM `tUser` WHERE `RefUSR`='$ForRefUSR' ";
        $sql31 = mysqli_query($mysqli, $query31);
        while($row31 = mysqli_fetch_array($sql31)){
        $UserRef   =$row31["RefUSR"];
        $FirstName  =$row31["FirstName"];
        $LastName  =$row31["LastName"];
        $FullName=$FirstName.' '.$LastName;
        $FullName1=$FirstName.$LastName;
        $FullName=ucwords(strtolower($FullName)); //----- convert to UpperLower Case
        }
        $assigneduser[$x][0]=$UserRef;
        $assigneduser[$x][1]=$FullName;
        $x++;
    }
?>
<br/>
           
    <div class="labelcust">Sub Task: </div>
    <input type=text class="total_fields forminput" name="sTaskName" id="sTaskName" value="" /><br clear="all"/>  <br clear="all"/>
    <div class="labelcust">Assign to:</div>
    <?php 
    $i=0;$maxassigneduser=sizeof($assigneduser);$multiple="";
    echo "<input type=hidden id=usermultiple value='$maxassigneduser' />";
    if ($maxassigneduser > 1) { 
        $multiple = "multiple"; 
    } 
    echo "<select name=selNewUser".$celnodv." style='width:200px;float:left;margin-left:5px' id=ForRefUSRSTsk class='total_fields' $multiple>";
            while($i<$maxassigneduser)
            {   
                echo "<option value=".$assigneduser[$i][0]." $selected> ".$assigneduser[$i][1]."</option>";
                $i++; 
            }
    echo "</select>";
    ?>
    <script>
        document.multiselect('#ForRefUSRSTsk')
        .setCheckBoxClick('checkboxAll', function(target, args) {  })
        .setCheckBoxClick('1', function(target, args) {   });
        document.getElementById("ForRefUSRSTsk_itemList").style.width ="300px";
    </script>
    <br clear='all'><br clear='all'>
    <div class="labelcust">Description:</div> 
    <textarea name=TaskDescription class="total_fields forminput" id=TaskDescription style="vertical-align: top;height:60px;border-radius:4px" rows=3  placeholder='Please provide task detail'></textarea>
    <br clear="all"/><br clear="all"/>
    <div class="labelcust">Priority:</div>
      <select class="forminput total_fields" name="priority" id="priority" style='float:left;padding:3px;border-radius:3px;width:120px;margin-left:5px'>
                <option value="P3" >P3 - Low</option>
                <option value="P2" >P2 - Medium</option>
                <option value="P1" >P1 - High</option>
       </select>
    
    <input type=button name="btnSave"  value="Save" style="margin:40px 50px;font-weight:bold;width:100px" class='btn' onclick="addsubtask()" />
     <br clear="all"/>  <br clear="all"/><br clear="all"/>  <br clear="all"/>       
<?php
}

if($_POST['cat'] == "addsubtask") {
$ForCalendarid= $_POST['ForCalendarid'];
$ForTaskid= $_POST['ForTaskid'];
$taskdescr=$_POST['taskdescr'];
$priority=$_POST['priority'];
$subname =$_POST['subname'];
$users = $_POST['selected'];

$query1="INSERT INTO `tSubTasks`(`TRecRef`, `TaskTitle`, `Descr`, `Status`, `Priority`, `CreatedBy`, `CreatedDateTime`) 
            VALUES ('$ForTaskid','$subname','$taskdescr','ACT', '$priority', '$id' , '$currdatetime')";
$sql1 = mysqli_query($mysqli, $query1);
$STRecRef= $mysqli->insert_id;  
$user_array = explode(',',$users);

$userselectcount = sizeof($user_array);
$i=0;
$userlist ='';
while($i<$userselectcount) {
$ForRefUSRR = $user_array[$i];
$query2 = "INSERT INTO `tSubTaskCal` (`STRecRef`, `cRecRef`, `TRecRef`, `SRecRef`, `ForRefUSR`, `UpdateDT`, `UpdateBy`) VALUES 
            ('$STRecRef',(SELECT cRecRef from tCalendar where SRecRef=(select SRecRef from tSchedule where TRecRef='$ForTaskid') and ForRefUSR='$ForRefUSRR'
        and  (`cScheduleDate`,`cDueDate`) in (select `cScheduleDate`,`cDueDate` from tCalendar where cRecRef='$ForCalendarid')),'$ForTaskid',(select SRecRef from tSchedule where TRecRef='$ForTaskid'), '$ForRefUSRR', '$currdatetime','$id') ";
$sql2 = mysqli_query($mysqli, $query2);
$i++;
//$a.=$query1;
}
$query3="INSERT INTO `tTaskNotes`(`TRecRef`, `cRecRef`, `STRecRef` ,`Stage`, `Notes`, `TimeTaken`, `NotesDT`, `NotesBy`) 
                             VALUES ('$ForTaskid', '$ForCalendarid' ,'$STRecRef','SUBTASKADD','Sub Task#$STRecRef - $subname','00:00:00','$currdatetime','$id' ) " ;
$sql3 = mysqli_query($mysqli, $query3);
//echo $a;
}

if($_POST['cat'] == "updatetask") {
$ForTaskid= $_POST['ForTaskid'];
$taskdescr=$_POST['taskdescr'];
$priority=$_POST['priority'];
$private =$_POST['private'];

$query3="UPDATE `tTasks` SET `Priority`='$priority' WHERE TRecRef ='$ForTaskid'";
$sql3 = mysqli_query($mysqli, $query3);

$query4="UPDATE `tSchedule` SET `TaskDescription`='$taskdescr', `PrivateTask`='$private' WHERE TRecRef ='$ForTaskid'";
$sql4 = mysqli_query($mysqli, $query4);

$query5="INSERT INTO `tTaskNotes`(`TRecRef`, `Stage`, `Notes`, `TimeTaken`, `NotesDT`, `NotesBy`) 
                             VALUES ('$ForTaskid','TASKUPDATED','Task Updated!','00:00:00','$currdatetime','$id' ) " ;
$sql5 = mysqli_query($mysqli, $query5);

}

if($_POST['cat'] == "notestyle") {

$ForTaskNote= $_POST['ForTaskNote'];
$style=$_POST['style'];    

$query3="UPDATE `tTaskNotes` SET `Style`='$style' WHERE NRecRef ='$ForTaskNote'";
$sql3 = mysqli_query($mysqli, $query3);
}

if($_POST['cat'] == "reassignuser") {
$ForScheduleid=$_POST['ForScheduleid'];
$userid=$_POST['userid'];
$ForTaskid=$_POST['ForTaskid'];
$ForCalid=$_POST['ForCalid'];
$optid=$_POST['optid'];
$query="select FirstName,LastName from tUser where  RefUSR = (select `ForRefUSR` from tCalendar where cRecRef='$ForCalid')";
$sql = mysqli_query($mysqli, $query);
$row=mysqli_fetch_array($sql);
$olduser=$row['FirstName'] . " ". $row['LastName'];

$query1="select FirstName,LastName from tUser where  RefUSR = '$userid'";
$sql1 = mysqli_query($mysqli, $query1);
$row1=mysqli_fetch_array($sql1);
$newuser=$row1['FirstName'] . " ". $row1['LastName'];

if($optid=="current") {
$query5="UPDATE `tCalendar` SET `ForRefUSR`='$userid'  WHERE SRecRef='$ForScheduleid' and TRecRef='$ForTaskid' and cRecRef='$ForCalid'" ;
}else {
$query5="UPDATE `tCalendar` SET `ForRefUSR`='$userid'  WHERE SRecRef='$ForScheduleid' and TRecRef='$ForTaskid' and `ForRefUSR` =(select `ForRefUSR` from tCalendar where cRecRef='$ForCalid') and Stage<>'Completed' and cScheduleDate >= (select cScheduleDate from tCalendar where cRecRef='$ForCalid')" ;
}
$sql5 = mysqli_query($mysqli, $query5);
$query4="INSERT INTO `tTaskNotes`(  `TRecRef`, `cRecRef`,     `Stage`,       `Notes`,       `NotesDT`,  `NotesBy`) 
                                                VALUES ('$ForTaskid','$ForCalid','TRANSFER','Task Transfered from $olduser to $newuser!', '$currdatetime','$id' ) " ;
$sql4 = mysqli_query($mysqli, $query4);
                        
}

if($_POST['cat'] == "reassign") {
$selectedusers = $_POST['userid'];
$EditTaskRef=$_POST['ForTaskid'];
$ForCalid=$_POST['ForCalid'];
$ForScheduleid=$_POST['ForScheduleid'];
$optid=$_POST['optid'];
$user_array = explode(',',$selectedusers);

$userselectcount = sizeof($user_array);
$i=0;
$userlist ='';
while($i<$userselectcount) {
$ForRefUSRR = $user_array[$i];
$userlist.= ",'".$ForRefUSRR."'";

if($optid=="current") {
    $query55="INSERT INTO `tCalendar`( `SRecRef`, `TRecRef`, `ForRefUSR`, `cScheduleDate`, `cDueDate`) 
                SELECT DISTINCT '$ForScheduleid', '$EditTaskRef', '$ForRefUSRR',`cScheduleDate`, `cDueDate` FROM `tCalendar` WHERE TRecRef='$EditTaskRef' AND SRecRef='$ForScheduleid'  and (cScheduleDate,cDueDate) =(select cScheduleDate,cDueDate from tCalendar where cRecRef='$ForCalid') AND  NOT EXISTS (SELECT 'X' FROM `tCalendar` WHERE TRecRef='$EditTaskRef' AND ForRefUSR='$ForRefUSRR'  AND SRecRef='$ForScheduleid' AND Stage<>'Completed' and (cScheduleDate,cDueDate) =(select cScheduleDate,cDueDate from tCalendar where cRecRef='$ForCalid')) " ;
      
} else {
     $query55="INSERT INTO `tCalendar`( `SRecRef`, `TRecRef`, `ForRefUSR`, `cScheduleDate`, `cDueDate`) 
                SELECT DISTINCT '$ForScheduleid', '$EditTaskRef', '$ForRefUSRR',`cScheduleDate`, `cDueDate` FROM `tCalendar` as c1 WHERE TRecRef='$EditTaskRef' AND SRecRef='$ForScheduleid' AND Stage<>'Completed' and cScheduleDate >= (select cScheduleDate from tCalendar where cRecRef='$ForCalid') AND  NOT EXISTS (SELECT 'X' FROM `tCalendar` as c2 WHERE TRecRef='$EditTaskRef' AND ForRefUSR='$ForRefUSRR'  AND SRecRef='$ForScheduleid' AND Stage<>'Completed' and c1.cScheduleDate=c2.cScheduleDate) " ;
                
}
     $sql55 = mysqli_query($mysqli, $query55);
     $NewRecID= $mysqli->insert_id;
    if ($NewRecID !='') {
    $newuser ="true";
    }
    $i=$i+1;
}
$userlist = substr($userlist,1);
if($optid=="current") {
$query555 = "UPDATE `tCalendar` SET Status='I' where `ForRefUSR` NOT IN ($userlist)  and SRecRef='$ForScheduleid' and TRecRef='$EditTaskRef' and (cScheduleDate,cDueDate) =(select cScheduleDate,cDueDate from tCalendar where cRecRef='$ForCalid') and Stage <> 'Completed'" ;
}else {
$query555 = "UPDATE `tCalendar` SET Status='I' WHERE SRecRef='$ForScheduleid' and TRecRef='$EditTaskRef' AND `ForRefUSR` NOT IN ($userlist) and Stage <> 'Completed' and cScheduleDate >= (select cScheduleDate from tCalendar where cRecRef='$ForCalid')";
}
$sql555 = mysqli_query($mysqli, $query555);



$query4="INSERT INTO `tTaskNotes`(  `TRecRef`, `cRecRef` ,    `Stage`,       `Notes`,       `NotesDT`,  `NotesBy`) 
                        VALUES ('$EditTaskRef', '$ForCalid', 'REASSIGNED','Task assigned list updated', '$currdatetime','$id' ) " ;
$sql4 = mysqli_query($mysqli, $query4);

}

if($_POST['cat'] == "reassignsubuser") {
$ForScheduleid=$_POST['ForScheduleid'];
$userid=$_POST['userid'];
$ForSTaskid=$_POST['ForSTaskid'];
$ForTaskid=$_POST['ForTaskid'];
$ForCalid=$_POST['ForCalid'];

$query="select FirstName,LastName from tUser where  RefUSR = (select `ForRefUSR` from tCalendar where cRecRef='$ForCalid')";
$sql = mysqli_query($mysqli, $query);
$row=mysqli_fetch_array($sql);
$olduser=$row['FirstName'] . " ". $row['LastName'];

$query1="select FirstName,LastName from tUser where  RefUSR = '$userid'";
$sql1 = mysqli_query($mysqli, $query1);
$row1=mysqli_fetch_array($sql1);
$newuser=$row1['FirstName'] . " ". $row1['LastName'];

$query5="UPDATE `tSubTaskCal` SET `ForRefUSR`='$userid',cRecRef=(select cRecRef from tCalendar where ForRefUSR='$userid' and TRecRef='$ForTaskid' and (cScheduleDate,cDueDate) =(select cScheduleDate,cDueDate from tCalendar where cRecRef='$ForCalid'))  WHERE SRecRef='$ForScheduleid' and STRecRef='$ForSTaskid' and TRecRef='$ForTaskid' and cRecRef='$ForCalid'" ;

$sql5 = mysqli_query($mysqli, $query5);
$query4="INSERT INTO `tTaskNotes`(  `TRecRef`, `cRecRef`,   `STRecRef`,  `Stage`,       `Notes`,       `NotesDT`,  `NotesBy`) 
                                                VALUES ('$ForTaskid','$ForCalid','$ForSTaskid','TRANSFER','Sub Task Transfered from $olduser to $newuser!', '$currdatetime','$id' ) " ;
$sql4 = mysqli_query($mysqli, $query4);
                        
}

if($_POST['cat'] == "reassignsub") {
$selectedusers = $_POST['userid'];
$EditTaskRef=$_POST['ForTaskid'];
$EditSTaskRef=$_POST['ForSTaskid'];
$ForCalid=$_POST['ForCalid'];
$ForScheduleid=$_POST['ForScheduleid'];
$user_array = explode(',',$selectedusers);

$userselectcount = sizeof($user_array);
$i=0;
$userlist ='';
while($i<$userselectcount) {
$ForRefUSRR = $user_array[$i];
$userlist.= ",'".$ForRefUSRR."'";

    $query55="INSERT INTO `tSubTaskCal`( `STRecRef`, `SRecRef`, `TRecRef`, `cRecRef`, `ForRefUSR`, `UpdateDT`, `UpdateBy`) 
                        SELECT DISTINCT '$EditSTaskRef', '$ForScheduleid', '$EditTaskRef', `cRecRef`, '$ForRefUSRR','$currdatetime', '$id' FROM `tCalendar` WHERE TRecRef='$EditTaskRef' AND SRecRef='$ForScheduleid'  and ForRefUSR='$ForRefUSRR' and (cScheduleDate,cDueDate) =(select cScheduleDate,cDueDate from tCalendar where cRecRef='$ForCalid') AND  NOT EXISTS (SELECT 'X' FROM `tSubTaskCal` WHERE TRecRef='$EditTaskRef' and STRecRef='$EditSTaskRef' AND ForRefUSR='$ForRefUSRR'  AND SRecRef='$ForScheduleid' AND Status='A')";
      
     $sql55 = mysqli_query($mysqli, $query55);
     $NewRecID= $mysqli->insert_id;
    if ($NewRecID !='') {
    $newuser ="true";
    }
    $i=$i+1;
}
$userlist = substr($userlist,1);

$query555 = "UPDATE `tSubTaskCal` SET Status='I' where `ForRefUSR` NOT IN ($userlist)  and SRecRef='$ForScheduleid' and TRecRef='$EditTaskRef' and STRecRef='$EditSTaskRef'" ;

$sql555 = mysqli_query($mysqli, $query555);



$query4="INSERT INTO `tTaskNotes`(  `TRecRef`, `cRecRef` , `STRecRef`,   `Stage`,       `Notes`,       `NotesDT`,  `NotesBy`) 
                        VALUES ('$EditTaskRef', '$ForCalid', '$EditSTaskRef', 'REASSIGNED','Sub Task assigned list updated', '$currdatetime','$id' ) " ;
$sql4 = mysqli_query($mysqli, $query4);

}

if($_POST['cat'] == "subtasknotes") { 
$ForTaskid=$_POST['ForTaskid'];
$ForSTaskid=$_POST['ForSTaskid'];
$ForCalid=$_POST['ForCalid'];


$query301="SELECT t1.* FROM `tSubTasks` AS t1 limit 1";
        $sql301 = mysqli_query($mysqli, $query301);    
        $existCount301 = mysqli_num_rows($sql301);
        //echo '<br>QUERY---'.$existCount301.'-----'.$query301;
        if ($existCount301>0){
            while($row301=mysqli_fetch_array($sql301))
            {   
                $sTaskTitle=$row301['TaskTitle'];
                $sTaskDescription=$row301['Descr'];
                $sPriority=$row301['Priority'];
                $Stagesub=$row301['Stage'];
                $sP1Selected=" ";$sP2Selected=" ";$sP3Selected=" ";
                if ($sPriority =="P3") { $sP3Selected =" selected"; }
                if ($sPriority =="P2") { $sP2Selected =" selected"; }
                if ($sPriority =="P1") { $sP1Selected =" selected"; }
                if ($Stagesub =="Completed") {$readonly ="readonly"; $disabled="disabled";}
            }
            
            $x=0;
            $query3011 = "SELECT ForRefUSR from tSubTaskCal where STRecRef='$ForSTaskid'";
            $sql3011 = mysqli_query($mysqli, $query3011);
            while($row3011=mysqli_fetch_array($sql3011))
            {
                $ForRefUSR=$row3011['ForRefUSR'];
                $query31="SELECT `RefUSR`, `FirstName`, `LastName`  FROM `tUser` WHERE `RefUSR`='$ForRefUSR' ";
                $sql31 = mysqli_query($mysqli, $query31);
                while($row31 = mysqli_fetch_array($sql31)){
                $UserRef   =$row31["RefUSR"];
                $FirstName  =$row31["FirstName"];
                $LastName  =$row31["LastName"];
                $FullName=$FirstName.' '.$LastName;
                $FullName=ucwords(strtolower($FullName)); //----- convert to UpperLower Case
                }
            $sForUserFullName.=', ' .$FullName;
            $x++;
            }
            $sForUserFullName = substr($sForUserFullName,2);
        }

echo "<span style='font-size:16px;font-weight:bold'>Task#$ForTaskid</span><br clear='all'><br clear='all'>";
?>
<div style='width:100px;float:left;text-align:left'>Task: </div> <div style='float:left;line-height:1;text-align:left;width:275px'><?php echo $sTaskTitle ?></div><br clear='all'><br clear='all'>
<div style='width:100px;float:left;text-align:left'>Assign to: </div> <div style='float:left;line-height:1;text-align:left;width:275px'><?php echo $sForUserFullName ?></div><br clear='all'><br clear='all'>
<div style='width:100px;float:left;text-align:left'>Priority: </div> <div style='display:inline;float:left;'>
      <select class="forminput" name="priority" id="priority" style='padding:3px;border-radius:3px;width:120px' <?php echo $disabled ?>>
                <option value="P3" <?php echo $sP3Selected ?> >P3 - Low</option>
                <option value="P2" <?php echo $sP2Selected ?> >P2 - Medium</option>
                <option value="P1" <?php echo $sP1Selected ?> >P1 - High</option>
       </select></div><br clear='all'><br clear='all'>
<div style='width:100px;float:left;text-align:left'>Description: </div> <div style='display:inline;float:left'><textarea id='taskdescr' rows=5 style='width:250px;border-radius:3px' <?php echo $readonly ?>><?php echo $sTaskDescription ?></textarea></div><br clear='all'><br clear='all'>
<?php if ($Stagesub !="Completed") { ?>
<br clear="all"/><br clear="all"/>
<input type=button name="btnSave"  value="Save" style="font-weight:bold;width:100px" class='btn' onclick="updatesubtask(<?php echo $ForSTaskid ?>)" />
<?php } ?>
<br clear="all"/><br clear="all"/>
<div class="maindiv1" id="center" style="width:100%">
    <?php 
        
        $tdbottomborder=" style='border-bottom:1pt solid green;' " ;

 
        //$query304="SELECT * FROM `tTaskNotes` WHERE cRecRef='$urlRecEdit' ORDER BY NotesDT DESC ";
        $query304="SELECT t2.*,time(t2.NotesDT) as time, t1.dRecRef, t1.DocName, t1.DocLink, t1.UploadDT FROM tTaskNotes AS t2 LEFT JOIN tTaskDocuments t1 ON t1.NRecRef=t2.NRecRef where t2.STRecRef='$ForSTaskid' ORDER BY NotesDT DESC";
        $sql304 = mysqli_query($mysqli, $query304);    
        $existCount304 = mysqli_num_rows($sql304);
        //echo '<br><br>---'.$existCount304.'-----'.$query304;
        if ($existCount304>0){
            echo "<br><table id=history cellpadding=2 cellspacing=2 width=100%>
                        <tr bgcolor=#5DADE2><td align=left><b>HISTORY</b></td><td align=center>&nbsp;</td><td align=center></td></tr>";
            while($row304=mysqli_fetch_array($sql304))
                {
                    $nNotes=$row304['Notes'];
                    $NRecRef=$row304['NRecRef'];
                    $nsqlNotesDT=$row304['NotesDT'];
                    $nsTimeTaken=$row304['TimeTaken'];
                    $nNotesDT = date('d-M-Y H:i:s', strtotime($nsqlNotesDT));
                    $nNotesBy=$row304['NotesBy'];
                    $nNotesByName=getUserFullName($nNotesBy);
                    $TimeSpent=$TimeSpent+$nsTimeTaken;
                    $hDocName=$row304['DocName'];
                    $hDocLink=$row304['DocLink'];
                    $target_path = "../uploads/SupportingDoc/";
                    $showfielink=$target_path.$hDocLink;
                    $style =$row304['Style'];
                    $stage=$row304['Stage'];
                    $noteadd='';
                    $noteadd1='';
                    if ($stage =='SUBSTARTTIME') {
                        $starttime=$row304['time'];
                        $noteadd = "Sub Task Started on ".$nNotesDT.'<br/><br/>';
                    }
                    if ($stage =='SUBENDTIME') {
                        $endtime=$row304['time'];
                        $noteadd = "Sub Task Ended on ".$nNotesDT.". Time Taken - ".$nsTimeTaken.'<br/><br/>';
                    }
                    if ($stage =='SUBNEWNOTE') {
                        
                        if ($nsTimeTaken!=""){
                        $noteadd1 = "<br/><br/>Time Taken - ".$nsTimeTaken;
                        }
                    }
                    if ($style == "N") { $styling ="font-style: normal; font-weight: 400;text-decoration: none;color:black"; }
                    if ($style == "B") { $styling ="font-style: normal; font-weight: 900;text-decoration: none;color:black"; }
                    if ($style == "C") { $styling ="font-style: normal; font-weight: 400;text-decoration: none;color:red"; }
                    if ($style == "I") { $styling ="font-style: italic; font-weight: 400;text-decoration: none;color:black"; }
                    if ($style == "U") { $styling ="font-style: normal; font-weight: 400;text-decoration: underline;color:black"; }
                    
                    echo "<tr><td colspan=3 style='line-height:1.2'><span id='note$NRecRef' style='$styling'>$noteadd $nNotes $noteadd1</span> <br/><br/><a href='$showfielink' style='line-height:1.2' target=_blank>$hDocName</a></td></tr>
                        <tr><td style='line-height:1.2' >$nNotesDT</td><td align=center>$nNotesByName</td>
                        <td align=right>
                            <a href='#' onclick=notestyle('B','$NRecRef') title='Bold'><b>B</b></a>
                            <a href='#' onclick=notestyle('I','$NRecRef') title='Italic'> <i>I</i></a>
                            <a href='#' onclick=notestyle('U','$NRecRef') title='Underline'> <u>U</u></a>
                            <a href='#' onclick=notestyle('C','$NRecRef') title='Color' style='color:red'> C</a>
                            <a href='#' onclick=notestyle('N','$NRecRef') title='Normal'> N</a>
                        </td>
                        </tr>
                        <tr><td colspan=3 '.$tdbottomborder.'></td></tr>";

                }   //---- end while  
                echo "</table><br><br>";
        }   //------- end if $existCount304
        
                
    ?>
</div>
<?php 
}


if($_POST['cat'] == "tasknotes") { 
$ForTaskid=$_POST['ForTaskid'];
$ForCalid=$_POST['ForCalid'];


$query301="SELECT t1.*,t2.*,t3.* FROM `tTasks` AS t1, `tSchedule` AS t2, `tCalendar` as t3 WHERE t1.TRecRef='$ForTaskid' AND t2.TRecRef=t1.TRecRef AND t3.cRecRef='$ForCalid' limit 1";
        $sql301 = mysqli_query($mysqli, $query301);    
        $existCount301 = mysqli_num_rows($sql301);
        //echo '<br>QUERY---'.$existCount301.'-----'.$query301;
        if ($existCount301>0){
            while($row301=mysqli_fetch_array($sql301))
            {   
                $TaskTitle=$row301['TaskTitle'];
                $CoCode=$row301['ForCoRecRef'];
                $query11="SELECT t2.CoName FROM `tCompany` AS t2 WHERE t2.CoType='COMPANY' AND t2.Status='ACT' AND t2.CoRecRef='$CoCode'";
                $sql11 = mysqli_query($mysqli, $query11);
                $row11=mysqli_fetch_array($sql11);
                $CoName=$row11['CoName'];
                $TaskMainGroup=$row301['TaskMainGroup'];
                $TaskMainGroupTitle=getTaskMainGroupTitle($TaskMainGroup);
                $TaskSubGroup=$row301['TaskSubGroup'];
                $TaskSubGroupTitle=getTaskSubGroupTitle($TaskSubGroup);
                $csqlScheduleDate=$row301['StartDate'];
                $cScheduleDate = date('d M-Y', strtotime($csqlScheduleDate));
                $csqlDueDate=$row301['DueDate'];
                $Stagesub=$row301['Stage'];
                $cDueDate = date('d M-Y', strtotime($csqlDueDate));
                $TaskDescription=$row301['TaskDescription'];
                $Priority=$row301['Priority'];
                $P1Selected=" ";$P2Selected=" ";$P3Selected=" ";
                if ($Priority =="P3") { $P3Selected =" selected"; }
                if ($Priority =="P2") { $P2Selected =" selected"; }
                if ($Priority =="P1") { $P1Selected =" selected"; }
                if ($Stagesub =="Completed") {$readonly ="readonly"; $disabled="disabled";}
                $PrivateTask = $row301['PrivateTask'];
                $checked=' ';
                if ($PrivateTask =="1") { $checked =" checked"; }
            }
            
            $x=0;
            $query3011 = "SELECT ForRefUSR from tCalendar where TRecRef='$ForTaskid' and (cScheduleDate,cDueDate) = (select cScheduleDate,cDueDate from tCalendar where cRecRef='$ForCalid') ";
            $sql3011 = mysqli_query($mysqli, $query3011);
            while($row3011=mysqli_fetch_array($sql3011))
            {
                $ForRefUSR=$row3011['ForRefUSR'];
                $query31="SELECT `RefUSR`, `FirstName`, `LastName`  FROM `tUser` WHERE `RefUSR`='$ForRefUSR' ";
                $sql31 = mysqli_query($mysqli, $query31);
                while($row31 = mysqli_fetch_array($sql31)){
                $UserRef   =$row31["RefUSR"];
                $FirstName  =$row31["FirstName"];
                $LastName  =$row31["LastName"];
                $FullName=$FirstName.' '.$LastName;
                $FullName=ucwords(strtolower($FullName)); //----- convert to UpperLower Case
                }
            $ForUserFullName.=', ' .$FullName;
            $x++;
            }
            $ForUserFullName = substr($ForUserFullName,2);
        }

echo "<span style='font-size:16px;font-weight:bold'>Task#$ForTaskid</span><br clear='all'><br clear='all'>";
?>
<div style='width:100px;float:left;text-align:left'>Company: </div> <div style='display:inline;float:left'><?php echo $CoName ?></div><br clear='all'><br clear='all'>
<div style='width:100px;float:left;text-align:left'>Assign to: </div> <div style='float:left;line-height:1;text-align:left;width:275px'><?php echo $ForUserFullName ?></div><br clear='all'><br clear='all'>
<div style='width:100px;float:left;text-align:left'>Task: </div> <div style='float:left;line-height:1;text-align:left;width:275px'><?php echo $TaskTitle ?></div><br clear='all'><br clear='all'>
<div style='width:100px;float:left;text-align:left'>Main Group: </div> <div style='display:inline;float:left'><?php echo $TaskMainGroupTitle ?></div><br clear='all'><br clear='all'>
<div style='width:100px;float:left;text-align:left'>Sub Group: </div> <div style='display:inline;float:left'><?php echo $TaskSubGroupTitle ?></div><br clear='all'><br clear='all'>
<div style='width:100px;float:left;text-align:left'>Start Date: </div> <div style='display:inline;float:left'><?php echo $cScheduleDate ?></div><br clear='all'><br clear='all'>
<div style='width:100px;float:left;text-align:left'>Due Date: </div> <div style='display:inline;float:left'><?php echo $cDueDate ?></div><br clear='all'><br clear='all'>

<div style='width:100px;float:left;text-align:left'>Priority: </div> <div style='display:inline;float:left;'>
      <select class="forminput" name="priority" id="priority" style='padding:3px;border-radius:3px;width:120px' <?php echo $disabled ?>>
                <option value="P3" <?php echo $P3Selected ?> >P3 - Low</option>
                <option value="P2" <?php echo $P2Selected ?> >P2 - Medium</option>
                <option value="P1" <?php echo $P1Selected ?> >P1 - High</option>
       </select></div><br clear='all'><br clear='all'>
<div style='width:100px;float:left;text-align:left'>Description: </div> <div style='display:inline;float:left'><textarea id='taskdescr' rows=5 style='width:250px;border-radius:3px' <?php echo $readonly ?>><?php echo $TaskDescription ?></textarea></div><br clear='all'><br clear='all'>
<div style='width:100px;float:left;text-align:left'>Private Task: </div> <div style='display:inline;float:left'><input type="checkbox" class="" name="chkPrivateTask" id="chkPrivateTask" value="" <?php echo $checked ?> <?php echo $disabled ?>></input></div><br clear='all'><br clear='all'>
<?php if ($Stagesub !="Completed") { ?>
<br clear="all"/><br clear="all"/>
<input type=button name="btnSave"  value="Save" style="font-weight:bold;width:100px" class='btn' onclick="updatetask(<?php echo $ForTaskid ?>)" />
<?php } ?>
<br clear="all"/><br clear="all"/>
<div class="maindiv1" id="center" style="width:100%">
    <?php 
        
        $tdbottomborder=" style='border-bottom:1pt solid green;' " ;

 
        //$query304="SELECT * FROM `tTaskNotes` WHERE cRecRef='$urlRecEdit' ORDER BY NotesDT DESC ";
        $query304="SELECT t2.*,time(t2.NotesDT) as time, t1.dRecRef, t1.DocName, t1.DocLink, t1.UploadDT FROM tTaskNotes AS t2 LEFT JOIN tTaskDocuments t1 ON t1.NRecRef=t2.NRecRef where t2.TRecRef='$ForTaskid' and cRecRef in (SELECT cRecRef from tCalendar where SRecRef in (select SRecRef from tSchedule where TRecRef='$ForTaskid') and (cScheduleDate, cDueDate)  in (select cScheduleDate, cDueDate from tCalendar where cRecRef='$ForCalid')  )ORDER BY NotesDT DESC";
        $sql304 = mysqli_query($mysqli, $query304);    
        $existCount304 = mysqli_num_rows($sql304);
        //echo '<br><br>---'.$existCount304.'-----'.$query304;
        if ($existCount304>0){
            echo "<br><table id=history cellpadding=2 cellspacing=2 width=100%>
                        <tr bgcolor=#5DADE2><td align=left><b>HISTORY</b></td><td align=center>&nbsp;</td><td align=center></td></tr>";
            while($row304=mysqli_fetch_array($sql304))
                {
                    $nNotes=$row304['Notes'];
                    $NRecRef=$row304['NRecRef'];
                    $nsqlNotesDT=$row304['NotesDT'];
                    $nsTimeTaken=$row304['TimeTaken'];
                    $nNotesDT = date('d-M-Y H:i:s', strtotime($nsqlNotesDT));
                    $nNotesBy=$row304['NotesBy'];
                    $nNotesByName=getUserFullName($nNotesBy);
                    $TimeSpent=$TimeSpent+$nsTimeTaken;
                    $hDocName=$row304['DocName'];
                    $hDocLink=$row304['DocLink'];
                    $target_path = "../uploads/SupportingDoc/";
                    $showfielink=$target_path.$hDocLink;
                    $style =$row304['Style'];
                    $stage=$row304['Stage'];
                    $noteadd='';
                    $noteadd1='';
                    if ($stage =='STARTTIME') {
                        $starttime=$row304['time'];
                        $noteadd = "Task Started on ".$nNotesDT.'<br/><br/>';
                    }
                    if ($stage =='ENDTIME') {
                        $endtime=$row304['time'];
                        $noteadd = "Task Ended on ".$nNotesDT.". Time Taken - ".$nsTimeTaken.'<br/><br/>';
                    }
                    if ($stage =='NEWNOTE' || $stage =='SUBNEWNOTE') {
                        
                        if ($nsTimeTaken!=""){
                        $noteadd1 = "<br/><br/>Time Taken - ".$nsTimeTaken;
                        }
                    }
                    
                    if ($stage =='COMPLETED') {
                        
                        if ($nsTimeTaken!=""){
                        $noteadd1 = "<br/><br/>Time Taken - ".$nsTimeTaken;
                        }
                    }
                    
                    if ($stage =='SUBSTARTTIME' ) {
                        $starttime=$row304['time'];
                        $noteadd = "Sub Task Started on ".$nNotesDT.'<br/><br/>';
                    }
                    if ($stage =='SUBENDTIME') {
                        $endtime=$row304['time'];
                        $noteadd = "Sub Task Ended on ".$nNotesDT.". Time Taken - ".$nsTimeTaken.'<br/><br/>';
                    }
                
                    if ($style == "N") { $styling ="font-style: normal; font-weight: 400;text-decoration: none;color:black"; }
                    if ($style == "B") { $styling ="font-style: normal; font-weight: 900;text-decoration: none;color:black"; }
                    if ($style == "C") { $styling ="font-style: normal; font-weight: 400;text-decoration: none;color:red"; }
                    if ($style == "I") { $styling ="font-style: italic; font-weight: 400;text-decoration: none;color:black"; }
                    if ($style == "U") { $styling ="font-style: normal; font-weight: 400;text-decoration: underline;color:black"; }
                    
                    echo "<tr><td colspan=3 style='line-height:1.2'><span id='note$NRecRef' style='$styling'>$noteadd $nNotes $noteadd1</span> <br/><br/><a href='$showfielink' style='line-height:1.2' target=_blank>$hDocName</a></td></tr>
                        <tr><td style='line-height:1.2' >$nNotesDT</td><td align=center>$nNotesByName</td>
                        <td align=right>
                            <a href='#' onclick=notestyle('B','$NRecRef') title='Bold'><b>B</b></a>
                            <a href='#' onclick=notestyle('I','$NRecRef') title='Italic'> <i>I</i></a>
                            <a href='#' onclick=notestyle('U','$NRecRef') title='Underline'> <u>U</u></a>
                            <a href='#' onclick=notestyle('C','$NRecRef') title='Color' style='color:red'> C</a>
                            <a href='#' onclick=notestyle('N','$NRecRef') title='Normal'> N</a>
                        </td>
                        </tr>
                        <tr><td colspan=3 '.$tdbottomborder.'></td></tr>";

                }   //---- end while  
                echo "</table><br><br>";
        }   //------- end if $existCount304
        
                
    ?>
</div>
<?php 
}
?>