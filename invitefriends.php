<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
error_reporting (E_ALL ^ E_NOTICE);

?>
<html>
<head>
   
<link rel="stylesheet" type="text/css" href="cssjs/newstyle.css"></link>

<?php
if($_POST['cat'] == "invitepopup"){    


    
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
        
     
        <div align="left" class="divpad50">

            <div class="row" >

                    <!-- New Registration --------------------------- START -->
                    <?php  {?> 
                    
                            </br>
                                <label class="lbl w120">  Email address </label>
                                <br><br> 
                                &nbsp;&nbsp;
                                <input type="text" class="form-control1"   id="NewUserEmail" name="NewUserEmail" placeholder="Email address"/>
                                <br><br> 
                                &nbsp;&nbsp;
            		        	<input type="checkbox" id="click" name="click" onclick="UseLicense()">&nbsp;Use My License
                                
                                
			                </br> 
                               
                            <button type="button" class="btn btn-default btn-login" name="btnInvite" value="Invite" onclick="inviteCheckEmail()">Send Invite</button>
                            
                            
                   
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