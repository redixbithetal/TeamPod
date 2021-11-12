<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
error_reporting (E_ALL ^ E_NOTICE);
include("taskheader.php");

//echo 'print coockies= '; print_r($_COOKIE);
if(isset($_COOKIE["name"]))           { $loginame=$_COOKIE["name"]; }
if(isset($_COOKIE["id"]))             { $id=$_COOKIE["id"]; }
if(isset($_COOKIE["CanSystemAdmin"])) { $CanSystemAdmin=$_COOKIE["CanSystemAdmin"]; }
//echo '<br>UserID='.$id.'...UserName='.$loginame.'...SysAdmin='.$CanSystemAdmin.'...AccessLevel='.$AccessLevel ;
GLOBAL $AccessLevel;

$AllTasksNameList = array();                                           
    $query1="SELECT distinct `TaskGroup`, `TaskTitle` FROM `tTasks` WHERE Status='ACT' ORDER BY `TaskTitle`";
    $sql1 = mysqli_query($mysqli, $query1);
    while($row1=mysqli_fetch_array($sql1))                        
    {
        $AllTasksNameList[]=array(
            'value'=>'',
           // 'label'=> ucfirst(strtolower($row1['TaskGroup']))." - ".ucfirst(strtolower($row1['TaskTitle']))
            'label'=> ucfirst(strtolower($row1['TaskTitle']))
            );
    } 
                
?>


<html>
<head>
<title>Tasks On Cloud</title>

	<meta charset="UTF-8">
        <link rel="shortcut icon" type="image/png" href="../images/icontask.png"/>
        <link rel="stylesheet" type="text/css" href="../focinc/newstyle.css"></link>
 	
	<!--Requirement jQuery-->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<!--Load Script and Stylesheet -->
	<script type="text/javascript" src="jquery.simple-dtpicker.js"></script>
	<link type="text/css" href="jquery.simple-dtpicker.css" rel="stylesheet" />
	<!---->
	
            <!-- --------------- START Auto Complete Text from Array -->
	<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/ui-darkness/jquery-ui.min.css" rel="stylesheet">
	<!-- DO NOT USE, As it is already used in Date Validation <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>  -->
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>

 <script>
        var complex = <?php echo json_encode($AllTasksNameList); ?>;
        data=complex;
        $(function() {
                $("#sTaskName").autocomplete({
                        source: data,
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
                                $("#sTRecRef").val(ui.item.value);
                                //$("#sTRecRef-value").val(ui.item.value);
                        }
                });
        });

        var complex2 = <?php echo json_encode($AllTasksGroupList); ?>;
        data2=complex2;
        $(function() {
                $("#sTaskGroup").autocomplete({
                        source: data2,
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
                                $("#sTaskGroup").val(ui.item.value);
                                //$("#sTaskGroup-value").val(ui.item.value);
                        }
                });
        });
        

</script>
            <!-- --------------- END Auto Complete Text from Array -->
        
 
            
<script>
    
$(document).ready(function(){
    $('#RepeatSchedule').on('change', function() {
      if ( this.value == 'Weekly')
        { 
            $("#DivSelectDay").show(); 
            var my_date  = document.getElementById('StartDate').value;
             my_date = my_date.substr(3, 2)+"-"+my_date.substr(0, 2)+"-"+my_date.substr(6);
            var d = new Date(my_date);
            day = d.getDay();
            
            //var my_date = "2014-06-03 07:59:48";
            //my_date = my_date.replace(/-/g, "/"); 
            
            
             var ele=document.getElementsByName('cbxDays[]');  
                for(var i=0; i<ele.length; i++){  
                    if (i==day-1) {
                    ele[i].checked=true;  
                    }
                    //alert (ele[i].value)
                }
                if (day==0) {
                    ele[6].checked=true;
                }
            
        }
      else
        { $("#DivSelectDay").hide(); }
      if ( this.value == '')
          { $("#DivSelectRepeat").hide(); }
      else
          { $("#DivSelectRepeat").show(); }
      if ( this.value == 'Daily') { document.getElementById('LblTextNext').innerHTML = 'DAYs' ;}
      if ( this.value == 'Weekly') { document.getElementById('LblTextNext').innerHTML = 'WEEKs' ;}
      if ( this.value == 'Monthly') { document.getElementById('LblTextNext').innerHTML = 'MONTHs' ;}
      if ( this.value == 'Yearly') { document.getElementById('LblTextNext').innerHTML = 'YEARs' ;}
        
    });
});       


function validtask()
{
    var v1 = document.getElementById('sTRecRef').value;
    //alert ('ID '+v1);
    if (v1=='')
    {
        document.getElementById('DivNewTgroup').style.display = 'block';
    }
}


function validate1()
{
    //var sdt = new Date(StartDate);
    //var ddt = new Date(DueDate);

    //alert ('SDT='+sdt);


    if (document.getElementById('ForCompany').value=='' || document.getElementById('ForCompany').value=='ALL')
    {
        document.getElementById("ForCompany").focus();   document.getElementById("ForCompany").style.borderColor = "red";
        document.getElementById('LblErrorMessage').innerHTML = 'Please select Company !' ;
        return false;
    }
    if (document.getElementById('ForRefUSR').value=='' || document.getElementById('ForRefUSR').value=='ALL')
    {
        document.getElementById("ForRefUSR").focus();   document.getElementById("ForRefUSR").style.borderColor = "red";
        document.getElementById('LblErrorMessage').innerHTML = 'Please select assign to user !' ;
        return false;
    }
    
    if (document.getElementById('sTaskName').value=='')
    {
        document.getElementById("sTaskName").focus();   document.getElementById("sTaskName").style.borderColor = "red";
        document.getElementById('LblErrorMessage').innerHTML = 'Please enter task title !' ;
        return false;
    }

    stdt = document.getElementById('StartDate').value;
    dudt = document.getElementById('DueDate').value;
/*    if (stdt > dudt) {
        alert("Start Date can not be greater than Due Date !");
        StartDate.style.borderColor = "red";
        DueDate.style.borderColor = "red";
	return false;
    }
  CHECK WHY NOT WORKING */ 
    
    //alert ('YES');

var selected = [];
for (var option of document.getElementById('ForRefUSR').options) {
 if (option.selected) {
    selected.push(option.value);
}
}

var awdays =[];
var checkboxes = document.querySelectorAll('#cbxDays:checked')

for (var i = 0; i < checkboxes.length; i++) {
  awdays.push(checkboxes[i].value)
}

document.getElementById('ForRefUSR1').value = selected;
document.getElementById('LblErrorMessage').innerHTML = '' ;
// document.getElementById('AddNewBtnClick').value='YESADDNEW';
// document.getElementById("TaskMgmt").submit();   

ForCompany=document.getElementById('ForCompany').value;
sTaskName=document.getElementById('sTaskName').value;
sTRecRef=document.getElementById('sTRecRef').value;
MainGroup=document.getElementById('MainGroup').value;
SubGroup=document.getElementById('SubGroup').value;
TaskDescription=document.getElementById('TaskDescription').value;
ForRefUSR1=selected;
chkPrivateTaskchk=document.getElementById('chkPrivateTask');
if (chkPrivateTaskchk.checked == true) { chkPrivateTask ="1"; } else { chkPrivateTask ="0";  } 
StartDate=document.getElementById('StartDate').value;
DueDate=document.getElementById('DueDate').value;
RepeatSchedule=document.getElementById('RepeatSchedule').value;
NextAfter=document.getElementById('NextAfter').value;
var ele = document.getElementsByName('radioNoOfTimes');
for(i = 0; i < ele.length; i++) {
    if(ele[i].checked)
    {
    radioNoOfTimes=ele[i].value;
    }
}
//radioNoOfTimes=document.getElementById('radioNoOfTimes').value;     
EndAfterOccur=document.getElementById('EndAfterOccur').value;
EndByDate=document.getElementById('EndByDate').value;
Priority=document.getElementById('priority').value;


var dataString = "ForCompany=" + ForCompany + "&sTaskName=" + sTaskName + "&sTRecRef=" + sTRecRef + "&MainGroup=" + MainGroup + "&SubGroup=" + SubGroup + "&Priority=" + Priority + "&TaskDescription=" + TaskDescription + "&ForRefUSR1=" + ForRefUSR1 + "&chkPrivateTask=" + chkPrivateTask + "&StartDate=" + StartDate + "&DueDate=" + DueDate + "&RepeatSchedule=" + RepeatSchedule + "&awdays=" + awdays + "&NextAfter=" + NextAfter + "&radioNoOfTimes=" + radioNoOfTimes + "&EndAfterOccur=" + EndAfterOccur + "&EndByDate=" + EndByDate + "&cat=createtask" ;

$.ajax({  
		type: "POST",  
		url: "ptaskload.php",  
		data: dataString,
		success: function(response)
		{
		    //alert(response);
		    
		    if (response.includes("Tasks is Scheduled")) {
		        alert ('Tasks is Scheduled !'); 
		        document.location='paddtask.php';
		    }
		    else {
		        alert ('Tasks is NOT Scheduled, please check criteria and try again !');
		    }
		    
		}
		
	});


}

function loadusers() {
    company = document.getElementById('ForCompany').value;
    var dataString = "company=" + company + "&cat=loadusers" ;
    $.ajax({  
		type: "POST",  
		url: "ptaskload.php",  
		data: dataString,
		success: function(response)
		{
			$("#userselect").html(response);
		}
		
	});
}

function SetWeekdays() {
    var my_date  = document.getElementById('StartDate').value;
             my_date = my_date.substr(3, 2)+"-"+my_date.substr(0, 2)+"-"+my_date.substr(6);
            var d = new Date(my_date);
            day = d.getDay();
            
             var ele=document.getElementsByName('cbxDays[]');  
                for(var i=0; i<ele.length; i++){  
                if (i==day-1) {
                    ele[i].checked=true;  
                    }
                    else {
                    ele[i].checked=false;      
                    }
                }
                if (day==0) {
                    ele[6].checked=true;
                }
}
</script>

</head>

<body>

<!--<form action="" name="TaskMgmt" id="TaskMgmt" method="post" enctype="multipart/form-data" target="_self" >    -->
    
    <input type=hidden name="AddNewBtnClick" id="AddNewBtnClick" value="" />
    

    
<div id="wrapper"><div  class="taskbox" style="margin-left:16%;width:50%;text-align:left">

<br/>
        <div class=WHITbkgBLackborder>
           <div style="padding: 5px 5px;">
               
        <br/><div class="labelcust">Company: </div>
           <select class="total_fields forminput" name="ForCompany" id="ForCompany" onchange="loadusers()">
                    <option value="">----- Select -----</option>
                        <?php  
                        $maxcompanycode = sizeof($CompanyCode_arr);
                        $i=0; 
                        while($i<$maxcompanycode)
                        {   
                            $valueof= $CompanyCode_arr[$i][0] ;
                            ?>    
                                <option value="<?php echo $valueof ?>"> <?php echo $CompanyCode_arr[$i][2] ?> </option>
                        <?php  $i++; } ?>
                </select>
        <br clear="all"/>
        <br clear="all"/>
           <div class="labelcust">Assign To:</div> 
        <div id="userselect">
        <select class="total_fields forminput" onChange ="checkedvalues()" name="ForRefUSR" id="ForRefUSR" multiple>
                        <option value="">----- Select Company First -----</option>
                </select>
        </div>
        <input type=hidden name="ForRefUSR1" id="ForRefUSR1" value="<?php echo $ForRefUSR1; ?>" />
         <script>
        document.multiselect('#ForRefUSR')
		.setCheckBoxClick("checkboxAll", function(target, args) {
			console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
		})
		.setCheckBoxClick("1", function(target, args) {
			console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
		});
		</script>

   <br clear="all"/>
  <br clear="all"/>
           
    <div class="labelcust">Task: </div>
    <input type=text class="total_fields forminput" name="sTaskName" id="sTaskName" value="<?php echo $sTaskName; ?>" />
    <input type=hidden name="sTRecRef" id="sTRecRef" value="<?php echo $sTRecRef; ?>" />

     <br clear="all"/>
    <br clear="all"/>
    <div class="labelcust">Main Group:</div> 
       <select class="total_fields forminput" name="MainGroup" id="MainGroup" onchange="loadsubgrp()" >
                <option value="">----- Select -----</option>
                    <?php  
                    $maxtaskmaingrouptitle = sizeof($AllTaskMainGroups_arr);
                    $i=0; 
                    while($i<$maxtaskmaingrouptitle)
                    {   
                        $valueof= $AllTaskMainGroups_arr[$i][0] ;
                     ?>    
                        <option value="<?php echo $valueof ?>"> <?php echo $AllTaskMainGroups_arr[$i][1] ?> </option>
                    <?php   $i++; } ?>
            </select>
    <br clear="all"/>
    <br clear="all"/>
    <div class="labelcust"> Sub Group:</div> 
    <div id="subgroup">
       <select class="total_fields forminput" name="SubGroup" id="SubGroup" >
                <option value="">----- Select Main Group First-----</option>
       </select>
    </div>
    
    <br clear="all"/>
    <br clear="all"/>
    <div class="labelcust"> Priority:</div> 
    <div>
       <select class="total_fields forminput" name="priority" id="priority" >
                <option value="P3" selected>P3 - Low</option>
                <option value="P2">P2 - Medium</option>
                <option value="P1">P1 - High</option>
       </select>
    </div>
    
     <div id='DivNewTgroup' style='display: none;'>
        <label class="txt w80">Task Group: </label>
        <input type=text class="total_fields" name="sTaskGroup" id="sTaskGroup" maxlength="20" style="width:80%" value="<?php echo $sTaskGroup; ?>" />
     </div>
     <br clear="all"/>
    <br clear="all"/>
    <div class="labelcust">Description:</div> 
    <textarea name=TaskDescription class="total_fields forminput" id=TaskDescription style="vertical-align: top;height:60px;border-radius:4px" rows=3  placeholder='Please provide task detail'></textarea>

    <br clear="all"/>
    <br clear="all"/>
    <div class="labelcust" style="">Private Task:</div>
    <input type="checkbox" class="" name="chkPrivateTask" id="chkPrivateTask" value="" ></input>
    <br clear="all"/>
    <br clear="all"/>
    <div class="labelcust">Start Date:</div>
    <input type="text" class="total_fields" style="width:120px" name="StartDate" id="StartDate" onChange=SetWeekdays() value=""/>
    <script type="text/javascript">
            $(function(){
                    $('*[name=StartDate]').appendDtpicker({
                            "inline": false,
                            "dateOnly": true,
                            "dateFormat": "DD-MM-YYYY",
                            "closeOnSelected": true
                    });
            });
    </script>
    <br clear="all"/>
    <br clear="all"/>
    <div class="labelcust">Due Date:</div>
    <input type="text" class="total_fields" style="width:120px" name="DueDate" id="DueDate" value=""/>
    <script type="text/javascript">
            $(function(){
                    $('*[name=DueDate]').appendDtpicker({
                            "inline": false,
                            "dateOnly": true,
                            "dateFormat": "DD-MM-YYYY",
                            "closeOnSelected": true
                    });
            });
    </script>
    
    <br clear="all"/>
    <br clear="all"/>
    <div class="labelcust">Repeat:</div>
    <select class="total_fields" name="RepeatSchedule" id="RepeatSchedule" style="width:40%" >
        <?php  if ($RepeatSchedule == "") { ?> <option value="" selected > <?php } else{ ?> <option value="" > <?php } ?> --- No ---</option>
        <?php  if ($RepeatSchedule == "Daily") { ?> <option value="Daily" selected > <?php } else{ ?> <option value="Daily" > <?php } ?> Daily</option>
        <?php  if ($RepeatSchedule == "Weekly") { ?> <option value="Weekly" selected > <?php } else{ ?> <option value="Weekly" > <?php } ?> Weekly</option>
        <?php  if ($RepeatSchedule == "Monthly") { ?> <option value="Monthly" selected > <?php } else{ ?> <option value="Monthly" > <?php } ?> Monthly</option>
        <?php  if ($RepeatSchedule == "Yearly") { ?> <option value="Yearly" selected > <?php } else{ ?> <option value="Yearly" > <?php } ?> Yearly</option>
    </select>
    <br clear="all"/>
    <br clear="all"/>
    <br clear="all"/>
    
    <div id="DivSelectDay" style="display:none;">
    &nbsp;&nbsp;&nbsp;
        Mo <input type=checkbox id=cbxDays name=cbxDays[] value=Mon <?php if ($cbxDays[0]=="Mon") {echo 'checked';} ?> > &nbsp;&nbsp;
        Tu <input type=checkbox id=cbxDays name=cbxDays[] value=Tue <?php if ($cbxDays[1]=="Tue") {echo 'checked';} ?> > &nbsp;&nbsp;
        We <input type=checkbox id=cbxDays name=cbxDays[] value=Wed <?php if ($cbxDays[2]=="Wed") {echo 'checked';} ?> > &nbsp;&nbsp;
        Th <input type=checkbox id=cbxDays name=cbxDays[] value=Thu <?php if ($cbxDays[3]=="Thu") {echo 'checked';} ?> > &nbsp;&nbsp;
        Fr <input type=checkbox id=cbxDays name=cbxDays[] value=Fri <?php if ($cbxDays[4]=="Fri") {echo 'checked';} ?> > &nbsp;&nbsp;
        Sa <input type=checkbox id=cbxDays name=cbxDays[] value=Sat <?php if ($cbxDays[5]=="Sat") {echo 'checked';} ?> > &nbsp;&nbsp;
        Su <input type=checkbox id=cbxDays name=cbxDays[] value=Sun <?php if ($cbxDays[6]=="Sun") {echo 'checked';} ?> > &nbsp;&nbsp;

    </div>
    <br clear="all"/>
    <br clear="all"/>
    <div id="DivSelectRepeat" style="display:none;">
    <div class="labelcust1">Next Task After:</div>
    <input type="text" class="total_fields" style="width:50px" name="NextAfter" id="NextAfter" value="1"/>&nbsp;&nbsp;<label id="LblTextNext"></label>
    <br clear="all"/>
    <br clear="all"/>

    <div class="labelcust1"><input type="radio" name="radioNoOfTimes" id="radioNoOfTimes" value="EndAfter" checked>&nbsp;&nbsp;End After </input></div><input type="text" class="total_fields" style="width:50px" name="EndAfterOccur" id="EndAfterOccur" value="10"/> Occurrences
    <br clear="all"/>
    <br clear="all"/>
    <div class="labelcust1"><input type="radio" name="radioNoOfTimes" id="radioNoOfTimes" value="EndBy">&nbsp;&nbsp;End By </input></div><input type="text" class="total_fields" style="width:120px" name="EndByDate" id="EndByDate" value=""/>
    <br clear="all"/>
    <br clear="all"/>
    <div class="labelcust1" style="width:50%"><input type="radio" name="radioNoOfTimes" id="radioNoOfTimes" value="NoEnd">&nbsp;&nbsp;End after 10 years </input></div>

    <script type="text/javascript">
            $(function(){
                    $('*[name=EndByDate]').appendDtpicker({
                            "inline": false,
                            "dateOnly": true,
                            "dateFormat": "DD-MM-YYYY",
                            "closeOnSelected": true
                    });
            });
    </script>
    
    </div>

    <br clear="all"/>
    <br clear="all"/>
            <br/>
                <div align="center"><font style="color:red;"><label id="LblErrorMessage"></label></font></div>
            <br clear="all"/>
            <br clear="all"/>
            <input type=button name="btnSave"  value="Save" style="margin-left:30%;font-weight:bold" class='btn btn-default btn-login' onclick="validate1()" />
            
	<br clear="all"/>
	<br clear="all"/>
    </div>
    </div>
</div></div>    <!-- wrapper & center -->


<!-- </form> -->


</body>
</html>    