<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,200;0,300;0,400;0,600;0,900;1,200;1,300;1,400;1,600;1,700;1,900&display=swap" rel="stylesheet">
<?php 
if($_POST['btntaskadd']) {
$sTaskName= $_POST['task'];
$ForCompany=$_POST['company'];
$TaskDescription=$_POST['descr'];
//$TaskMainGroup=$_POST['TaskMainGroup'];
//$TaskSubGroup=$_POST['TaskSubGroup'];

$query3="INSERT INTO `tTasks`(`TaskMainGroup`, `TaskSubGroup`, `TaskGroup`,  `TaskTitle`, `Status`, `Priority`, `CreatedBy`, `CreatedDateTime`) 
                              VALUES ( '0',    '0',  '0', '$sTaskName',  'ACT', 'P3', '$id', '$currdatetime' ) " ;
$sql3 = mysqli_query($mysqli, $query3);
$sTRecRef= $mysqli->insert_id;

$query4="INSERT INTO `tSchedule`(`TRecRef`, `TaskDescription`, `ForCoRecRef`,  `PrivateTask`,  `StartDate`,    `DueDate`,  `DueDays`, `RepeatSchedule`, `AssignDT`, `AssignedBy`) 
                             VALUES ('$sTRecRef','$TaskDescription','$ForCompany', '0','$current_date','$current_date','1','', '$currdatetime','$id' ) " ;
$sql4 = mysqli_query($mysqli, $query4);
$NewRecID= $mysqli->insert_id;

$query5="INSERT INTO `tCalendar`( `SRecRef`, `TRecRef`, `ForRefUSR` ,`cScheduleDate`, `cDueDate`) 
                                 VALUES ('$NewRecID', '$sTRecRef' ,'$id','$current_date','$current_date' ) " ;
$sql5 = mysqli_query($mysqli, $query5);
    
}
?>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>


<style>
   #myInput{
   background-image:url('images/Search.svg');
   background-position:99% 2px;
   background-repeat:no-repeat;
   }
   #myQuickTask{
   background-image:url('images/QuickTask.svg');
   background-position:5% 3px;
   background-repeat:no-repeat;
   }
   .sidenav1 {   
    width: 720px;
    height: 900px;
    padding-top: 0px;
   }
   .col-md-6{
   width: 50%;
   float: left;
   }
   .fl{
   float: left;
   }
   .fr{
   float: right;
   }
   .filter_label {
        margin-left: 15px;
        font-size: 18px;
        font-weight: 600;
        color: #333333;
        font-family: 'Source Sans Pro', sans-serif;
    }

    .filter_label_sidenav{
        margin-left: 15px;
        font-size: 18px;
        font-weight: 600;
        width: 49px;
        height: 23px;
        color: #333333;
        font-family: 'Source Sans Pro', sans-serif;
    }
   .times_a{
      margin-top: 5px;
      font-size: 22px;
      font-weight: normal;
      margin-left: 15px;
      width: 24px;
      height: 24px;
   }
   .btn-save{
         color: white;
         background: #e74c3c;
         border: 1px solid #e74c3c;    
         border-radius: 35%;
         font-size: 14px;
         font-weight: 600;
         letter-spacing: 1px;
         line-height: 15px;
         width: 102px;
         height: 32px;
         border-radius: 24px;
         transition: all 0.3s ease 0s;
         font-family: 'Source Sans Pro', sans-serif;
   }
   .p-3 {
  /* padding: 14px 10px 47px 12px;*/
   }
   .mb-5, .my-5 {
   margin-bottom: 3rem!important;
   }
   .shadow {
      box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
       }
   .rounded {
   border-radius: 0.25rem !important;
   }
   .bg-white {
   background-color: #fff!important;
   }
   .field_filter{
       width: 260px;
    float: left;
    height: 900px;
    border-right: 2px solid gray;
   }
   .fieks_content{
   width: 458px;
   float: right;
   background: #f2f2f2;
   height: 100vh;
       overflow: auto;
   }
   .field_list{
   list-style: none;
   width: 100%;
   padding-left: 0px;
   }
   .contanier_row{
   height: 100vh;
   }
   .resetview{
      color: #005a9e;
      float: left;
      margin-left: 15px;
      font-weight: normal;
      font-size: 14px;
      font-family: 'Source Sans Pro';
      margin-bottom: 25px;
   }
   .reset_view_div{
   font-size: 15px;
   float: left;
   width: 100%;
   margin-top: 20px;
   }
   ul.field_list li {
   border-width: 1px;
   font-weight: 600;
   }
   .li_button{
   background: none;
   border: none;
   font-size: 16px;
   border-width: 1px;
   font-weight: 600;
   border-bottom: 2px solid #ccc;
       width: 215px;
    height: 48px;
    padding: 14px;
   }
   .show{
   display: block;
   }
   .hide{
   display: none;
   }
   .active{        
   background: #eeeeee;
   border: 2px solid #4b7902;
   border-radius: 24px;
   transition: all 0.3s ease 0s;
   }
   .active_div{
   width: 8px;
   height: 8px;
   border-radius: 50%;
   left: 0px;
   top: -4px;
   float: right;
   background: red;
   margin-top: 5px;
   }
   input[type=checkbox] + label {
   display: block;
   margin: 0.2em;
   cursor: pointer;
   padding: 0.2em;
   float: left;
   }
   input[type=checkbox] {
   display: none;
   }
   input[type=checkbox] + label:before {
   content: "\2714";
   border: 0.1em solid #f7d991;
   border-radius: 0.2em;
   display: inline-block;
   width: 24px;
   height: 24px;
   padding-left: 0.2em;
   padding-bottom: 0.3em;
   margin-right: 10px;
   vertical-align: bottom;
   color: transparent;
   transition: .2s;
   }
   input[type=checkbox] + label:active:before {
   transform: scale(0);
   }
   input[type=checkbox]:checked + label:before {
   background-color: #f7d991;
   border-color: #f7d991;
   color: #e74c3c;
   }
   input[type=checkbox]:disabled + label:before {
   transform: scale(1);
   border-color: #aaa;
   }
   input[type=checkbox]:checked:disabled + label:before {
   transform: scale(1);
   background-color: #bfb;
   border-color: #bfb;
   }
   .checkbox_div{
   float: left;
   margin-left: 25px;
   width: 100%;
   }
   #quickbox1{
      width: 30%;
   }
   .card {
  display: grid;
    grid-template-rows: 1fr auto max-content;
        width: 504px;
    height: 804px;
    aspect-ratio: 1 / 1.5;
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
    margin-top: 18px;
    box-shadow: 0 1px 2px 0px rgb(0 0 0 / 20%), 0 2px 4px 0px rgb(0 0 0 / 20%), 0 4px 8px 0px rgb(0 0 0 / 20%), 0 8px 16px 0px rgb(0 0 0 / 7%), 0 16px 32px 0px rgb(0 0 0 / 13%), 0 32px 48px 0px rgb(0 0 0 / 20%);
}

.card-header {
  display: grid;
  position: relative;
  overflow: hidden;
}
.card-header::before {
  content: "";
  position: absolute;
  display: flex;
  inset: 0;
  background: url(https://cdn.pixabay.com/photo/2017/06/26/08/13/mockup-2443050_960_720.jpg)
    center/cover;
  transition: transform 300ms ease;
}

.card-header:hover::before {
  transform: scale(1.2);
  transition: transform 266ms linear;
}

.title {
  text-align: center;
  width: 12ch;
  text-transform: uppercase;
  font-weight: 800;
  line-height: 1.2;
  font-size: 2.6rem;
  margin: auto;
  -webkit-text-stroke: 1.5px #0005;
  color: hsla(0, 100%, 100%, 0.8);
  text-shadow: 2px 2px 1px #0001, 2px 3px 2px #0002, 3px 4px 3px #0003,
    3px 5px 5px #0003, 4px 5px 7px #0004, 4px 6px 10px #0005;
  transform: rotate(-10deg);
}

.title:first-letter {
  color: hsla(0, 100%, 100%, 0.8);
  text-shadow: 3px 3px 0px teal;
  font-size: 1.5em;
}
.card-content {
  display: grid;
  grid-template-rows: repeat(3, 1fr);
}
.card-content,
.card-footer {
  margin-inline: 1rem;
}

.card-footer {
  align-self: end;
  margin-block-end: 2rem;
}

.secondary-title {
  width: max-content;
  text-transform: capitalize;
  font-weight: 400;
  color: #444;
  font-size: 1.6rem;
  margin-block: 1rem 2rem;
  position: relative;
}

.secondary-title::before {
  content: "";
  position: absolute;
  display: inline-flex;
  width: calc(100% + 0.5em);
  top: 2.5rem;
  border-bottom: 3px solid teal;
}

.text {
  color: #0008;
  font-size: 0.9rem;
  line-height: 1.3;
  font-weight: 300;
  max-width: 28ch;
}


.quick_content {
    display: flex;
    justify-content: center;
    width: 576px;
    color: #f2f2f2;
    height: 900px;
}


      .wrapper {
        padding: 25px 14px;
      }
      /* title-start */
      .wrapper .title h2 {
        text-align: center;
        font-size: 40px;
        margin-bottom: 22px;
      }
      /* title-end */
      /* p-one-start */
      .wrapper .p-one p {
        color: #95959d;
        padding: 0 10px;
        text-align: center;
        font-size: 14px;
        margin-bottom: 50px;
      }
      /* p-one-end */
      /* inp-email-start */
      .wrapper .inp {
        position: relative;
      }
      .wrapper .inp {
        display: flex;
        flex-direction: column;
      }

      .wrapper .inp select {
        border: 0;
        border: 1px solid #e6e6e6;
        width: 100%;
        outline: none;
        height: 51px;
        padding: 0 20px;
        border-radius: 4px;
        background-image: url(data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20256%20448%22%20enable-background%3D%22new%200%200%20256%20448%22%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E.arrow%7Bfill%3A%23424242%3B%7D%3C%2Fstyle%3E%3Cpath%20class%3D%22arrow%22%20d%3D%22M255.9%20168c0-4.2-1.6-7.9-4.8-11.2-3.2-3.2-6.9-4.8-11.2-4.8H16c-4.2%200-7.9%201.6-11.2%204.8S0%20163.8%200%20168c0%204.4%201.6%208.2%204.8%2011.4l112%20112c3.1%203.1%206.8%204.6%2011.2%204.6%204.4%200%208.2-1.5%2011.4-4.6l112-112c3-3.2%204.5-7%204.5-11.4z%22%2F%3E%3C%2Fsvg%3E%0A);
        background-position: right 10px center;
        background-repeat: no-repeat;
        background-size: auto 50%;
        border-radius: 2px;
        /* border: none; */
        /* color: #ffffff; */
        padding: 10px 30px 10px 10px;
        outline: none;
        -moz-appearance: none;
        -webkit-appearance: none;
        appearance: none;
      }
      .wrapper .inp select:focus {
        border: 1px solid #e0e3ff;
      }

      .wrapper .inp select::placeholder {
        color: #a5a4ac;
        font-size: 12px;
      }
      .wrapper .inp label.email {
             position: absolute;
             left: 12px;
             top: -10px;
                 color: #737373;
    font-size: 12px;
    font-family: 'Source Sans Pro';
    font-weight: normal;
      }

      .wrapper .inp::before {
        position: absolute;
        content: "";
        background-color: rgb(255, 255, 255);
        left: 10px;
            width: 54px;
    height: 21px;
        top: -10px;
      }
      .wrapper .inp i.email {
        position: absolute;
        right: 18px;
        top: 17.5px;
        font-size: 20px;
        color: #cfcfd3;
      }
      .wrapper .inp .email-margin {
        margin-bottom: 40px;
      }

      /* inp-email-end */
      /* inp-pass-start */
      .wrapper .inp label.pass {
        position: absolute;
        top: 80px;
        left: 20px;
        z-index: 2;
        color: #95959d;
      }
      .wrapper .inp::after {
        position: absolute;
        content: "";
        background-color: rgb(255, 255, 255);
        left: 12px;
        width: 87px;
        height: 21px;
        top: 79px;
      }


      .wrapper .inp i.lock {
        position: absolute;
        bottom: 15.3px;
        right: 20px;
        font-size: 20px;
        color: #cfcfd3;
      }

      /* inp-pass-end */
      /* btn-start */
      .wrapper .button {
        display: flex;
        justify-content: center;
      }
      .wrapper .button .btn {
        margin-top: 50px;
        border: 0;
        width: 100%;
        height: 60px;
        background-color: rgb(32, 40, 235);
        color: #fff;
        border-radius: 4px;
        font-size: 16px;
        margin-bottom: 26px;
        cursor: pointer;
      }
      .wrapper .button .btn:hover {
        box-shadow: 1px 1px 20px rgba(32, 40, 235, 0.2);
      }

      /* btn-end */
      /* line-start */
      .wrapper .line {
        width: 100%;
        text-align: center;
        border-bottom: 1px solid #f0f1ff;
        line-height: 0.1em;
        margin: 30px;
        margin-bottom: 40px;
        margin-left: 0%;
      }
      .wrapper .line span {
        background: #fff;
        padding: 0 10px;
        color: #afafb5;
        font-size: 14px;
      }

      /* line-end */
      /* icon-wrapper-start */

      .wrapper .icon-wrapper {
        display: flex;
        justify-content: space-between;
        /* align-items: center; */
      }
      .wrapper .icon-wrapper .google,
      .wrapper .icon-wrapper .facebook,
      .wrapper .icon-wrapper .apple {
        width: 100px;
        height: 50px;
        background-color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 4px;
        font-size: 22px;
        border: 1px solid #d9dcff;
        margin-bottom: 30px;
        cursor: pointer;
      }
      .wrapper .icon-wrapper .google:hover {
        box-shadow: rgba(0, 0, 0, 0.09) 0px 3px 12px;
      }
      .wrapper .icon-wrapper .facebook:hover {
        box-shadow: rgba(0, 0, 0, 0.09) 0px 3px 12px;
      }
      .wrapper .icon-wrapper .apple:hover {
        box-shadow: rgba(0, 0, 0, 0.09) 0px 3px 12px;      }

      /* icon-wrapper-end */
      /* down-start */
      .wrapper .down {
        text-align: center;
        font-size: 14px;
      }
      .wrapper .down span {
        color: #b8b7be;
      }
      label.task_name {
          position: absolute;
          left: 12px;
          top: -10px;
             color: #737373;
    font-size: 12px;
    font-family: 'Source Sans Pro';
    font-weight: normal;

      }
      input#task_name {
    border: 0;
    border: 1px solid #e6e6e6;
    width: 100%;
    outline: none;
    height: 51px;
    padding: 0 20px;
    border-radius: 4px;
    color: #333333;
    font-size: 16px;
    font-family: 'Source Sans Pro';
    font-weight: normal;
}
 label.description {
          position: absolute;
          left: 12px;
          top: -10px;
           color: #737373;
    font-size: 12px;
    font-family: 'Source Sans Pro';
    font-weight: normal;
      }
      textarea {
    border: 0;
    resize: vertical;
    border: 1px solid #e6e6e6;
    width: 100%;
    outline: none;
    height: 200px;
    padding: 8px 20px;
    border-radius: 4px;
    color: #333333;
    font-size: 16px;
    font-family: 'Source Sans Pro';
    font-weight: normal;
}
button.fr.btn-save {
    margin-right: 15px;
}

*, *:before, *:after {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}





      /* down-end */

</style>
<div class="maindiv" id="wrapper" style="margin:15px 0 0 16%;width:84%">
<div>
   <input type="text" id="myInput" onkeyup="fnTypeFilterRow()" style="background-color: #fff;float:left;width:40%;border-radius:25px" placeholder="Search by Task or Maingroup or Subgroup " title="Type in task name" class="form-control  total_fields" />
</div>
<div style="width:38%;float:left;">&nbsp; </div>
<div >
   <!-- <input type=button id="myQuickTask" class='btn btn-default' title="Add New Task" value="Quick Task" style="font-family:'Open Sans';width:150px;height:32px;border-radius:25px;margin:0px 16px 0px 0px;" onclick="popup('popUpDiv','addtask','')"/> -->
   <input type=button id="myQuickTask" class='btn btn-default' title="Add New Task" value="Quick Task" style="font-family:'Open Sans';width:150px;height:32px;border-radius:25px;margin:0px 16px 0px 0px;" onclick="quickboxfilter()"/>
   
   <img style="border:none;background:#eee;float:right;cursor: pointer;margin:0px 40px 0px 0px;" alt="" src="images/Filters.svg" onclick="openfilter()" />
</div>
<br clear='all'/>
<div class=sidenav1 id=quickbox1>
  
      <div class="row shadow p-3 bg-white rounded" style="width: 576px;height: 48px;">
         <div class="col-md-6" style="margin-top: 5px;">
            <a onclick="quickboxclosefilter()" class="fl times_a"><i class="fas fa-times"></i></a> 
            <label class="fl filter_label" > 
            Quick Task
            </label>
         </div>
         <div class="col-md-6" style="margin-top: 5px;">
            <button type="button" onclick="submitquickbox()"  name="btntaskadd"  class="fr btn-save">Save</button>
         </div>
      </div>
      <div class="contanier_row">
         <div class="quick_content">
            <main class="card">
               <article class="card-content">
                  <div class="wrapper">
                     <div class="inp">
                        <label class="email" for="company_quickbox"> Company*</label>
                        <select id="company_quickbox" name="company" >
                           <option value="">----- Select -----</option>
                           <?php  
                              $maxcompanycode = sizeof($AllCompanyCode_arr);
                              $i=0; 
                              while($i<$maxcompanycode)
                              {   
                                  $valueof= $AllCompanyCode_arr[$i][0] ;
                                  ?>    
                           <option value="<?php echo $valueof ?>"> <?php echo $AllCompanyCode_arr[$i][2] ?> </option>
                           <?php  $i++; } ?>
                        </select>
                     </div>
                     <div class="inp" style="margin-top: 20px;">
                        <label class="task_name" for="task_name"> Task Name*</label>
                        <input type="text" name="task" id="task_name"  >
                     </div>
                     <div class="inp" style="margin-top: 20px;">
                        <label class="description" for="description"> Description</label>
<<<<<<< Updated upstream
                        <textarea name="descr" id="description" rows="15"></textarea>
=======

                        <textarea name="descr" id="description" rows="15" placeholder="Please Provide Task Detail"></textarea>

>>>>>>> Stashed changes
                     </div>
                  </div>
               </article>
               <footer class="card-footer">
                  
               </footer>
            </main>
         </div>
      </div>
  
</div>
 <!-- <div class=sidenav1 id=sidenav1>
   <label style="width:100px;float:left"> Filter by:</label> <a onclick="closefilter()"><img style="border:none;background:#fff;float:right;margin-right:10px" alt="" src="images/Close.svg" /></a><br/><br/>
   <select class="total_fields forminput" name="ForCompany" style="width:200px">
      <?php  $i=0; 
      while($i<$maxcompanycode)
      {   
          $valueof= $AllCompanyCode_arr[$i][0] ;
          
          if ($ForCompany == $valueof) {  ?>
      <option value="<?php echo $valueof; ?>" selected> <?php echo $AllCompanyCode_arr[$i][2] ?></option>
      <?php } else{ ?>    
      <option value="<?php echo $valueof ?>"> <?php echo $AllCompanyCode_arr[$i][2] ?> </option>
      <?php  }  $i++; } ?>
   </select>
   <br clear="all"/><br clear="all"/>
   <select class="total_fields forminput" name="MainGroup" id="ForCompany" style="width:200px">
      <option value="">... All MainTask Groups ...</option>
      <?php  
      $maxtaskmaingrouptitle = sizeof($AllTaskMainGroups_arr);
      $i=0; 
      while($i<$maxtaskmaingrouptitle)
      {   
          $valueof= $AllTaskMainGroups_arr[$i][0] ;
          if ($MainGroup == $valueof) {  ?>
      <option value="<?php echo $valueof; ?>" selected> <?php echo $AllTaskMainGroups_arr[$i][1] ?></option>
      <?php } else{ ?>    
      <option value="<?php echo $valueof ?>"> <?php echo $AllTaskMainGroups_arr[$i][1] ?> </option>
      <?php  }  $i++; } ?>
   </select>
   <br clear="all"/><br clear="all"/>
   <select class="total_fields forminput" name="SubGroup" id="ForCompany" style="width:200px" >
      <option value="">... All SubTask Groups ...</option>
      <?php  
      $SubGroupsOfMain_arr=getSubGroupOfMain($MainGroup);
      $maxtasksubgrouptitle = sizeof($SubGroupsOfMain_arr);
      $i=0; 
      while($i<$maxtasksubgrouptitle)
      {   
          $valueof= $SubGroupsOfMain_arr[$i][0] ;
          if ($SubGroup == $valueof) {  ?>
      <option value="<?php echo $valueof; ?>" selected> <?php echo $SubGroupsOfMain_arr[$i][1] ?></option>
      <?php } else{ ?>    
      <option value="<?php echo $valueof ?>"> <?php echo $SubGroupsOfMain_arr[$i][1] ?> </option>
      <?php  }  $i++; } ?>
   </select>
   <br clear="all"/><br clear="all"/>
   <select class="total_fields forminput" name="ForRefUSR" style="width:200px">
      <?php  $i=0; 
      while($i<$maxusercodename)
      {   
          $valueof= $UserCodeName_arr[$i][0] ;
          
          if ($ForRefUSR == $valueof) {  ?>
      <option value="<?php echo $valueof; ?>" selected> <?php echo $UserCodeName_arr[$i][1] ?></option>
      <?php } else{ ?>    
      <option value="<?php echo $valueof ?>"> <?php echo $UserCodeName_arr[$i][1] ?> </option>
      <?php  }  $i++; } ?>
   </select>
   <br clear="all"/><br clear="all"/>
   <select class="total_fields forminput" name="ForTaskTag" style="width:200px">
      <option value="">ALL Tags</option>
      <?php  $i=0; 
      while($i<$maxtasktags)
      {   
          $valueof= $AllTasksTagList_arr[$i][0] ;
          
          if ($ForTaskTag == $valueof) {  ?>
      <option value="<?php echo $valueof; ?>" selected> <?php echo $AllTasksTagList_arr[$i][0] ?></option>
      <?php } else{ ?>    
      <option value="<?php echo $valueof ?>"> <?php echo $AllTasksTagList_arr[$i][0] ?> </option>
      <?php  }  $i++; } ?>
   </select>
   <br clear="all"/><br clear="all"/>
   <div style="width:160px">
      <input type="checkbox" name="chkViewCompleted" id="chkViewCompleted" value="YES" <?php echo $IsChekedMark; ?> >&nbsp;&nbsp;View Completed &nbsp;</input>
   </div>
   <br clear="all"/><br clear="all"/>
   <input type=submit name="btnFilter"  value="Filter" style="margin-left:20px;height:32px;width:100px" class='btn btn-default' />
   </div> --> 
 <div class=sidenav1 id=sidenav1>
<div class="row shadow p-3 bg-white rounded" style="height: 48px;">
   <div class="col-md-6" style="margin-top: 10px;">
      <a onclick="closefilter()" class="fl times_a"><i class="fas fa-times"></i></a> 
      <label class="fl filter_label_sidenav" > 
      Filters
      </label>
   </div>
   <div class="col-md-6" style="margin-top: 10px;">
    <form method="post">
          <button type="button" name="btnFilter" onclick="sendfilterdata()" class="fr btn-save">Save</button>
          <input type="hidden" name="AddNewBtnClick">
          <input type="hidden" name="ViewListForFD">
          <input type="hidden" name="ForCompany">
          <input type="hidden" name="MainGroup">
          <input type="hidden" name="SubGroup">
          <input type="hidden" name="ForRefUSR">
          <input type="hidden" name="ForTaskTag">
          <input type="hidden" name="CountCells" value="5">
    </form>
     
   </div>
</div>
<div class="contanier_row">
    <div class="col-md-4 field_filter">
      <div class="reset_view_div" >
         <a href="javascript:void()" onclick="resetbox()" class="resetview">Reset all</a>
      </div>
      <ul class="field_list">
         <li>
            <button class="li_button active" type="button" id="com_li" onclick="changecontentpart('com_li')">
               <span class="fl">Company <span id="select_com_li"></span></span>
               <div class="active_div li_button_div" id="com_li_round"></div>
            </button>
         </li>
         <li>
            <button class="li_button" type="button" id="main_li" onclick="changecontentpart('main_li')">
               <span class="fl">Main Task Groups <span id="select_main_li"></span></span> 
               <div class="li_button_div" id="main_li_round"></div>
            </button>
         </li>
         <li>
            <button class="li_button" type="button" id="sub_li" onclick="changecontentpart('sub_li')">
               <span class="fl">Sub Task Groups<span id="select_sub_li"></span></span> 
               <div class="li_button_div" id="sub_li_round"></div>
            </button>
         </li>
         <li>
            <button class="li_button" type="button" id="assign_li" onclick="changecontentpart('assign_li')">
               <span class="fl">Assigned to<span id="select_assign_li"></span></span> 
               <div class="li_button_div" id="assign_li_round"></div>
            </button>
         </li>
         <li>
            <button class="li_button" type="button" id="tag_li" onclick="changecontentpart('tag_li')">
               <span class="fl">Tags<span id="select_tag_li"></span></span> 
               <div class="li_button_div" id="tag_li_round"></div>
            </button>
         </li>
         <li>
         <button class="li_button" type="button" id="view_li" onclick="changecontentpart('view_li')"><span class="fl">View Completed<span id="select_view_li"></span></span><div class="li_button_div" id="view_li_round"></button>
         </li>
      </ul>
    </div>
    <div class="col-md-9 fieks_content">
      <div id="com_li_div" class="div_1" >
      <div class="reset_view_div" >
      <a href="javascript:void()" onclick="selectallbox('companybox','Company','com_li')" class="resetview">Select all</a>
      </div>
      
      <?php  $i=0; 
         while($i<$maxcompanycode)
         {   
             $valueof= $AllCompanyCode_arr[$i][0] ;
             
             if ($ForCompany == $valueof) {  ?>
      <div class="checkbox_div">
      <input type="checkbox" class="companybox" id="company-<?=$i?>" name="Company[]" value="<?php echo $valueof; ?>" onchange="countchecked('Company','com_li')" checked>
      <label for="company-<?=$i?>"> <?php echo $AllCompanyCode_arr[$i][2] ?></label>
      </div>
      <?php } else{ ?>    
      <div class="checkbox_div">
      <input type="checkbox" class="companybox" id="company-<?=$i?>" name="Company[]" value="<?php echo $valueof; ?>" onchange="countchecked('Company','com_li')" >
      <label for="company-<?=$i?>"> <?php echo $AllCompanyCode_arr[$i][2] ?></label>
      </div>
      <?php  }  $i++; } ?>
      </div>
      <div id="main_li_div" class="hide div_1">
      <div class="reset_view_div" >
      <a href="javascript:void()" onclick="selectallbox('mainbox','Main','main_li')" class="resetview">Select all</a>
      </div>
      <div>
      <?php  
         $maxtaskmaingrouptitle = sizeof($AllTaskMainGroups_arr);
         $i=0; 
         while($i<$maxtaskmaingrouptitle)
         {   
             $valueof= $AllTaskMainGroups_arr[$i][0] ;
             if ($MainGroup == $valueof) {  ?>
      <div class="checkbox_div">
      <input type="checkbox" class="mainbox" onchange="countchecked('Main','main_li')" id="maintask-<?=$i?>" name="Main[]" value="<?php echo $valueof; ?>" checked>
      <label for="maintask-<?=$i?>"> <?php echo $AllTaskMainGroups_arr[$i][1] ?></label>
      </div>
      <?php } else{ ?>    
      <div class="checkbox_div">
      <input type="checkbox" class="mainbox" id="maintask-<?=$i?>" onchange="countchecked('Main','main_li')" name="Main[]" value="<?php echo $valueof; ?>">
      <label for="maintask-<?=$i?>"> <?php echo $AllTaskMainGroups_arr[$i][1] ?></label>
      </div>
      <?php  }  $i++; } ?>
      </div>
      </div>
      <div id="sub_li_div" class="hide div_1">
      <div class="reset_view_div" >
      <a href="javascript:void()" onclick="selectallbox('subbox','Sub','sub_li')" class="resetview">Select all</a>
      </div>
      <div>
      <?php  
         $SubGroupsOfMain_arr=getSubGroupOfMain($MainGroup);
         $maxtasksubgrouptitle = sizeof($SubGroupsOfMain_arr);
         $i=0; 
         while($i<$maxtasksubgrouptitle)
         {   
             $valueof= $SubGroupsOfMain_arr[$i][0] ;
             if ($SubGroup == $valueof) {  ?>
      <div class="checkbox_div">
      <input type="checkbox" class="subbox" onchange="countchecked('Sub','sub_li')" id="subtask-<?=$i?>" name="Sub[]" value="<?php echo $valueof; ?>" checked>
      <label for="subtask-<?=$i?>"> <?php echo $SubGroupsOfMain_arr[$i][1] ?></label>
      </div>
      <?php } else{ ?>    
      <div class="checkbox_div">
      <input type="checkbox" class="subbox" id="subtask-<?=$i?>" onchange="countchecked('Sub','sub_li')" name="Sub[]" value="<?php echo $valueof; ?>">
      <label for="subtask-<?=$i?>"> <?php echo $SubGroupsOfMain_arr[$i][1] ?></label>
      </div>
      <?php  }  $i++; } ?>
      </div>
      </div>
      <div id="assign_li_div" class="hide div_1">
      <div class="reset_view_div" >
      <a href="javascript:void()" onclick="selectallbox('assignbox','ForUSR','assign_li')" class="resetview">Select all</a>
      </div>
      <div>
        <?php   $i=0;while($i<$maxusercodename)
      {   
          $valueof= $UserCodeName_arr[$i][0] ;
          
          if ($ForRefUSR == $valueof) {  ?>
            <div class="checkbox_div">
      <input type="checkbox" class="assignbox" id="assignto-<?=$i?>" onchange="countchecked('assignbox','assign_li')" name="ForUSR[]" value="<?php echo $valueof; ?>" checked>
      <label for="assignto-<?=$i?>"> <?php echo $UserCodeName_arr[$i][1] ?></label>
      </div>
     
      <?php } else{ ?>    
      <div class="checkbox_div">
      <input type="checkbox" class="assignbox" id="assignto-<?=$i?>" name="ForUSR[]" onchange="countchecked('assignbox','assign_li')" value="<?php echo $valueof; ?>">
      <label for="assignto-<?=$i?>"> <?php echo $UserCodeName_arr[$i][1] ?></label>
      </div>
      <?php  }  $i++; } ?>
      </div>
      </div>
      <div id="tag_li_div" class="hide div_1">
      <div class="reset_view_div" >
      <a href="javascript:void()" onclick="selectallbox('tagbox','Tag','tag_li')" class="resetview">Reset all</a>
      </div>
      <div>
       <?php  $i=0; 
      while($i<$maxtasktags)
      {   
          $valueof= $AllTasksTagList_arr[$i][0] ;
          
          if ($ForTaskTag == $valueof) {  ?>
            <div class="checkbox_div">
              <input type="checkbox" class="tagbox" id="tags-<?=$i?>" name="Tag[]" onchange="countchecked('Tag','tag_li')" value="<?php echo $valueof; ?>" checked>
              <label for="tags-<?=$i?>"> <?php echo $AllTasksTagList_arr[$i][0] ?></label>
            </div>
      
      <?php } else{ ?>    
      <div class="checkbox_div">
              <input type="checkbox" class="tagbox" id="tags-<?=$i?>" name="Tag[]" onchange="countchecked('Tag','tag_li')" value="<?php echo $valueof; ?>">
              <label for="tags-<?=$i?>"> <?php echo $AllTasksTagList_arr[$i][0] ?></label>
            </div>
      <?php  }  $i++; } ?>
      </div>
      </div>
      <div id="view_li_div" class="hide div_1">
      <div class="reset_view_div" >
      <a href="javascript:void()" onclick="selectallbox('completebox','ViewCompleted','view_li')" class="resetview">Reset all</a>
      </div>
      <div>
            <div class="checkbox_div">
              <input type="checkbox" class="subbox" id="ViewCompleted" name="ViewCompleted[]"  onchange="countchecked('ViewCompleted','view_li')" value="YES">
              <label for="ViewCompleted">&nbsp;&nbsp;View Completed &nbsp;</label>
          </div>
      </div>
      </div>
      </div>
      </div>
   </div>
</div> 
<script type="text/javascript">
   function changecontentpart(val){
       $(".li_button").removeClass("active");
       $(".li_button_div").removeClass("active_div");
       $("#"+val+"_round").addClass("active_div");
       
       $("#"+val).addClass("active");
       $(".div_1").removeClass("show");
       $(".div_1").removeClass("hide");
       $(".div_1").addClass("hide");
       $("#"+val+"_div").removeClass("hide");
       $("#"+val+"_div").addClass("show");
   
   }
   
   function selectallbox(val,selectname,applyename){
         $("."+val).prop('checked',true);
         if($('[name="'+selectname+'[]"]:checked').length!=0){
            $("#select_"+applyename).html("("+$('[name="'+selectname+'[]"]:checked').length+")");
         }
         if($('[name="'+selectname+'[]"]:checked').length==0){
            $("#select_"+applyename).html("");
         }
         

   }

   function countchecked(selectname,applyename){
       if($('[name="'+selectname+'[]"]:checked').length!=0){
            $("#select_"+applyename).html("("+$('[name="'+selectname+'[]"]:checked').length+")");
         }
         if($('[name="'+selectname+'[]"]:checked').length==0){
            $("#select_"+applyename).html("");
         }
   }
   
   function resetbox(){
       $(".li_button").removeClass("active");
       $(".li_button_div").removeClass("active_div");
       $("#com_li_round").addClass("active_div");      
       $("#com_li").addClass("active");
       $(".div_1").removeClass("show");
       $(".div_1").removeClass("hide");
       $(".div_1").addClass("hide");
       $("#com_li_div").removeClass("hide");
       $("#com_li_div").addClass("show");
       $(".companybox").prop('checked',false);
       $(".subbox").prop('checked',false);
       $(".mainbox").prop('checked',false);
       $(".mainbox").prop('checked',false);
       $(".assignbox").prop('checked',false);
       $(".completebox").prop('checked',false);
   }

   function sendfilterdata(){

   }


    function submitquickbox() {
      var  x = $("#company_quickbox").val();
      var  y = $("#task_name").val();
      var z =$("#description").val();
      var msg = 0;
      if (x == "") {
        alert("Please Enter Company to create the task");
        document.getElementById("company_quickbox").style.borderColor = "red";    
        msg = 1;    
      }
      if (y == "") {
        alert("Please Enter Task Name");
        document.getElementById("task_name").style.borderColor = "red"; 
        msg = 1;       
      }
      
       if(msg==0){
            $.ajax({
                method:"post",
                data : { company:x,task:y,descr:z},
                success: function( data ) {
                    alert("task add successfully");
                    window.location.reload();
                }
            });
       }
    }

</script>
<!-- wrapper -->