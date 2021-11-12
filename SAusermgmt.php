<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
error_reporting (E_ALL ^ E_NOTICE);
include("taskheader.php");

if(isset($_COOKIE["id"]))             { $id=$_COOKIE["id"]; }

$SuccessMessage='';    

    $FullName=$_POST["FullName"];
    $LName=$_POST["LName"];
    $Contact=$_POST["Contact"];
    $AddNewBtnClick=$_POST['AddNewBtnClick'];

$query10= "SELECT FirstName,LastName,EmailID,ContactNo FROM `tUser`where RefUSR='$id' ";
$sql10 = mysqli_query($mysqli, $query10);
$row10 = mysqli_fetch_array($sql10);
$EmailID = $row10["EmailID"];
$FirstName = $row10["FirstName"];
$LastName = $row10["LastName"];
$ContactNo = $row10["ContactNo"];



if ($AddNewBtnClick=="YES"  )
{
    $AddNewBtnClick="";
    
    if ($id!='')      //----- if Edit Record
    {
        $query11="UPDATE `tUser` SET `FirstName`='$FullName',`LastName`='$LName',`ContactNo`='$Contact',  `UpdateBy`='$id'  
                  WHERE `RefUSR`='$id'  " ;
        $sql11 = mysqli_query($mysqli, $query11);
    
        echo "<script> alert ('User Updated');</script>";
        
    }
    echo "<script>document.location='ptoday.php';</script>";
}    
?>


<html>
<head>
   
<link rel="shortcut icon" type="image/png" href="images/icontask.png"/>
<link rel="stylesheet" type="text/css" href="cssjs/newstyle.css"></link>

<!--  start Mask  Date Validation   -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="jquery.inputmask.bundle.min.js"></script>



<script>
function validcheck1()
{
   var letters = /^[a-zA-Z]+(\s{1}[a-zA-Z]+)*$/;  
   var numbers = /^[0-9]*$/;
   var paswd=  /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/; 


        var x=document.forms["UserForm"]["FullName"].value;
        
        
        if(x=='')
        {
            alert('Please Enter First Name');
            return false;
        }


	/*   after all field valids then submit the form */
 document.forms["UserForm"]["AddNewBtnClick"].value="YES";				//----- store parameter to check if the Submit New Button was clicked
 document.forms['UserForm'].submit();


}

</script>

</head>

<div align="left" style='margin:56px 0 0 15%;width:87%'>

    <form action="" name="UserForm" method="post">
        <input type=hidden name=AddNewBtnClick value="">
        
                    <div style="display:inline;width:120px;float:left;margin-top:10px"><b>First Name :</b></div>
                    <input type="text" class="form-control total_fields" style="display:inline;width:220px" id="FullName" name="FullName" placeholder="First Name" value="<?php echo $FirstName; ?>" ></br>
                    <div style="display:inline;width:120px;float:left;margin-top:10px"><b>Last Name :</b></div>
                    <input type="text" class="form-control total_fields" style="display:inline;width:220px" id="LName" name="LName" placeholder="Last Name" value="<?php echo $LastName; ?>" ></br>
                    <div style="display:inline;width:120px;float:left;margin-top:10px"><b>Email :</b></div>
                    <input type="text" class="form-control total_fields" style="display:inline;width:220px" id="Email" name="Email" placeholder="Email" value="<?php echo $EmailID; ?>" ></br>
                    <div style="display:inline;width:120px;float:left;margin-top:10px"><b>Contact No. :</b></div>
                    <input type="text" class="form-control total_fields" style="display:inline;width:220px" id="Contact" name="Contact" placeholder="Contact" value="<?php echo $ContactNo; ?>" ></br>
<br/>
                    <button type="button" class="btn btn-default btn-login" name="btnSave" value="SaveUser" onclick="validcheck1()">Save User</button>

                     <p>&nbsp;</p>
                     
                     <?php if ($SuccessMessage!='') echo $SuccessMessage; ?>
                     <p>&nbsp;</p>
                </div>
                    
    