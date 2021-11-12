<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php 
error_reporting (E_ALL ^ E_NOTICE);




include "dbhands.php";

//$RefStr = $_POST['RefStr'];

?>


<html>
<head>
    
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="newstyle.css"></link>
        <meta name="viewport" content="width=device-width, initial-scale=1"></meta>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        

        
<?php
if($_POST['cat'] == "resetpswdpopup"){    


    
}

?>

</head>


<body style="background-color: #FFFFFF;">

<form action="" name="UserForm" method="post" onkeypress="return event.keyCode != 13;">
<table cellpadding="0" cellspacing="0" width="100%"><tr>     <!-- section 1 -->
    <td valign="top" >
            
        <div style="display:none"><input type=password style="width:0" ></div>
            
       <div class="homebkgimage"  >
           
        <div class="divheader1">
            <div style="float:left;">
            <div style="float:right;">  </div>
            <br><br><br> <br><br><br>
            &nbsp;&nbsp;
        
        </div>
        <br><br> 
        &nbsp;&nbsp;
     
        <div align="left" class="divpad50">

            <div class="row" >

                    <!-- New Registration --------------------------- START -->
                    <?php  { ?> 
                    <div class=top-right style="width:70%;position: absolute; top: 15%; left: 10%; background-color: #FFFFFF; opacity: 0.8; padding: 20px 20px 20px 20px;">
                                <label style="font-size:16px;color:#595959;font-weight: normal;line-height: 27px;">Registered Email ID</label><br>
                                <input type="text" style="font-size:16px;color:#333333;font-family: calibri;font-weight: normal;line-height: 20px;width:90%;border:1px solid #737373;border-radius:4px" class="form-control1"   id="NewUserEmail"  name="NewUserEmail" placeholder="* Email address"/>
                                
            		        	<br><br><br>
                                &nbsp;
                                
			                </br> 
                               
                            <button type="button" class="btn btn-default btn-login" name="btnResetpswd" value="Resetpswd" onclick="validcheckResetpswd()">Submit</button>
                            
                        </div>    
                   
                        <br></br>

                    <?php } ?>
       
                  <!-- col-2 --->               
            </div>  <!-- row --->
        </div>  <!-- divpad50 --->
        
    </div>  <!-- homebkgimage --->
    
    </td></tr>


  
</table>
         
</form>

    
</body>
</html>
 

