<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php 
error_reporting (E_ALL ^ E_NOTICE);




include "dbhands.php";

$RefStr = $_POST['RefStr'];

?>


<html>
<head>
    
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="newstyle.css"></link>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1"></meta>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
  <style>
      #NewUserEmail{
        background-image:url('images/LoginEmail.svg');
        background-position:98%;
        background-repeat:no-repeat;
        }
        
        form j {
        margin-left: -30px;
        cursor: pointer;
        }
        
  </style>      

        
<?php
if($_POST['cat'] == "registermepopup"){    


    
}

?>

</head>

<div class=container style="width:100%;height:88%;">
    <div class=top-right style="width:70%;position: absolute; top: 15%; left: 10%; background-color: #FFFFFF; opacity: 0.8; padding: 20px 20px 20px 20px;">
        <form action="" name="UserForm" method="post" onkeypress="return event.keyCode != 13;">
            <input type="text" style="font-size:16px;color:#333333;font-family: calibri;font-weight: normal;line-height: 20px;width:100%;border:1px solid #737373;border-radius:4px" class="form-control1"   id="NewUserEmail"  name="NewUserEmail" placeholder="* Email address"/>
            <br>
            <br>
            <br>
            <input type="password" style="font-size:16px;color:#333333;font-family: calibri;font-weight: normal;line-height: 20px;width:100%;border:1px solid #737373;border-radius:4px"  class="form-control1" id="Password" name="Password" placeholder="* Password"/>
            <j class="bi bi-eye-slash" id="togglePassword1"></j>
            <p style="font-size:0.8vw;">&emsp; &emsp; <i>[Enter 8 to 15 characters which contains lowercase letter, uppercase letter, numeric digit, special character]</i></p>
			</br> 
            <button type="button" class="btn btn-default btn-login" name="btnRegisterMe" value="RegisterMe" onclick="validcheckSignup('<?php echo $RefStr ?>')">Register Me</button>
            <br></br>
        </form>
    </div>
</div>

 <script>
    const togglePassword1 = document.querySelector('#togglePassword1');
const password1 = document.querySelector('#Password');
       
   togglePassword1.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password1.getAttribute('type') === 'password' ? 'text' : 'password';
    password1.setAttribute('type', type);
    // toggle the eye / eye slash icon
    this.classList.toggle('bi-eye');
});
   </script>       


    
</body>
</html>
 

