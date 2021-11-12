<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php 
error_reporting (E_ALL ^ E_NOTICE);

date_default_timezone_set('Europe/London'); // CDT
$current_date = date('Y-m-d');
$currdatetime = date('Y-m-d H:i:s');


include "dbhands.php";

$NewUserID=$_GET["usid"];
$NewUserReff=$_GET["rstr"];


 $query1 = "SELECT `UserID`, `Status` FROM `tUser` where `RefUSR`='$NewUserID' LIMIT 1";
                    $sql1 = mysqli_query($mysqli, $query1);
                    $row1=mysqli_fetch_array($sql1);
                    //$existCount=mysqli_num_rows($sql1);
                         //   echo "<script> alert ('User Count = $existCount ');</script>";
                    if ($row1[Status]!='A')
                    {  
                        $query3="update `tUser` set `Status`='A' where `RefUSR`='$NewUserID' " ;
       
		                $sql3 = mysqli_query($mysqli, $query3);
		                
		                $query4="INSERT INTO `tVerificationReferral` ( `RefUSR`, `ReferralString`, `UpdateDate`) VALUES ( '$NewUserID', '$NewUserReff', '$currdatetime')";
		                
		                $sql4 = mysqli_query($mysqli, $query4);
                        //write the update script and alert that acct is activated
                        echo "<script> alert ('Your account is verified, you can login now!');</script>";
                        echo '<script> document.location="index.php";    </script>';
                    }
                    else
                    
                    {  
                        
                        echo "<script> alert ('Your account is already verified, please proceed to login page!');</script>";
                        echo '<script> document.location="index.php";    </script>';
                    }


