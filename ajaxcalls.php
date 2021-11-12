<?php
error_reporting (E_ALL ^ E_NOTICE);

include "dbhands.php";
//include "i_envirovar.php";
//include "i_functions.php";

if (isset($_COOKIE["id"]))
   {
       $id = $_COOKIE["id"];
   }
?>


<?php
date_default_timezone_set('Europe/London'); // CDT
$current_date = date('Y-m-d');
$currdatetime = date('Y-m-d H:i:s');

function random_strings($length_of_string) 
{ 
$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
            // Shufle the $str_result and returns substring 
            // of specified length 
return substr(str_shuffle($str_result),0, $length_of_string); 
}

if($_POST['cat'] =="createprof") {
    
	$NewUserEmail=$_POST['NewUserEmail'];
	$Password=$_POST['NewUserPassword'];
    $RefUsr=$_POST['RefStr'];
	
	if ($NewUserEmail!=''){

                    $query1 = "SELECT `UserID` FROM `tUser` WHERE `UserID`='$NewUserEmail' LIMIT 1";
                    $sql1 = mysqli_query($mysqli, $query1);
                    $existCount=mysqli_num_rows($sql1);
                    if ($existCount>0)
                    {
                        echo 'It looks like you are already registered, if you forget your password please reset it';
                    }
            
    else {
	
	
	        $query21="SELECT max(SubscriptionID) + 00001 as subs FROM `UserSubscription` limit 1";
	        $sql21 = mysqli_query($mysqli, $query21);
	         while($row21=mysqli_fetch_array($sql21)) 
	    
	        {
	        $UserSubscriptionID=$row21['subs'];}
	        
	        if ($UserSubscriptionID==null){$UserSubscriptionID =10000;}//echo "noteid_".$query21; }
	     
	     
	        $query22="SELECT max(CompanyID) + 00001 as comp FROM `UserCompany` limit 1";
	        $sql22 = mysqli_query($mysqli, $query22);
	         while($row22=mysqli_fetch_array($sql22)) 
	    
	        {
	        $CompanyID=$row22['comp'];}
	            
	        if ($CompanyID==null){$CompanyID=10000;}
	        
	        $query3="INSERT INTO `tUser`(`UserID`,`EmailID`, `UsrPass` ,`Status`,`RegistrationDate`,`SubscriptionID`) 
	        VALUES ('$NewUserEmail', '$NewUserEmail','$Password','I', '$currdatetime','$UserSubscriptionID') " ;
       
		
	    	$sql3 = mysqli_query($mysqli, $query3);
	    	echo 'Your user profile is in review. !';
	    	
	    	$query4="SELECT `RefUSR` from `tUser` where `UserID`='$NewUserEmail' " ;
	    	$sql4 = mysqli_query($mysqli, $query4);
	    	$row4=mysqli_fetch_array($sql4);
	    	$NewUserID=$row4[RefUSR];
	    	
	    	$query5="select RefUSR from `tVerificationReferral`  where `ReferralString`='$RefUsr' " ;
	    	$sql5 = mysqli_query($mysqli, $query5);
	    	$row5=mysqli_fetch_array($sql5);
	    	$RefUserID=$row5[RefUSR];
	    	
	    	$query6="update `tUser` set ReferredBy='$RefUserID'  where `RefUSR`='$NewUserID' " ;
	    	$sql6 = mysqli_query($mysqli, $query6);
	    	
	    	$string= random_strings(10);
        	$to="$NewUserEmail";
        	$subject1= "Welcome to the organization, verify your account to proceed further";
        	$fromName = "HR Team";
        	$from = "support@teampod.co.uk"; 
        	
            $message2='
            
            Hi!
            
            Welcome to the organization.
            Your account has been created, Please find below details of your account:
            
            --------------------------
            Email: '.$NewUserEmail.'
            Password: '.$Password.'
            --------------------------
            
            Please click on the this link to verify your account: 
            https://teampod.co.uk/TeamPod/verification.php?usid='.$NewUserID.'&rstr='.$string.'
            
            Thanks & Regards,
            HR Team
            
            ';
            
            $headers1 = "From: $fromName"."<".$from.">";
        	mail($to,$subject1,$message2,$headers1);
        	
        	echo 'Please check inbox for verification link';
        	
}
}
}


if($_POST['cat'] =="resetpswd") {
    
	$UserEmail=$_POST['UserEmail'];
	if ($UserEmail!=''){

                    $query1 = "SELECT `UserID` FROM `tUser` WHERE `UserID`='$UserEmail' LIMIT 1";
                    $sql1 = mysqli_query($mysqli, $query1);
                    $existCount=mysqli_num_rows($sql1);
                    if ($existCount<=0)
                    {
                        echo 'User does not exist!';
                    }
            
    else {
	
	    	$query4="SELECT `RefUSR`,`FirstName` from `tUser` where `UserID`='$UserEmail' " ;
	    	$sql4 = mysqli_query($mysqli, $query4);
	    	$row4=mysqli_fetch_array($sql4);
	    	$UserID=$row4[RefUSR];
	    	$FirstName=$row4[FirstName];
	    	$string= random_strings(10);
        	$to="$UserEmail";
        	$subject1= "Teampod - Password Reset Link";
        	$fromName = "HR Team";
        	$from = "support@teampod.co.uk"; 
        	
            $message2='
            
            Hi '.$FirstName.'!
            
            Your have requested for a password reset.
            
            Please click on the this link to reset your password: 
            https://teampod.co.uk/TeamPod/resetpasswordpage.php?usid='.$UserID.'&rstr='.$string.'
            
            Thanks & Regards,
            HR Team
            
            ';
            
            $query31="select `UserID`,`resetstring` from `tPasswordReset` where `UserID`='$UserID' " ;
	    	$sql31 = mysqli_query($mysqli, $query31);
	    	$row31 = mysqli_fetch_array($sql31);
            $resetstring = $row31["resetstring"];
            
            if($resetstring=='')
                {
                    $query33="INSERT INTO `tPasswordReset`(`UserID`,`resetstring`,`UpdateDate`) VALUES ('$UserID', '$string','$currdatetime') " ;
        	    	$sql33 = mysqli_query($mysqli, $query33);
                }
                
            else
                {
                   $query33="UPDATE `tPasswordReset` set `resetstring`='$string',`UpdateDate`='$currdatetime'  where `UserID`='$UserID' " ;
        	    	$sql33 = mysqli_query($mysqli, $query33); 
                    
                }
            
            $headers1 = "From: $fromName"."<".$from.">";
        	mail($to,$subject1,$message2,$headers1);
        	
        	echo 'Please check inbox for Password reset link';
        	
}
}
}

if($_POST['cat'] =="useLicense") {
             
            
            $query6 = "select `LicenseUsed`,`LicensePurchased` from `tUser` where RefUSR=$id";
            $sql6 = mysqli_query($mysqli, $query6);
            $row6 = mysqli_fetch_array($sql6);
            $LicenseUsed   =$row6["LicenseUsed"];
            $LicensePur   =$row6["LicensePurchased"];
            
            if ($LicensePur>$LicenseUsed){
            $query7 = "UPDATE `tUser` set LicenseUsed=$LicenseUsed+1 where RefUSR=$id";
            $sql7 = mysqli_query($mysqli, $query7);
            
            $query8 = "select `LicenseUsed`,`LicensePurchased` from `tUser` where RefUSR=$id";
            $sql8 = mysqli_query($mysqli, $query8);
            $row8 = mysqli_fetch_array($sql8);
            $LicenseUsed   =$row6["LicenseUsed"];
            $LicensePur   =$row6["LicensePurchased"];
            
            $LicenseRem=$LicensePur-$LicenseUsed;
            
            echo 'Remaining License='.$UserID.' ';
            }
            
            else {
            echo 'Your License keys exhaused!';   
            }
            
            
            
}

if($_POST['cat'] =="sendInviteEmail") {
             
            
            $query7 = "SELECT ReferralString FROM `tVerificationReferral`  where RefUSR=$id";
                            
            $sql7 = mysqli_query($mysqli, $query7);
            $row7 = mysqli_fetch_array($sql7);
            $ReferralString   =$row7["ReferralString"];
            
        	$NewUserEmail=$_POST['NewUserEmail'];
        	
        	$query8 = "SELECT `UserID`, `EmailID`, `FirstName`,`LastName`, `Status` FROM `tUser` where RefUSR=$id";
            $sql8 = mysqli_query($mysqli, $query8);
            $row8 = mysqli_fetch_array($sql8);
            $FName   =$row8["FirstName"];
            $LName   =$row8["LastName"];
            
            $query9 = "SELECT `RefUSR`, `EmailID` FROM `tUser` where EmailID='$NewUserEmail'";
            $sql9 = mysqli_query($mysqli, $query9);
            $row9 = mysqli_fetch_array($sql9);
            $RefUSR   =$row9["RefUSR"];
            
            //echo $query9;
            //echo $UserID;
            
            if($RefUSR>0)
            {
                
              echo 'This User already exists in the system. Please enter another mail ID';  
              //return false;
            }
            
            else
            {
            
            $to="$NewUserEmail";
        	$subject1= "Welcome to the organization, please find your invitation link";
        	$fromName = "HR Team";
        	$from = "support@teampod.co.uk"; 
        	
            $message2='
            Hi!
            
            Welcome to the organization.
            You have been invited by your friend: '.$FName.' '.$LName.' 
            
            Please click on the this link to create your account: 
            https://teampod.co.uk/TeamPod/index.php?r='.$ReferralString.'
            
            Thanks & Regards,
            HR Team
            
            ';
            
            $headers1 = "From: $fromName"."<".$from.">";
        	mail($to,$subject1,$message2,$headers1);
        	
        	
        	echo 'Invitation link is sent!';
            }
}


