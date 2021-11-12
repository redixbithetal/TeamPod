<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
error_reporting (E_ALL ^ E_NOTICE);
include("taskheader.php");

/*
 * ................. FOR ptoday.php/PALLITEMS.php change following in same code ..............
    *  change ptoday.php to pallitem.php
    *  add following statement in ptoday for $query301
                 AND ( DATE(t3.cScheduleDate)<=CURDATE() )
 * .............................................................................
 */
$DateStart='2019-01-01';
$DateToday= date('Y-m-d');
$DateTomorrow = date ("Y-m-d", strtotime("+1 day", strtotime($DateToday)));

//echo '<br>DateToday'.$DateToday.'----DateTomorrow='.$DateTomorrow.'----';

            $target_path = "../uploads/SupportingDoc/";

//echo 'print coockies= '; print_r($_COOKIE);
if(isset($_COOKIE["name"]))           { $loginame=$_COOKIE["name"]; }
if(isset($_COOKIE["id"]))             { $id=$_COOKIE["id"]; }
if(isset($_COOKIE["CanSystemAdmin"])) { $CanSystemAdmin=$_COOKIE["CanSystemAdmin"]; }
//echo '<br>UserID='.$id.'...UserName='.$loginame.'...SysAdmin='.$CanSystemAdmin.'...AccessLevel='.$AccessLevel ;
GLOBAL $AccessLevel;

//$ALarrSize=sizeof($AccessLevel);


// $AccessLevelCO=$AccessLevel[0][0];   //--- company name
// $AccessLevelAL=$AccessLevel[0][1];   //--- access level
//    echo "<script> alert ('ID=$id / NAME=$loginame / AL=$AccessLevel / $AccessLevelCO = $AccessLevelAL <$arsi>');</script>";


$ForCompany=$_POST['ForCompany'];
$ForRefUSR=$_POST['ForRefUSR'];

$ViewListForFD=$_POST['ViewListForFD'];



//echo '<br>LINE 35--CO=('.$ForCompany.')----USR=('.$ForRefUSR.')';


$ForCompany='ALL';
$_SESSION['ForCompany']='ALL';
$ForRefUSR=$id;
$_SESSION['ForRefUSR']=$id;

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) 
{
    $_SESSION['ForCompany']=$_POST['ForCompany'];
    $_SESSION['ForRefUSR']=$_POST['ForRefUSR'];
    $_SESSION['MainGroup']=$_POST['MainGroup'];
    $_SESSION['SubGroup']=$_POST['SubGroup'];
    $_SESSION['ForTaskTag']=$_POST['ForTaskTag'];
    $_SESSION['chkViewCompleted']=$_POST['chkViewCompleted'];
    
    
}

//echo '<br>LINE 60--CO=('.$ForCompany.')----USR=('.$ForRefUSR.')----FCCriteria-'.$FCCriteria;

$ForCompany=$_SESSION["ForCompany"];
$ForRefUSR=$_SESSION["ForRefUSR"];
$MainGroup=$_SESSION["MainGroup"];
$SubGroup=$_SESSION["SubGroup"];
//$ForTaskGroup=$_SESSION["ForTaskGroup"];
$ForTaskTag=$_SESSION["ForTaskTag"];
$chkViewCompleted=$_SESSION["chkViewCompleted"];


//if ($ForRefUSR=='') {$ForRefUSR='ALL';}     //-------- if first time load the page then default show all users tasks


//echo '<br>LINE 70--CO=('.$ForCompany.')----USR=('.$ForRefUSR.')----FCCriteria-'.$FCCriteria;

    $urlRecEdit=$_GET['ET'];
    //echo $urlRecEdit;

    $urlETLSC=$_GET['ETLSC'];   // Start Stop Clock/watch for RecRef Calander
    $urlETLSS=$_GET['ETLSS'];   // Start Stop Clock/watch for RecRef Schedule
    $urlSSC=$_GET['SSC'];     // Start Stop Clock/watch


    $DTTTRecRef=$_GET['DTTT'];
                        //----------- REMOVE TAG for this task
    if ($DTTTRecRef!='')
    {
        $query36="DELETE FROM `tTaskTags` WHERE `TRecRef`='$DTTTRecRef' AND RefUSR ='$id'" ;
        $sql36 = mysqli_query($mysqli, $query36);
        
        echo "<script>document.location='ptoday.php?ET=$urlRecEdit';</script>";
    }   //---- end if REMOVE TAG for this task




if ($chkViewCompleted=='YES') {$IsChekedMark='checked';} else {$IsChekedMark='';}


if ($ForCompany=='ALL' && $ForRefUSR!='')     { $FCCriteria.=" AND t2.FCompany IN (SELECT FCompany FROM `tUserAccessLevels` WHERE RefUSR='$id' AND `AccessLevel` LIKE '%ADMNDASH%' ) ";}
else { 
                                        //-------------- if one company selected then check the user access level 
                        $LVLCriteria='';
                        for ($k=0;$k<$ALarrSize;$k++)
                        {
                            //echo '<br>CO='.$AccessLevel[1][$k];
                            if($AccessLevel[$k][0]==$ForCompany)            //-------- if company found then exit loop
                                break;
                        }
                        //echo '<br>LEVEL='.$AccessLevel[$k][1];            //-------- get the access level = if its only a user then only selecte myself
                        if ($AccessLevel[$k][1]=='ALPROCES,') { $LVLCriteria=" AND t2.RefUSR='$id' ";}
    
        $FCCriteria = " AND t2.FCompany='$ForCompany' $LVLCriteria "; 
    
                        }   //--- end elseif $ForCompany

//echo '<br>LINE 155--CO=('.$ForCompany.')----USR=('.$ForRefUSR.')----FCCriteria-'.$FCCriteria;
                        
                        
        $AllCompanyCode_arr = array();             //---------------- Get all File Codes from database table then when require only show specific codes in select statement

        $i=0;
            $AllCompanyCode_arr[$i][0]='ALL';
            $AllCompanyCode_arr[$i][2]='All Companies';
        $query11="SELECT t1.*,t2.CoRecRef,t2.CoCode,t2.CoName FROM `tUserAccessLevels` as t1, `tCompany` AS t2 
                  WHERE t1.FCompany=t2.CoRecRef AND t2.CoType='COMPANY' AND t2.Status='ACT' AND t1.RefUSR='$id'
                  GROUP BY t1.FCompany ORDER BY t2.CoName ";
        $sql11 = mysqli_query($mysqli, $query11);
        $i=1;
        while($row11=mysqli_fetch_array($sql11))						//------------------- Store Practice ID & Full Name from database to AllPractice_arr ------
            {
            $AllCompanyCode_arr[$i][0]=$row11['CoRecRef'];
            $AllCompanyCode_arr[$i][1]=$row11['CoCode'];
            $AllCompanyCode_arr[$i][2]=$row11['CoName'];
            $i++;
            }
	$maxcompanycode = sizeof($AllCompanyCode_arr);

        
    $UserCodeName_arr = array(); 
    $i=0;
            $UserCodeName_arr[$i][0]=$id;
            $UserCodeName_arr[$i][1]='my Tasks';
/*    $i=1;
            $UserCodeName_arr[$i][0]='ALL';
            $UserCodeName_arr[$i][1]='All Users';
 */   
    $query11="SELECT t1.RefUSR, t1.FirstName, t1.LastName FROM `tUser` AS t1, `tUserAccessLevels` AS t2  
              WHERE t1.RefUSR=t2.RefUSR AND t1.Status='ACT' $FCCriteria  
              GROUP BY t1.RefUSR ORDER BY t1.FirstName, t1.LastName "; 
    //echo '<br>-----'.$query11;
    $sql11 = mysqli_query($mysqli, $query11);
    $i=1;
    while($row11=mysqli_fetch_array($sql11))						//------------------- Store Practice ID & Full Name from database to AllPractice_arr ------
        {
        $UserCodeName_arr[$i][0]=$row11['RefUSR'];
        $UserCodeName_arr[$i][1]=$row11['FirstName'].' '.$row11['LastName'];
        //echo ' Yes '.$UserCodeName_arr[$i][0];
        $i++;
        }
    
	$maxusercodename = sizeof($UserCodeName_arr);


        
    $UserNameOnly_arr = array(); 
    $i=0;
    $query11="SELECT t1.RefUSR, t1.FirstName,t1.LastName FROM `tUser` AS t1, `tUserAccessLevels` AS t2  
              WHERE t1.RefUSR=t2.RefUSR AND t1.Status='ACT' $FCCriteria  
              GROUP BY t1.RefUSR ORDER BY t1.FirstName "; 
    //echo '<br>-----'.$query11;
    $sql11 = mysqli_query($mysqli, $query11);
    while($row11=mysqli_fetch_array($sql11))						//------------------- Store Practice ID & Full Name from database to AllPractice_arr ------
        {
        $UserNameOnly_arr[$i][0]=$row11['RefUSR'];
        $UserNameOnly_arr[$i][1]=$row11['FirstName'].' '.$row11['LastName'];
        //echo ' Yes '.$UserCodeName_arr[$i][0];
        $i++;
        }
    
	$maxusernameonly = sizeof($UserNameOnly_arr);

                                         
$AllTasksNameList = array();                                           
    $query1="SELECT `TRecRef`, `TaskGroup`, `TaskTitle` FROM `tTasks` WHERE Status='ACT' ORDER BY `TaskTitle`";
    $sql1 = mysqli_query($mysqli, $query1);
    while($row1=mysqli_fetch_array($sql1))                        
    {
        $AllTasksNameList[]=array(
            'value'=>$row1['TRecRef'],
            'label'=> ucfirst(strtolower($row1['TaskGroup']))." - ".ucfirst(strtolower($row1['TaskTitle']))
            );
    } 

    
    
$AllTasksTagList = array();  
    $query1="SELECT DISTINCT `TagTitle` FROM `tTaskTags` WHERE RefUSR='$id' ORDER BY `TagTitle` ";
    $sql1 = mysqli_query($mysqli, $query1);
    while($row1=mysqli_fetch_array($sql1))                        
    {
        $AllTasksTagList[]=array(
            'value'=>$row1['TagTitle'],
            'label'=> ucfirst(strtolower($row1['TagTitle']))
            );
    } 
    
        
?>


<html>
<head>
<title>Team Pod</title>
<style>

#odtasks{
        background-image:url('images/OverdueTasks.svg');
        background-position:5% 8px;
        background-repeat:no-repeat;
    }

#tdtasks{
        background-image:url('images/TodayTasks.svg');
        background-position:5% 8px;
        background-repeat:no-repeat;
    }
#tmtasks{
        background-image:url('images/TomorrowTasks.svg');
        background-position:5% 8px;
        background-repeat:no-repeat;
    }
body {
  background-color: rgba(189, 189, 189, 0.25);
}

/*.tab {
  overflow: hidden;
  border: 0px solid #ccc;
  background-color: #eee;
}

/* Style the buttons inside the tab 
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover 
.tab button:hover {
  background-color: #ddd;
  border-radius:10px 10px 0px 0px;
}

/* Create an active/current tablink class 
.tab button:active {
  background-color: #ccc;
}*/

#Nav_AddTask {  background: #eee;  background-size: 70px 38px;  border: 2px solid #5DADE2; width: 70px; height: 42px;}
#Nav_AddTask:hover {  background: #999;  background-size: 70px 40px;}
</style>
   
<link rel="shortcut icon" type="image/png" href="images/icontask.png"/>
<link rel="stylesheet" type="text/css" href="newstyle.css"></link>
    
	<!--Requirement jQuery-->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<!--Load Script and Stylesheet -->
	<script type="text/javascript" src="jquery.simple-dtpicker.js"></script>
	<link type="text/css" href="jquery.simple-dtpicker.css" rel="stylesheet" />
	<!---->
        
        
  <script>
$(function() {
  $( ".datepicker" ).datepicker({ dateFormat: "dd/mm/yy" });
}); 
  </script>
        
        
            <!-- --------------- START Auto Complete Text from Array -->
	<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/ui-darkness/jquery-ui.min.css" rel="stylesheet">
	<!-- DO NOT USE, As it is already used in Date Validation <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>  -->
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
        
<script language=javascript>
var h=0;
var m=0;
var s=0;
function to_start(){

switch(document.getElementById('btn').value)
{
case  'Stop Timer':
window.clearInterval(tm); // stop the timer 
document.getElementById('btn').value='Start Timer';
break;
case  'Start Timer':
tm=window.setInterval('disp()',1000);
document.getElementById('btn').value='Stop Timer';
break;
}
}


function disp(){
// Format the output by adding 0 if it is single digit //
if(s<10){var s1='0' + s;}
else{var s1=s;}
if(m<10){var m1='0' + m;}
else{var m1=m;}
if(h<10){var h1='0' + h;}
else{var h1=h;}
// Display the output //
str= h1 + ':' + m1 +':' + s1 ;
document.getElementById('upTimeTaken').value=str;
// Calculate the stop watch // 
if(s<59){ 
s=s+1;
}else{
s=0;
m=m+1;
if(m==60){
m=0;
h=h+1;
} // end if  m ==60
}// end if else s < 59
// end of calculation for next display

}
</script>

            
            
 <script>
             
        var complextask = <?php echo json_encode($AllTasksNameList); ?>;
        datatask=complextask;
        $(function() {
                $("#NewTaskName").autocomplete({
                        source: datatask,
                        focus: function(event, ui) {
                                // prevent autocomplete from updating the textbox
                                event.preventDefault();
                                // manually update the textbox
                                $(this).val(ui.item.label);
                        },
                        select: function(event, ui) {
                                // prevent autocomplete from updating the textbox
                                event.preventDefault();
                                // manually update the textbox and hidden field
                                $(this).val(ui.item.label);
                                $("#sTRecRefNew").val(ui.item.value);
                                //$("#sTRecRef-value").val(ui.item.value);
                        }
                });
        });


</script>
        
        
<script>

function ShowList(tday)
{
    document.getElementById("ViewListForFD").value=tday;
    document.getElementById("TaskMgmt").submit(); 
}


</script>

</head>

<body>

    
<form action="" name="TaskMgmt" id="TaskMgmt" method="post" enctype="multipart/form-data" target="_self" >  
    <div style="position:fixed;top:50%;left:30%;font-size:20px;padding:15px;border: solid 1px #300000;border-radius:5px;background-color: #000;color:#fff;display:none;z-index:10000;text-align:center" class ="successmsg"></div>
    <input type=hidden name=AddNewBtnClick id=AddNewBtnClick value="" />
    <input type=hidden name=ViewListForFD id=ViewListForFD value="<?php echo $ViewListForFD;?>" />
<?php    if ($urlRecEdit=='') { 

include "taskfilter.php";
?>

<div class="maindiv" id="wrapper" style="margin-left:16%;width:84%;margin-top:10px"><div id="floatleft" style="width:100%;float:left">
            
<div style="background-color: #f0f0f0">
        
 <?php       $FTMGCriteria=''; $FTSGCriteria=''; $FTGCriteria='';
        
        $FCCriteria=" AND t2.ForCoRecRef IN (SELECT FCompany FROM `tUserAccessLevels` WHERE RefUSR='$id' AND ( `AccessLevel` LIKE '%ALPROCES%' ) ) ";
        if ($chkViewCompleted=='YES') {$FCMPCriteria=" AND t3.CompleteBy!='' "; } else  {$FCMPCriteria=" AND t3.CompleteBy='' "; }
        if ($ForCompany=='ALL' && $ForRefUSR=='ALL') {$FUCriteria=""; }
        if ($ForCompany=='ALL' && $ForRefUSR!='ALL') { $FUCriteria=" AND t3.ForRefUSR='$ForRefUSR' "; }
    //    if ($ForCompany=='ALL' && $ForRefUSR!='ALL') {$FCCriteria=" AND t2.ForCoRecRef IN (SELECT FCompany FROM `tUserAccessLevels` WHERE RefUSR='$id' ) ";}
        if ($ForCompany!='ALL' && $ForRefUSR=='ALL') {$FUCriteria=" AND t2.ForCoRecRef=$ForCompany "; }
        if ($ForCompany!='ALL' && $ForRefUSR!='ALL') {$FUCriteria=" AND t2.ForCoRecRef=$ForCompany AND t3.ForRefUSR='$ForRefUSR'  ";}
        if ($MainGroup!='') {$FTMGCriteria.=" AND t1.TaskMainGroup='$MainGroup' ";}
        if ($SubGroup!='') {$FTSGCriteria.=" AND t1.TaskSubGroup='$SubGroup' ";}
        //if ($ForTaskGroup!='') {$FTGCriteria.=" AND t1.TaskGroup='$ForTaskGroup' ";}
       // if ($ForTaskTag!='') {$FTTagCriteria.=" AND t1.TRecRef IN ( SELECT TaRecRef FROM `tTaskTags` WHERE TagTitle='$ForTaskTag' )  ";}
        if ($ForTaskTag!='') {$FTTagCriteria.=" AND t3.cRecRef IN ( SELECT cRecRef FROM `tTaskTags` WHERE TagTitle='$ForTaskTag'  AND RefUSR ='$id') ";}
        //echo '<br>LINE 325--CO=('.$ForCompany.')----USR=('.$ForRefUSR.')<br>//---FCCriteria=('.$FCCriteria.')<br>//---FUCriteria=('.$FUCriteria.')';

        $OverDueCount=0; $TodaysCount=0; $TomorrowCount=0; $celnodv=0;$TRecRefold='';
        
        
        
                                //----------------------- START Over Due, Today & Tomorrow Task List --------------------------- START
        $query301="SELECT t1.*,t2.*,t3.* FROM `tTasks` AS t1, `tSchedule` AS t2, `tCalendar` AS t3 
                    WHERE t1.TRecRef=t2.TRecRef AND t2.SRecRef=t3.SRecRef $FCCriteria $FUCriteria $FTMGCriteria $FTSGCriteria $FCMPCriteria $FTTagCriteria 
                    AND ( DATE(t3.cScheduleDate) BETWEEN '$DateStart' AND '$DateTomorrow' ) AND t3.Status='A'
                    ORDER BY t3.cScheduleDate,t1.TRecRef LIMIT 200";
        
        $sql301 = mysqli_query($mysqli, $query301);    
        $existCount301 = mysqli_num_rows($sql301);
        //echo '<br>QUERY---'.$existCount301.'-----'.$query301;
        if ($existCount301>0){
            while($row301=mysqli_fetch_array($sql301))
                {
                    $TRecRef=$row301['TRecRef'];
                    $csqlScheduleDate=$row301['cScheduleDate'];
                    $csqlDueDate=$row301['cDueDate'];
                    if (($TRecRef !== $TRecRefold) or ($TRecRef == $TRecRefold && $csqlScheduleDateold !== $csqlScheduleDate && $csqlDueDate !==$csqlDueDateold)) 
                   {
                    $taskowner = $row301['CreatedBy'];
                    $cRecRef=$row301['cRecRef'];
                    $sRecRef=$row301['SRecRef'];
                    $cScheduleDateUK = date ("d/m/Y", strtotime($csqlScheduleDate));
                    $cScheduleDate = date('d', strtotime($csqlScheduleDate));
                    $cSchDay = date('D', strtotime($csqlScheduleDate));
                    $cDueDateUK = date ("d/m/Y", strtotime($csqlDueDate));
                    $cDueDate = date('d M-Y', strtotime($csqlDueDate));
                    $cDueDay = date('D', strtotime($csqlDueDate));
                    $cStage=$row301['Stage'];
                    $TaskTitle=$row301['TaskTitle'];
                    $CoCode=$row301['ForCoRecRef'];
                    $CoShortCode=getCompanyShortCode($CoCode);
                    $TaskGroup=$row301['TaskGroup'];
                    $PrivateTask=$row301['PrivateTask'];
                    $AssignedBy=$row301['AssignedBy'];
                    $CompleteBy=$row301['CompleteBy'];
                    $RepeatSchedule1=$row301['RepeatSchedule'];
                    $AlertColor=$row301['AlertColor'];
                    $eMainGroup=$row301['TaskMainGroup'];
                    $eSubGroup=$row301['TaskSubGroup'];
                    $TaskMainGroup=$row301['TaskMainGroup'];
                    $TaskMainGroupTitle=getTaskMainGroupTitle($TaskMainGroup);
                    $TaskSubGroup=$row301['TaskSubGroup'];
                    $TaskSubGroupTitle=getTaskSubGroupTitle($TaskSubGroup);
                    $TaskDescr=$row301['TaskDescription'];
                    $Priority=$row301['Priority'];
                    $ForRefUSRC=$row301['ForRefUSR'];
                    $showdate='';
                    $showday='';
                    $ForUserFullName='';
                    $initials='';
                    $fullname='';
                    $awdays = $row301['DaysInWeek'];
                    //if($ForRefUSR!='ALL') {;
                    $assigneduser=array();
                    $x=0;
                    $query3011 = "SELECT distinct ForRefUSR from tCalendar where TRecRef='$TRecRef' AND Status='A' and (`cScheduleDate`,`cDueDate`) in (select `cScheduleDate`,`cDueDate` from tCalendar where cRecRef='$cRecRef') ";
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
                    $ForUserFullName.=', ' .$FullName;
                    if($ForRefUSRC==$ForRefUSR) {$color="#ccc";} else { $color="#fff";};
                    $initials.="<span style='background:$color;color:#000;border-radius:50%;padding:5px;border:1px solid #000' ><a href='#' title='$FullName'>".substr($FirstName,0,1).substr($LastName,0,1)."</a></span>&nbsp;&nbsp;";
                    $assigneduser[$x]=$ForRefUSR;
                    $x++;
                    }
                    $ForUserFullName = substr($ForUserFullName,2);
                    /*}
                    else {
                    $ForRefUSR=$row301['ForRefUSR'];
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
                    $initials.="<span style='background:#fff;color:#000;border-radius:50%;padding:5px;border:1px solid #000' ><a href='#' title='$FullName'>".substr($FirstName,0,1).substr($LastName,0,1)."</a></span>&nbsp;&nbsp;";
                    }*/
                    
                    if ($csqlScheduleDate==$csqlDueDate)
                    {   $showdate=$cDueDate;
                        $showday=$cDueDay;
                    } else {
                        $showdate=$cScheduleDate.' to '.$cDueDate;
                        $showday=$cSchDay.'-'.$cDueDay;
                    }
                    if($csqlDueDate<$current_date) {$statuscolor='style="background-color:red;"'; $statusBARcolor='red';}
                    else {$statuscolor='style="background-color:#5DADE2;"'; $statusBARcolor='#5DADE2';}
                    if ($CompleteBy!=0) {$statuscolor='style="background-color:green;"'; $statusBARcolor='green';}
                    if ($cStage=='WorkInProgress') {$statuscolor='style="background-color:#ffcc00;"'; $statusBARcolor='#ffcc00';}
                    $newassigncolor='black';
                   // if ($AlertColor=='DB') {$newassigncolor='style="color:blue;"';}
                   // if ($AlertColor=='LB') {$newassigncolor='style="color:deepskyblue;"';}
                    
                        $ThisTaskTags='';
                        $query21="SELECT * FROM `tTaskTags` WHERE `TaRecRef`='$TRecRef' AND `RefUSR` ='$id' AND cRecRef='$cRecRef' ORDER BY `TagTitle` ";
                        //echo 'Q21='.$query21;
                        $sql21 = mysqli_query($mysqli, $query21);
                        while($row21=mysqli_fetch_array($sql21))                        
                        {
                            $ThisTRecRef=$row21['TRecRef'];
                            $ThisTaskRecRef=$row21['TaRecRef'];
                            $ThisTagTitle=$row21['TagTitle'];
                            //$ThisTagTitle= ucfirst(strtolower($ThisTagTitle));
                            //$ThisTaskTags.=" -<i>$ThisTagTitle</i>&nbsp;&nbsp; ";
                            $ThisTaskTags.="<a href='#' onClick='removetag($celnodv,$ThisTRecRef)'><img src='images/imgRemove.png' alt='X' height='15' width='15' border=0/></a>
      $ThisTagTitle&nbsp;<br/> ";
                        } 
                    
                    //  ($ForRefUSR==$AssignedBy && $AssignedBy!=$id)   //---- This is private task
                    
                    if ($PrivateTask==1 && $AssignedBy!=$id) {   //---- This is private task 
                    }
                    else {
                        /*  id="dv-<?php echo $celnodv;?>" onmouseover="mouseOverDV(<?php echo $celnodv;?>)" onmouseout="mouseOutDV(<?php echo $celnodv;?>)"> */
                    $outall = "<input type=hidden id=EditTaskRef".$celnodv."     name=EditTaskRef".$celnodv." value=".$TRecRef." > ";
                    include "ptaskdetails.php";

                    $outall.= "</div>";
                    $celnodv++;
                    
                    //echo '<br>csqlScheduleDate'.$csqlScheduleDate.'='.strtotime($csqlScheduleDate).'------DateToday='.$DateToday.'='.strtotime($DateToday).'------';
                    //if (strtotime($csqlScheduleDate)<strtotime($DateToday))  {$outover.=$outall; $OverDueCount++;}
                    //if (strtotime($csqlScheduleDate)==strtotime($DateToday)) {$outoday.=$outall; $TodaysCount++;}
                    
                    if (strtotime($csqlScheduleDate)<strtotime($DateToday) && strtotime($csqlDueDate)<strtotime($DateToday))  {$outover.=$outall; $OverDueCount++;}
                    if (strtotime($csqlScheduleDate)==strtotime($DateToday) || ( strtotime($csqlScheduleDate)<strtotime($DateToday) && strtotime($csqlDueDate)>=strtotime($DateToday)  ) ) {$outoday.=$outall; $TodaysCount++;}
                    if (strtotime($csqlScheduleDate)==strtotime($DateTomorrow)) {$outomorrow.=$outall; $TomorrowCount++;}
                    
                    }   //------ end if private task
                    
                    $TRecRefold = $TRecRef;
                    $csqlScheduleDateold=$row301['cScheduleDate'];
                    $csqlDueDateold=$row301['cDueDate'];
                    }
                }   //---- end while
                                //----------------------- END Over Due, Today & Tomorrow Task List --------------------------- END
                

                
        }   //------- end if

        
        ?>
            <input type="hidden" name="CountCells" value="<?php echo $celnodv;?>"/>
            
            <div class="tab">
            <button class="tablinks" id="odtasks" onclick="ShowList('OD')">Over due tasks (<?php echo $OverDueCount; ?>)&nbsp</button>
            <button class="tablinks" id="tdtasks" onclick="ShowList('TD')">Today's tasks (<?php echo $TodaysCount; ?>) &nbsp &nbsp</button>
            <button class="tablinks" id="tmtasks" onclick="ShowList('TM')">&nbsp Tomorrow's tasks (<?php echo $TomorrowCount; ?>)</button>
            </div>
            
            <div id="OD" class="tabcontent">
            <?php if ($ViewListForFD=='OD') { echo $outover; } ?>
            </div>

            <div id="TD" class="tabcontent">
            <?php if ($ViewListForFD=='TD' || $ViewListForFD=='') { echo $outoday; } ?> 
            </div>

            <div id="TM" class="tabcontent">
            <?php if ($ViewListForFD=='TM') { echo $outomorrow; } ?>
            </div>

            
          <!--  <table cellpadding=4 cellspacing=0 width=100% border=0><tr style="cursor: pointer;" onclick="ShowList('OD')" >
            <td width="100%"><div class=BLUbkgBLUborder style='width:100%'><h4>OVER DUE TASKS &nbsp;&nbsp;&nbsp;&nbsp;</h4></div></td>
            <td align=center><div class=BLUbkgBLUborder style='width:90px;'><h4><?php //echo $OverDueCount; ?></h4></div></td>
            </tr></table>
                <?php //if ($ViewListForFD=='OD') { echo $outover; } ?>
            <table cellpadding=4 cellspacing=0 width=100% border=0><tr style="cursor: pointer;"  onclick="ShowList('TD')" >
            <td width="100%"><div class=BLUbkgBLUborder style='width:100%'><h4>TODAY's TASKS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h4></div></td>
            <td align=center><div class=BLUbkgBLUborder style='width:90px;'><h4><?php //echo $TodaysCount; ?></h4></div></td>
            </tr></table>
                <?php //if ($ViewListForFD=='TD' || $ViewListForFD=='') { echo $outoday; } ?>
            <table cellpadding=4 cellspacing=0 width=100% border=0><tr style="cursor: pointer;"  onclick="ShowList('TM')">
            <td width="100%"><div class=BLUbkgBLUborder style='width:100%'><h4>TOMORROW's TASKS</h4></div></td>
            <td align=center><div class=BLUbkgBLUborder style='width:90px;'><h4><?php //echo $TomorrowCount; ?></h4></div></td>
            </tr></table>
                <?php //if ($ViewListForFD=='TM') { echo $outomorrow; } ?> -->

                
                
    <?php
    }   //---- end if $urlRecEdit
    ?>
</div></div>    <!-- wrapper & center -->

</form>
    


</body>
</html>    