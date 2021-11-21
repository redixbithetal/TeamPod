<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

//**GLOBAL $AccessLevel;

//**$SizeOfAL=sizeof($AccessLevel);
                //-------------- join array of company to use in SELECT IN to get company related records
//**for($jra=0;$jra<$SizeOfAL;$jra++) {
//** $arrMyCo.="','".$AccessLevel[$jra][0];
//**}
//echo $arrMyCo;

        //   echo "<script> alert ('ID=$id / NAME=$loginame / Size=$SizeOfAL / $arrMyCo');</script>";

include "dbhands.php";

    $AllUserCodeName_arr = array();             //---------------- Get all File Codes from database table then when require only show specific codes in select statement
         $query11="SELECT `RefUSR`, `FirstName`, `LastName` FROM `tUser` WHERE `Status`='ACT' ORDER BY `FirstName`, `LastName` "; 
        $sql11 = mysqli_query($mysqli, $query11);
        $i=1;
        while($row11=mysqli_fetch_array($sql11))						//------------------- Store Practice ID & Full Name from database to AllPractice_arr ------
            {
            $AllUserCodeName_arr[$i][0]=$row11['RefUSR'];
            $AllUserCodeName_arr[$i][1]=$row11['FirstName'].' '.$row11['LastName'];
            //echo ' Yes '.$AllUserCodeName_arr[$i][0].' -- '.$AllUserCodeName_arr[$i][1];
            $i++;
            }
       
                        function getUserFullName($uid)
                        {
                            global $AllUserCodeName_arr;
                            for ($s=0;$s<=sizeof($AllUserCodeName_arr);$s++)
                            {
                                if($AllUserCodeName_arr[$s][0]==$uid)
                                {
                                //echo '<br> Yes '.$s.' -- '.$AllUserCodeName_arr[$s][0].' -- '.$AllUserCodeName_arr[$s][1];
                                    return $AllUserCodeName_arr[$s][1];}
                            }
                            return '';
                        }        


                        function getUserFirstName($uid)
                        {
                            $FPartName='';
                            global $AllUserCodeName_arr;
                            for ($s=0;$s<=sizeof($AllUserCodeName_arr);$s++)
                            {
                                if($AllUserCodeName_arr[$s][0]==$uid)
                                {
                                //echo '<br> Yes '.$s.' -- '.$AllUserCodeName_arr[$s][0].' -- '.$AllUserCodeName_arr[$s][1];
                                    $FPartName=substr($AllUserCodeName_arr[$s][1],0,strrpos($AllUserCodeName_arr[$s][1],' '));
                                    return $FPartName;}
                            }
                            return '';
                        }        
        
    $CompanyCode_arr = array();             //---------------- Get all File Codes from database table then when require only show specific codes in select statement

        $query11="SELECT t1.*,t2.CoRecRef,t2.CoCode,t2.CoName FROM `tUserAccessLevels` as t1, `tCompany` AS t2 
                  WHERE t1.FCompany=t2.CoRecRef AND t2.CoType='COMPANY' AND t2.Status='ACT' AND t1.RefUSR='$id'
                  GROUP BY t1.FCompany ORDER BY t2.CoName ";
        $sql11 = mysqli_query($mysqli, $query11);
        $i=0;
        while($row11=mysqli_fetch_array($sql11))						//------------------- Store Practice ID & Full Name from database to AllPractice_arr ------
            {
            $CompanyCode_arr[$i][0]=$row11['CoRecRef'];
            $CompanyCode_arr[$i][1]=$row11['CoCode'];
            $CompanyCode_arr[$i][2]=$row11['CoName'];
            $i++;
            }
	$maxcompanycode = sizeof($CompanyCode_arr);

    $AllTaskGroups_arr = array();             //---------------- Get all File Codes from database table then when require only show specific codes in select statement
        $query11="SELECT * FROM `tTasks` WHERE `Status`='ACT' GROUP BY `TaskGroup` ORDER BY `TaskGroup` "; 
        $sql11 = mysqli_query($mysqli, $query11);
        $i=0;
        while($row11=mysqli_fetch_array($sql11))						//------------------- Store Practice ID & Full Name from database to AllPractice_arr ------
            {
            $AllTaskGroups_arr[$i][0]=$row11['TaskGroup'];
            $i++;
            }
	$maxgrouptasktitle = sizeof($AllTaskGroups_arr);

        
    $AllTaskMainGroups_arr = array();
        $query11="SELECT `gRecRef`, `gTitle` FROM `tTaskgCode` WHERE `gUsedFor`='TASKMAINGROUP' AND `Status`='ACT' ORDER BY `gTitle` "; 
        $sql11 = mysqli_query($mysqli, $query11);
        $i=0;
        while($row11=mysqli_fetch_array($sql11))
            {
            $AllTaskMainGroups_arr[$i][0]=$row11['gRecRef'];
            $AllTaskMainGroups_arr[$i][1]=$row11['gTitle'];
            $i++;
            }
	$maxtaskmaingrouptitle = sizeof($AllTaskMainGroups_arr);
        
                        function getTaskMainGroupTitle($cid)
                        {
                            global $AllTaskMainGroups_arr;
                            for ($s=0;$s<=sizeof($AllTaskMainGroups_arr);$s++)
                            {
                                if($AllTaskMainGroups_arr[$s][0]==$cid)
                                {
                                    return $AllTaskMainGroups_arr[$s][1];}
                            }
                            return '';
                        }        


    $AllTaskSubGroups_arr = array();
        $query11="SELECT `gRecRef`, `gOfMain`, `gTitle` FROM `tTaskgCode` WHERE `gUsedFor`='TASKSUBGROUP' AND `Status`='ACT' ORDER BY `gTitle` "; 
        $sql11 = mysqli_query($mysqli, $query11);
        $i=0;
        while($row11=mysqli_fetch_array($sql11))
            {
            $AllTaskSubGroups_arr[$i][0]=$row11['gRecRef'];
            $AllTaskSubGroups_arr[$i][1]=$row11['gTitle'];
            $AllTaskSubGroups_arr[$i][2]=$row11['gOfMain'];
            $i++;
            }
	$maxtasksubgrouptitle = sizeof($AllTaskSubGroups_arr);
        
                        function getTaskSubGroupTitle($cid)       //------- Return ONE sub group Title
                        {
                            global $AllTaskSubGroups_arr;
                            for ($s=0;$s<=sizeof($AllTaskSubGroups_arr);$s++)
                            {
                                if($AllTaskSubGroups_arr[$s][0]==$cid)
                                {
                                    return $AllTaskSubGroups_arr[$s][1];}
                            }
                            return '';
                        }        


                        function getSubGroupOfMain($mgid)       //------- filter Sub Groups for the Main group into a new array
                        {
                            global $AllTaskSubGroups_arr;
                            if ($mgid=='') { 
                                $SubGroupsOfMain_arr=$AllTaskSubGroups_arr; 
                                return $SubGroupsOfMain_arr;
                            }
                            $i=0;
                            for ($s=0;$s<=sizeof($AllTaskSubGroups_arr);$s++)
                            {
                                if($AllTaskSubGroups_arr[$s][2]==$mgid)
                                {
                                    $SubGroupsOfMain_arr[$i][0]=$AllTaskSubGroups_arr[$s][0];
                                    $SubGroupsOfMain_arr[$i][1]=$AllTaskSubGroups_arr[$s][1];
                                    $i++;
                                }
                            }
                            return $SubGroupsOfMain_arr;
                        }        

$AllTasksTagList_arr = array();  
    $query1="SELECT DISTINCT `TagTitle` FROM `tTaskTags` where RefUSR='$id' ORDER BY `TagTitle` ";
    $sql1 = mysqli_query($mysqli, $query1);
    $i=0;
    while($row1=mysqli_fetch_array($sql1))                        
    {
        $AllTasksTagList_arr[$i][0]=ucfirst(strtolower($row1['TagTitle']));
        $i++;
    } 
    $maxtasktags = sizeof($AllTasksTagList_arr);

        


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

        
function getCompanyFullTitle($cid)
{
    global $AllCompanyCode_arr;
    for ($s=0;$s<sizeof($AllCompanyCode_arr);$s++)
    {
       if($AllCompanyCode_arr[$s][0]==$cid)
       {return $AllCompanyCode_arr[$s][2];}
       }
       return '';
}        

function getCompanyShortCode($cid)
{
    global $AllCompanyCode_arr;
    //echo sizeof($AllCompanyCode_arr);
    for ($s=0;$s<sizeof($AllCompanyCode_arr);$s++)
    {
        if($AllCompanyCode_arr[$s][0]==$cid)
        {return $AllCompanyCode_arr[$s][1];}
    }
    return '';
}

            //------------------------------- return User name or Partial email address --------------------------------
function CheckUserName($chkuserid)
{
    global $mysqli;
        $query31="SELECT `FullName`, `EmailID` FROM `tUser` WHERE `RefUSR`='$chkuserid' ";
        $sql31 = mysqli_query($mysqli, $query31);
            while($row31 = mysqli_fetch_array($sql31)){
                $UserRef = $row31["RefUSR"];
                $FullName = $row31["FullName"];
                $NameFistPart=substr($FullName, 0, strpos($FullName, ' '));     //--- get first name by checking space in fullname
                if ($NameFistPart=='') {$NameFistPart=$FullName;}                //--- if there is no fullname then take all name
                $EmailID = $row31["EmailID"];
                $EmailFistPart=substr($EmailID, 0, strpos($EmailID, '@'));      //-- get email fist part
                $retunUserName=$NameFistPart;
                    if ($NameFistPart=='') { $retunUserName=$EmailFistPart;}  //--- if user not updated Full Name then just show email as his/her name
            }
            
            if ($retunUserName=='')     //----- if no user in tUser database then it might be External Authorisor, then get email address
                {$retunUserName=substr($chkuserid, 0, strpos($chkuserid, '@')); }     //-- get user email fist part

            //echo "<script> alert ('ID=$chkuserid / return=$retunUserName ');</script>";
                    
            return ($retunUserName); 
    
}   //---- end function CheckUserName



            //------------------------------- return User email address --------------------------------
function CheckUserEmail($chkuserid)
{
    global $mysqli;
        $query31="SELECT `FullName`, `EmailID` FROM `tUser` WHERE `RefUSR`='$chkuserid' ";
        $sql31 = mysqli_query($mysqli, $query31);
            while($row31 = mysqli_fetch_array($sql31)){
                $UserRef = $row31["RefUSR"];
                $EmailID = $row31["EmailID"];
            }
                     //----- if no user in tUser database then it might be External Authorisor, then get email address
            if ($EmailID=='')   {return $chkuserid; }
            else                {return $EmailID; }

}   //---- end function CheckUserEmail


function convertDecimal2HM($dec)
    {

    $seconds = ($dec * 3600);    // converting to seconds
    $hours = floor($dec);
    // since we've "calculated" hours, let's remove them from the seconds variable
    $seconds -= $hours * 3600;
    $minutes = floor($seconds / 60);    // calculate minutes left
    $seconds -= $minutes * 60;    // remove those from seconds as well
    // return the time formatted HH:MM
    return lz($hours).":".lz($minutes).":00";    // return the time formatted HH:MM
    }
    function lz($num)// lz = leading zero
    {
        return (strlen($num) < 2) ? "0{$num}" : $num;
    }
?>

