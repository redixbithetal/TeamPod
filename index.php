<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php 
   error_reporting (E_ALL ^ E_NOTICE);
   
   include "dbhands.php";
   include "newheader.php";
   
   //include "i_envirovar.php";
   
   $RefStr = $_GET['r'];
   
   date_default_timezone_set('Europe/London'); // CDT
   $current_date = date('Y-m-d');
   $currdatetime = date('Y-m-d H:i:s');
   $SysFullCompany="TeamPod";
   $loginfromip=getUserIP();

function getUserIP()            //------- get client Internet IP address
{
    $client  = $_SERVER['HTTP_CLIENT_IP'];      //---- do not use this Any one of these header values can be freely spoofed and most of the time not get IP address
    $forward = $_SERVER['HTTP_X_FORWARDED_FOR'];
    $remoteIP  = $_SERVER['REMOTE_ADDR'];                   //--- ISP IP address
    $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);     //--- ISP IP address and Name
    $agent   = $_SERVER['HTTP_USER_AGENT'];                 //-------- best information to collect from any PC/mobile device

    $ip_host=$remoteIP.'- '.$agent;
    return $ip_host;
}


function random_strings($length_of_string) 
{ 
$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
            // Shufle the $str_result and returns substring 
            // of specified length 
return substr(str_shuffle($str_result),0, $length_of_string); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
           
        //    $rbtn_personal=$_POST["rbtn_personal"];
        //    $rbtn_bus=$_POST["rbtn_bus"];
           $btnSignIn=$_POST["btnSignIn"];
           $btnSignUp=$_POST["btnSignUp"];
           $btnPswdReset=$_POST["btnPswdReset"];
           $ResetUserEmail=$_POST["ResetUserEmail"];
           $UserEmail=$_POST["UserEmail"];
           $pswrd=$_POST["pswrd"];
           $NewUserEmail=$_POST["NewUserEmail"];
           $Newpswrd=$_POST["Newpswrd"];
           $AddNewBtnClick=$_POST['AddNewBtnClick'];   
           
                       if ($btnSignIn!='' && $UserEmail=='')       //------------------ if click on SignIn bit no Email/pswd entered then stay on same page
                           {
                           echo '<script> document.location="index.php";    </script>';
                           }
                    // as per requirement      
                    //    if ($rbtn_bus=='' &&  $rbtn_personal=='')
                    //        {
                    //        echo "<script> alert ('Please select the account options');</script>";
                    //        echo '<script> document.location="index.php";    </script>';
                    //        }
   
                       if ( $btnSignIn!='' && $UserEmail!='')  {
                            //&& $rbtn_bus!=''
                           $query6 = "SELECT `RefUSR`, `UserID`, `EmailID`, `CanSystemAdmin`, `Status` FROM `tUser`
                           WHERE `UserID`='$UserEmail' && `usrPass`='$pswrd'";
                            
                           $sql6 = mysqli_query($mysqli, $query6);
                           $UPCount=mysqli_num_rows($sql6);
                           
                           if ($UPCount>0)
                           {
                               $row6 = mysqli_fetch_array($sql6);
                               $UserRef    =$row6["RefUSR"];
                               $UserStatus =$row6["Status"];
                               $CanSystemAdmin = $row6["CanSystemAdmin"];
                               
                               setcookie("id", $UserRef, time()+ (86400 * 30),'/');
                               setcookie("CanSystemAdmin", $CanSystemAdmin, time()+ (86400 * 30),'/');
                               
                               $_SESSION["id"] = $UserRef;
                               
                                //----------------- START Activity Log ---------------------
                                    $query12="INSERT INTO `tUserIPHostLog`(  `RefUSR`,`ActivityType`, `IPAddressHost`, `ActivityDT`)
                                              VALUES ('$UserRef',  'Login',     '$loginfromip', '$currdatetime') " ;
                                    $sql12 = mysqli_query($mysqli, $query12);
                                //----------------- END Activity Log ---------------------
                                       
                               if($UserStatus!='A')
                               {
                                   echo '<script> alert ("Your Account is not verified/ activated, please activate and try to login");</script>';
                                   echo '<script> document.location="index.php";    </script>';
                               }
   
                               $query7 = "SELECT `RefUSR`, `UserID`, `EmailID`, `Status` FROM `tUser`
                                          WHERE `UserID`='$UserEmail' && `usrPass`='$pswrd'";
                           
                               echo "<script> alert ('Please update your company name');</script>";
                               echo '<script> document.location="SAusermgmt.php";  </script>';
                           }
                           else
                           {
                               echo "<script> alert ('Incorrect Login Details or Not Approved');</script>";
                               echo '<script> document.location="index.php";    </script>';
   
                           }
                       }
                       
                       if ( $btnSignIn!='' && $UserEmail!='' && $rbtn_personal!=''){
   
                           $query6 = "SELECT `RefUSR`, `UserID`, `EmailID`, `CanSystemAdmin`, `Status` FROM `tUser`
                           WHERE `UserID`='$UserEmail' && `usrPass`='$pswrd'";
                            
                           $sql6 = mysqli_query($mysqli, $query6);
                           $UPCount=mysqli_num_rows($sql6);
                           
                           if ($UPCount>0)
                           {
                               $row6 = mysqli_fetch_array($sql6);
                               $UserRef    =$row6["RefUSR"];
                               $UserStatus =$row6["Status"];
                               $CanSystemAdmin = $row6["CanSystemAdmin"];
                               
                               setcookie("id", $UserRef, time()+ (86400 * 30),'/');
                               setcookie("CanSystemAdmin", $CanSystemAdmin, time()+ (86400 * 30),'/');
                               
                               $_SESSION["id"] = $UserRef;
                               
                                //----------------- START Activity Log ---------------------
                                    $query12="INSERT INTO `tUserIPHostLog`(  `RefUSR`,`ActivityType`, `IPAddressHost`, `ActivityDT`)
                                                                    VALUES ('$UserRef',  'Login',     '$loginfromip', '$currdatetime') " ;
                                    $sql12 = mysqli_query($mysqli, $query12);
                                //----------------- END Activity Log ---------------------
                                       
                               if($UserStatus!='A')
                               {
                                   echo '<script> alert ("Your Account is not verified/ activated, please activate and try to login");</script>';
                                   echo '<script> document.location="index.php";    </script>';
                               }
   
                               $query7 = "SELECT `RefUSR`, `UserID`, `EmailID`, `Status` FROM `tUser`
                                          WHERE `UserID`='$UserEmail' && `usrPass`='$pswrd'";
                           
                               echo '<script> document.location="ptoday.php";  </script>';
                           }
                           else
                           {
                               echo "<script> alert ('Incorrect Login Details or Not Approved');</script>";
                               echo '<script> document.location="index.php";    </script>';
                           }
                       }

           if ($btnPswdReset!='')            
           {
                       $query7 = "SELECT `RefUSR`, `usrPass`,`FirstName` FROM `tUser` WHERE `UserID`='$ResetUserEmail' LIMIT 1";
                       $sql7 = mysqli_query($mysqli, $query7);
                       $existCount7=mysqli_num_rows($sql7);
                              // echo "<script> alert ('User Count = $existCount7 / ID=$ResetUserEmail ');</script>";
                       if ($existCount7>0)
                       {
                           while($row7 = mysqli_fetch_array($sql7)){           //----------- get pswd from database
                               $RefUSR = $row7["RefUSR"];
                               $usrEmailID = $row7["EmailID"];
                               $usrPass = $row7["usrPass"];
                               $FirstName = $row7["FirstName"];
                                }
                               $asterik=strlen($usrPass)-4;
                               $ViewPswdHint=substr($usrPass,0,2).str_repeat('*', $asterik).substr($usrPass,strlen($usrPass)-2,2);
   
                               $email_to=$usrEmailID;
                               $Subject = "Your Account Information";
                               $message = "<p>Dear User, </p>";
                               $message.= "<p>&nbsp;&nbsp;&nbsp;Your password hint is : <b>&nbsp;&nbsp;&nbsp;".$ViewPswdHint."</b></p>";
                               $message.= "<p>&nbsp;&nbsp;&nbsp; Please login to <b>$SysFullCompany </b>, <br/>&nbsp;&nbsp;&nbsp; In case of any issues please contact your Manager!</p>";
                               $Signature ="System Administrator";
   
                               //include "i_mail.php";       //---------- Include Mail Template
                                $string= random_strings(10);
                                $query_pass = "INSERT INTO `tPasswordReset`(`id`, `UserId`, `resetstring`, `UpdateDate`) VALUES ('','$RefUSR','$string','$currdatetime')";
                                $sql_pass = mysqli_query($mysqli, $query_pass);
                               
                                   $actual_link = "https://$_SERVER[HTTP_HOST]/subscription/resetpassword.php?USRIDNO=$RefUSR&rstr=$string&ToAct=SET";
       
                                   $to=$ResetUserEmail;
                               	$subject1= "Request for Password Reset";
                               	$fromName = "IT Team";
                               	$from = "support@teampod.co.uk"; 
                               	$message1 = "Hi ".$FirstName.",\r\n \r\nYour old password hint is :  ".$ViewPswdHint."\r\n \r\nAlso please find below link incase to reset your password - \r\n \r\nURL: ". $actual_link;
                               	$message1.="\r\n \r\nThanks & Regards, \r\n IT Team";
                                   $headers1 = "From: $fromName"." <".$from.">";
       
   	                            mail($to,$subject1,$message1,$headers1);
   	                            $emailsendmessage="Please check email to reset your password, incase not received check spam folder.";
                           /*    if(!$mail->send()) {
                                       $emailsendmessage='Error 02: There is an error in sending email please contact support team.';
                                   //echo 'Mailer Error: ' . $mail->ErrorInfo;
                               } else {
                                       $emailsendmessage='Please check your registered email for your login details.';
                               }
                           */   
                               
                                       //----------------- START Activity Log ---------------------
                                           $query12="INSERT INTO `tUserIPHostLog`( `RefUSR`,  `ActivityType`, `IPAddressHost`, `ActivityDT`)
                                                                           VALUES ('$RefUSR', 'Pswd Request',  '$loginfromip', '$currdatetime') " ;
                                           $sql12 = mysqli_query($mysqli, $query12);
                                       //----------------- END Activity Log ---------------------
   
               } else {
                   $emailsendmessage='Please check your registered email for your login details.';
               }      
   
           echo "<script> alert (' $emailsendmessage ');</script>";
           echo '<script> document.location="index.php";    </script>';
           
           }
           
           $NewUserEmail='';
           $emailurlLink='';
           
   } // ---end if REQUEST_METHOD===POST
   $_POST = array();

?>

<html>
   <head>
      <meta charset="utf-8">
      <title>TeamPod</title>
      
      <link rel="stylesheet" type="text/css" href="newstyle.css">
      </link>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
      <meta name="viewport" content="width=device-width, initial-scale=1">
      </meta>
      <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
      <style>    
         body {
            font-family: calibri;
            font-style: normal;
         }
         
        #UserEmail{
        background-image:url('images/LoginEmail.svg');
        background-position:98%;
        background-repeat:no-repeat;
        }
         
        /*#pswrd{
        background-image:url('images/LoginPswd.svg');
        background-position:98%;
        cursor:pointer;
        background-repeat:no-repeat;
        }*/
        
        form i {
        margin-left: -30px;
        cursor: pointer;
        }

         p.popup { font-size: 15px; margin: 0px; text-align: left;}
         .popup img {width:30%;border:#666 solid 1px;margin:40px 0 0 0}
         #blanket {
         background-color: #fff;
         opacity: 0.8;
         filter: alpha(opacity=0);
         position: absolute;
         z-index: 9001;
         top: 50%;
         left: 10%;
         width: 100%;
         }
         #popUpDiv {
         position: absolute;
         background-color: #fff;
         width: 420px;
         height: auto;
         z-index: 9002;
         padding: 20px;
         border:#333 1px solid;
         border-radius:5px;
         }
      </style>
      <script>

      $(document).on("keypress", "input.txtfield", function(e){
        if(e.which == 13){
        txtbox = $("input.txtfield");       
        currenttxtfieldNumber = txtbox.index(this);
        if (currenttxtfieldNumber > -1) {
        if (txtbox[currenttxtfieldNumber + 1] != null) {
            nextBox = txtbox[currenttxtfieldNumber + 1];

           if (nextBox.type === "submit") {
            nextBox.click();
            //id = nextBox.prop('id');
            //id.click();    
            }
            if (nextBox.type === "button") {
            nextBox.click();
            //id = nextBox.prop('id');
            //id.click();    
            }
            if (nextBox.type === "text") {
            nextBox.focus();
            nextBox.select();
            }
            if (nextBox.type === "date") {
            nextBox.focus();
            nextBox.select();
            }
        } 
        e.preventDefault();
        return false;
        }
        }
});
         function popup(windowname,page,rowid,srowid) {
         	//alert(page);
         	if (page == 'signup') {
         	if(rowid=='')
         	{
         	dataString= "cat=registermepopup";}
         	else
         	{dataString= "RefStr="+rowid+"&cat=registermepopup";}
         	//alert(dataString);
         	$.ajax({  
         	    type: "POST",  
         		url: "signup.php",  
         		data: dataString,
         		success: function(response)
         		{   
         		    $(".popup").html(response).show();
         		    
         		}
         		
         	});
            }
            
            if (page == 'resetpswd') {
         	if(rowid=='')
         	{
         	dataString= "cat=resetpswdpopup";}
         	else
         	{dataString= rowid+"&cat=resetpswdpopup";}
         	//alert(dataString);
         	$.ajax({  
         	    type: "POST",  
         		url: "resetpassword.php",  
         		data: dataString,
         		success: function(response)
         		{   
         		    $(".popup").html(response).show();
         		    
         		}
         		
         	});
            }
            
            
         blanket_size(windowname,page);
         window_pos(windowname);
         toggle('blanket');
         toggle(windowname);
         }
         
         function blanket_size(popUpDivVar,page) {
         
         	if (typeof window.innerWidth != 'undefined') {
         		viewportheight = window.innerHeight;
         	} else {
         		viewportheight = document.documentElement.clientHeight;
         	}
         	if ((viewportheight > document.body.parentNode.scrollHeight) && (viewportheight > document.body.parentNode.clientHeight)) {
         		blanket_height = viewportheight;
         	} else {
         		if (document.body.parentNode.clientHeight > document.body.parentNode.scrollHeight) {
         			blanket_height = document.body.parentNode.clientHeight;
         		} else {
         			blanket_height = document.body.parentNode.scrollHeight;
         		}
         	}
         	var blanket = document.getElementById('blanket');
         
         	blanket.style.height = blanket_height + 'px';
         	var popUpDiv = document.getElementById(popUpDivVar);
         	popUpDiv_height=blanket_height/2-200;//100 is half popup's height
         	popUpDiv.style.top = '100px';
         	popUpDiv.style.left = '400px';
         	
         }
         
         function window_pos(popUpDivVar) {
         	if (typeof window.innerWidth != 'undefined') {
         		viewportwidth = window.innerHeight;
         	} else {
         		viewportwidth = document.documentElement.clientHeight;
         	}
         	if ((viewportwidth > document.body.parentNode.scrollWidth) && (viewportwidth > document.body.parentNode.clientWidth)) {
         		window_width = viewportwidth;
         	} else {
         		if (document.body.parentNode.clientWidth > document.body.parentNode.scrollWidth) {
         			window_width = document.body.parentNode.clientWidth;
         		} else {
         			window_width = document.body.parentNode.scrollWidth;
         		}
         	}
         	var popUpDiv = document.getElementById(popUpDivVar);
         	//window_width=window_width/2-250;//250 is half popup's width
         	
         	if (window_width < 900){
         	popUpDiv.style.left = '60px';
         	} else {
         	    popUpDiv.style.left = '200px';
         	    popUpDiv.style.height = '400px';
         	}
         }
         
         function toggle(div_id) {
         	var el = document.getElementById(div_id);
         	if ( el.style.display == 'none' ) {	el.style.display = 'block';}
         	else {el.style.display = 'none';}
         }
             
         /*function ShowHidePswrd() {
           var x = document.getElementById("Password");
           if (x.type === "password") {
             x.type = "text";
           } else {
             x.type = "password";
           }
         }*/
         
         /*function ShowHidePswrd1() {
           var x = document.getElementById("pswrd");
           if (x.type === "password") {
             x.type = "text";
           } else {
             x.type = "password";
           }
         } 
         
        */

         
         
         
         function validcheckSignup(refusr)
         {
          //alert(refusr);
         
         NewUserEmail=document.getElementById('NewUserEmail').value;
         NewUserPassword=document.getElementById('Password').value;
         
         
            var letters = /^[a-zA-Z]+(\s{1}[a-zA-Z]+)*$/;
            var numbers = /^[0-9]\d{10}$/;
            var paswd=  /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/;
         
                 if(NewUserEmail=="")
         		{
         		
         		alert('Please Enter your Email');
         		  return false;
         		}
         
                 
         		
         			var atpos=NewUserEmail.indexOf("@");
         			var dotpos=NewUserEmail.lastIndexOf(".");
         
         			 
         			if (atpos<1 || dotpos<atpos+2 || dotpos+2>=NewUserEmail.length)
         			  {
         			  
         			  	alert('Please Enter Proper Email');
         			  return false;
         			  }
         
         		  
         		if(!NewUserPassword.match(paswd))
         		{
         		
         	      alert('Password not accepted');
         		  return false;
         		}
         
         if (refusr=='') {
         var dataString = "NewUserEmail=" + NewUserEmail + "&NewUserPassword=" + NewUserPassword + "&cat=createprof" ;}
         else
         {var dataString = "NewUserEmail=" + NewUserEmail + "&NewUserPassword=" + NewUserPassword + "&RefStr=" + refusr + "&cat=createprof" ;}
         
         //alert(dataString);
         $.ajax({  
         		type: "POST",  
         		url: "ajaxcalls.php",  
         		data: dataString,
         		success: function(response)
         		{   
         		   
         		    console.log(response);
         		    alert(response);
                    document.location='index.php';
         		   
         		    
         		}
         		
         		
         		
         		
         	});
                                           
         }
         
         
         function validcheckResetpswd()
         {
          
          UserEmail=document.getElementById('NewUserEmail').value;
            var letters = /^[a-zA-Z]+(\s{1}[a-zA-Z]+)*$/;
            var numbers = /^[0-9]\d{10}$/;
            
                 if(UserEmail=="")
         		{
         		
         		alert('Please Enter your Email');
         		  return false;
         		}
                	var atpos=UserEmail.indexOf("@");
         			var dotpos=UserEmail.lastIndexOf(".");
         			 
         			if (atpos<1 || dotpos<atpos+2 || dotpos+2>=UserEmail.length)
         			  {
         			   alert('Please Enter Proper Email');
         			   return false;
         			  }
         
         
         
         var dataString = "UserEmail=" + UserEmail + "&cat=resetpswd" ;
         
         $.ajax({  
         		type: "POST",  
         		url: "ajaxcalls.php",  
         		data: dataString,
         		success: function(response)
         		{   
         		   
         		    console.log(response);
         		    alert(response);
                    document.location='index.php';
         		}
         	});
         }
         
         
         
         
         </script>
   </head>
   <body>
       <div class=container style="width:100%;height:88%">
        <img src='images/signinbgimage.png' style="width:100%;height:100%;">
        <div class=top-right style="width:30%;position: absolute; top: 50%; left: 70%; transform: translate(-20%, -50%);background-color: #FFFFFF; opacity: 0.8; ;border-radius:4px;padding: 20px 20px 20px 20px;">
          <form action="" name="UserForm" method="post" onkeypress="return event.keyCode != 13;">
            <label style="font-size:32px;color:#333333;font-nweight: 600;line-height: 44px;">
            Sign in to TeamPod
            </label>
            <br>
            <label style="font-size:20px;color:#595959;font-weight: normal;line-height: 27px;">
            New to Teampod? &nbsp;
            </label>
            <a style="color:#005A9E;font-weight: 600;line-height: 27px;" onclick="popup('popUpDiv','signup','<?php echo $RefStr ?>')">Sign Up</a> 
            <br></br>
            <!-- <input type="radio" name="rbtn_bus" id="rbtn" value="BusinessAcct">Business Account   <input type="radio" name="rbtn_personal" id="rbtn" value="PersonalAcct">Personal Account  <br> 
            <br> -->
            <input type="text" style="font-size:16px;color:#333333;font-family: calibri;font-weight: normal;line-height: 20px;width:100%;border:1px solid #737373;border-radius:4px" class="form-control txtfield" id="UserEmail" tabindex="1" name="UserEmail" placeholder="Email address" autocorrect=off autocapitalize=words/>
            <br><br>
            <input type="password" style="font-size:16px;color:#333333;font-family: calibri;font-weight: normal;line-height: 20px;width:100%;border:1px solid #737373;border-radius:4px" class="active txtfield" id="pswrd" tabindex="2" name="pswrd" placeholder="Password"/>
            <i class="bi bi-eye-slash" id="togglePassword"></i>
            <br/><br/>
            <label class="lbl w120">&nbsp;</label><br>&nbsp;&nbsp;
            <input type="submit" class="btn btn-default btn-login txtfield" tabindex="3" name="btnSignIn" value="Sign In" />
            <br></br>
            <div style="width:100%;text-align:center">
            <a style="color:#005A9E;" class="homelink" onclick="popup('popUpDiv','resetpswd','')">Forgot your password?</a> 
            </div>
            <br></br>
            </form>
        </div>
       </div>
       <script>
    const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#pswrd');
       
   togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye / eye slash icon
    this.classList.toggle('bi-eye');
});
   </script>
   </body>
   <div style="display: none;" id="blanket"></div>
   <div style="display: none; background-color: #FFFFFF; opacity: 0.8; " id="popUpDiv">
      <a onclick="popup('popUpDiv')">
          <img style="margin:0px 0px 0px 0px; alt=" bamtus"="" src="https://teampod.co.uk/wp-content/uploads/2021/06/TeabPod-Logo25x.png">
          <img style="border:none;background-color: #FFFFFF; opacity: 0.8;float:right" alt="" src="..//TeamPod/images/Close.svg" /></a><br /><br />
      <div class="popup"></div>
   </div>
</html>
         
