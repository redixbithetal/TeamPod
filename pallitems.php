<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
error_reporting (E_ALL ^ E_NOTICE);
include("taskheader.php");

/*
 * ................. FOR pallitems.php/PALLITEMS.php change following in same code ..............
    *  change pallitems.php to pallitem.php
    *  add following statement in ptoday for $query301
                 AND ( DATE(t3.cScheduleDate)<=CURDATE() )
 * .............................................................................
 */
$DateStart='2019-01-01';
$DateToday= date('Y-m-d');
$DateTomorrow = date ("Y-m-d", strtotime("+1 day", strtotime($DateToday)));

//echo '<br>DateToday'.$DateToday.'----DateTomorrow='.$DateTomorrow.'----';

            $target_path = "uploads/SupportingDoc/";

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
        $query36="DELETE FROM `tTaskTags` WHERE `TRecRef`='$DTTTRecRef' " ;
        $sql36 = mysqli_query($mysqli, $query36);
        
        echo "<script>document.location='pallitems.php?ET=$urlRecEdit';</script>";
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
   /* $i=1;
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
    $query11="SELECT t1.RefUSR, t1.FirstName FROM `tUser` AS t1, `tUserAccessLevels` AS t2  
              WHERE t1.RefUSR=t2.RefUSR AND t1.Status='ACT' $FCCriteria  
              GROUP BY t1.RefUSR ORDER BY t1.FirstName "; 
    //echo '<br>-----'.$query11;
    $sql11 = mysqli_query($mysqli, $query11);
    while($row11=mysqli_fetch_array($sql11))						//------------------- Store Practice ID & Full Name from database to AllPractice_arr ------
        {
        $UserNameOnly_arr[$i][0]=$row11['RefUSR'];
        $UserNameOnly_arr[$i][1]=$row11['FirstName'];
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

    
    
        
?>


<html>
<head>
<title>Tasks On Cloud</title>

   
<link rel="shortcut icon" type="image/png" href="../focinc/images/icontask.png"/>
<link rel="stylesheet" type="text/css" href="cssjs/newstyle.css"></link>
    
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
    
<?php    include "taskfilter.php"; ?>

<div class="maindiv" id="wrapper" style="margin-left: 16%;width:84%"><div id="center" >
            
<div style="background-color: #ffffff">
    <?php 
    if ($urlRecEdit=='') {
        
        $FTMGCriteria=''; $FTSGCriteria=''; $FTGCriteria='';
        
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
        //if ($ForTaskTag!='') {$FTTagCriteria.=" AND t1.TRecRef IN ( SELECT TaRecRef FROM `tTaskTags` WHERE TagTitle='$ForTaskTag' ) ";}
        if ($ForTaskTag!='') {$FTTagCriteria.=" AND t3.cRecRef IN ( SELECT cRecRef FROM `tTaskTags` WHERE TagTitle='$ForTaskTag'  AND RefUSR ='$id') ";}
        //echo '<br>LINE 325--CO=('.$ForCompany.')----USR=('.$ForRefUSR.')<br>//---FCCriteria=('.$FCCriteria.')<br>//---FUCriteria=('.$FUCriteria.')';

        $AllCount=0; $celnodv=0;
        
        
        
                                //----------------------- START Over Due, Today & Tomorrow Task List --------------------------- START
        $query301="SELECT distinct t1.*,t2.*,t3.* FROM `tTasks` AS t1, `tSchedule` AS t2, `tCalendar` AS t3 
                    WHERE t1.TRecRef=t2.TRecRef AND t3.Status='A' AND t2.SRecRef=t3.SRecRef $FCCriteria $FUCriteria $FTMGCriteria $FTSGCriteria $FCMPCriteria $FTTagCriteria 
                    ORDER BY t3.cScheduleDate LIMIT 200";
        $sql301 = mysqli_query($mysqli, $query301);    
        $existCount301 = mysqli_num_rows($sql301);
        //echo '<br>QUERY---'.$existCount301.'-----'.$query301;
        if ($existCount301>0){
            
            //$outall = "<table cellpadding=4 cellspacing=0 width=99% border=0 id='myTable' style='border-collapse: separate; border-spacing: 0 7px;'> ";
            
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
                    $cScheduleDate = date('d M-Y', strtotime($csqlScheduleDate));
                    $cSchDay = date('D', strtotime($csqlScheduleDate));
                    $cDueDateUK = date ("d/m/Y", strtotime($csqlDueDate));
                    $cDueDate = date('d M-Y', strtotime($csqlDueDate));
                    $cDueDay = date('D', strtotime($csqlDueDate));
                    $cStage=$row301['Stage'];
                    $TaskTitle=$row301['TaskTitle'];
                    $CoCode=$row301['ForCoRecRef'];
                    $CoShortCode=getCompanyShortCode($CoCode);
                    $eMainGroup=$row301['TaskMainGroup'];
                    $eSubGroup=$row301['TaskSubGroup'];
                    $TaskMainGroup=$row301['TaskMainGroup'];
                    $TaskMainGroupTitle=getTaskMainGroupTitle($TaskMainGroup);
                    $TaskSubGroup=$row301['TaskSubGroup'];
                    $TaskSubGroupTitle=getTaskSubGroupTitle($TaskSubGroup);
                    $TaskGroup=$row301['TaskGroup'];
                    $ForRefUSRC=$row301['ForRefUSR'];
                    $ForUserFullName=getUserFirstName($ForRefUSR);
                    $TaskDescr=$row301['TaskDescription'];
                    $PrivateTask=$row301['PrivateTask'];
                    $AssignedBy=$row301['AssignedBy'];
                    $CompleteBy=$row301['CompleteBy'];
                    $AlertColor=$row301['AlertColor'];
                    $RepeatSchedule1=$row301['RepeatSchedule'];
                    $Priority=$row301['Priority'];
                    $ForUserFullName='';
                    $initials='';
                    $awdays = $row301['DaysInWeek'];
                    $assigneduser=array();
                    $x=0;
                    $query3011 = "SELECT distinct ForRefUSR from tCalendar where TRecRef='$TRecRef' and (`cScheduleDate`,`cDueDate`) in (select `cScheduleDate`,`cDueDate` from tCalendar where cRecRef='$cRecRef') ";
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
                    
                    $showdate='';
                    $showday='';
                    /*
                    if ($csqlScheduleDate==$csqlDueDate)
                    {   $showdate=$cDueDate;
                        $showday=$cDueDay;
                    } else {
                        $showdate=$cScheduleDate.'-'.$cDueDate;
                        $showday=$cSchDay.'-'.$cDueDay;
                    }
                    $showdate=$cScheduleDate;
                    $showday=$cSchDay;
                    */
                    
                    if ($csqlScheduleDate==$csqlDueDate)
                    {   $showdate=$cDueDate;
                        $showday=$cDueDay;
                    } else {
                        $showdate='<span style="font-size:13px">&nbsp;'.$cScheduleDate.'<br/>-'.$cDueDate.'</span>';
                        $showday=$cSchDay.'-'.$cDueDay;
                    }
                    
                    if($csqlDueDate<$current_date) {$statuscolor='style="background-color:red;"'; $statusBARcolor='red';}
                    else {$statuscolor='style="background-color:#5DADE2;"'; $statusBARcolor='#5DADE2';}
                    if ($CompleteBy!=0) {$statuscolor='style="background-color:green;"'; $statusBARcolor='green';}
                    if ($cStage=='WorkInProgress') {$statuscolor='style="background-color:#ffcc00;"'; $statusBARcolor='#ffcc00';}
                    
        $newassigncolor='style="display:block; margin:5px; border:2px #5DADE2 solid; border-radius:10px;" ';
        $tborderleft='style="border: solid 0 #5DADE2; border-left-width:2px; border-radius: 10px 0px 0px 10px; border-top: 2px solid #5DADE2; border-bottom: 2px solid #5DADE2; " ';
        $tborderright='style="border: solid 0 #5DADE2; border-right-width:2px; border-radius: 0px 10px 10px 0px; border-top: 2px solid #5DADE2; border-bottom: 2px solid #5DADE2; " ';
        $tbordertopbottom='style="border: solid 0 #5DADE2;  border-top: 2px solid #5DADE2; border-bottom: 2px solid #5DADE2; " ';
        $tbordertop='style="border: solid 0 #5DADE2; border-top: 2px solid #5DADE2; " ';
        $tborderbottom='style="border: solid 0 #5DADE2; border-bottom: 2px solid #5DADE2; " ';
        
        if ($AlertColor=='DB') {$newassigncolor='style="color:blue; display:block; margin:5px; border:2px blue solid; border-radius:10px;" ';}
        if ($AlertColor=='LB') {$newassigncolor='style="color:deepskyblue; display:block; margin:5px; border:2px deepskyblue solid; border-radius:10px;" ';}
        
        $newassigncolor='style="color:black;"';
        //if ($AlertColor=='DB') {$newassigncolor='style="color:blue;"';}
        //if ($AlertColor=='LB') {$newassigncolor='style="color:deepskyblue;"';}
                    
                        $ThisTaskTags='';
                        $query21="SELECT * FROM `tTaskTags` WHERE `TaRecRef`='$TRecRef' AND `RefUSR` ='$id' AND cRecRef='$cRecRef' ORDER BY `TagTitle` ";
                        //echo 'Q21='.$query21;
                        $sql21 = mysqli_query($mysqli, $query21);
                        while($row21=mysqli_fetch_array($sql21))                        
                        {
                            $ThisTRecRef=$row21['TRecRef'];
                            $ThisTaskcRecRef=$row21['cRecRef'];
                            $ThisTagTitle=$row21['TagTitle'];
                            //$ThisTagTitle= ucfirst(strtolower($ThisTagTitle));
                            $ThisTaskTags.="<a href='#' onClick='removetag($celnodv,$ThisTRecRef)'><img src='../focinc/images/imgRemove.png' alt='X' height='15' width='15' border=0/></a>
      $ThisTagTitle&nbsp;<br/> ";
                        } 
                    
                    //  ($ForRefUSR==$AssignedBy && $AssignedBy!=$id)   //---- This is private task
                    
                    if ($PrivateTask==1 && $AssignedBy!=$id) {   //---- This is private task 
                    }
                    else {
                        /*  id="dv-<?php echo $celnodv;?>" onmouseover="mouseOverDV(<?php echo $celnodv;?>)" onmouseout="mouseOutDV(<?php echo $celnodv;?>)"> */
                    $outall.= "<input type=hidden id=EditTaskRef".$celnodv."     name=EditTaskRef".$celnodv." value=".$TRecRef." > ";
                    include "ptaskdetails.php";
                          
                    
                    $outall.= "</div>";
                    $celnodv++;
                    
                    $AllCount++;
                    }   //------ end if private task
                    $csqlScheduleDateold=$row301['cScheduleDate'];
                    $csqlDueDateold=$row301['cDueDate'];
                    $TRecRefold = $TRecRef;
                    }
                }   //---- end while
                                //----------------------- END Over Due, Today & Tomorrow Task List --------------------------- END
                
                //$outall.= "</table>";
                
        }   //------- end if

        
        ?>
            <input type="hidden" name="CountCells" value="<?php echo $celnodv;?>"/>
        
            <table cellpadding=4 cellspacing=0 width=100% border=0><tr style="cursor: pointer;"  onclick="ShowList('TD')" >
            <td width="100%"><div class=BLUbkgBLUborder style='width:100%'><h4>ALL TASKS</h4></div></td>
            <td align=center><div class=BLUbkgBLUborder style='width:90px;'><h4><?php echo $AllCount; ?></h4></div></td>
            </tr></table>
                <?php if ($ViewListForFD=='TD' || $ViewListForFD=='') { echo $outall; } ?>
                
                
    <?php
    }   //---- end if $urlRecEdit
    ?>
</div></div>    <!-- wrapper & center -->
   
</form>
    
<style>
#Nav_AddTask {  background: url(../focinc/images/iconf-addA.png);  background-size: 70px 38px;  border: 2px solid #5DADE2; width: 70px; height: 42px;}
#Nav_AddTask:hover {  background: url(../focinc/images/iconf-addB.png);  background-size: 70px 40px;}
</style>

</body>
</html>    