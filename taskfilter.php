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
   width: 61%;
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
   .filter_label{
   margin-left: 15px;
   font-size: 22px;
   font-weight: 700;
   }
   .times_a{
   margin-top: 5px;
   font-size: 22px;
   font-weight: 700;
   margin-left: 15px;
   }
   .btn-save{
   color: white;
   background: #e74c3c;
   border: 1px solid #e74c3c;
   padding: 10px 30px;
   border-radius: 35%;
   font-size: 13px;
   letter-spacing: 1px;
   line-height: 15px;
   border-radius: 40px;
   transition: all 0.3s ease 0s;
   }
   .p-3 {
   padding: 14px 10px 47px 12px;
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
   width: 30%;
   float: left;
   height: 100vh;
   border-right: 2px solid gray;
   }
   .fieks_content{
   width: 70%;
   float: right;
   background: #f2f2f2;
   height: 100vh;
       overflow: auto;
   }
   .field_list{
   list-style: none;
   width: 100%;
   margin-top: 40px;
   padding-left: 0px;
   }
   .contanier_row{
   height: 100vh;
   }
   .resetview{
   color: #2e57a2;
   float: left;
   margin-left: 15px;
   font-weight: 500;
   margin-bottom: 25px;
   }
   .reset_view_div{
   font-size: 15px;
   float: left;
   width: 100%;
   margin-top: 32px;
   margin-left: 25px;
   }
   ul.field_list li {
   border-width: 1px;
   margin: 0px 60px;
   font-weight: 600;
   }
   .li_button{
   background: none;
   border: none;
   font-size: 18px;
   border-width: 1px;
   font-weight: 600;
   border-bottom: 2px solid #ccc;
   width: 100%;
   padding: 18px;
   }
   .show{
   display: block;
   }
   .hide{
   display: none;
   }
   .active{        
   background: #eeeeee;
   border: 1px solid green;
   border-radius: 40px;
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
   width: 1em;
   height: 1em;
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
</style>
<div class="maindiv" id="wrapper" style="margin:15px 0 0 16%;width:84%">
<div>
   <input type="text" id="myInput" onkeyup="fnTypeFilterRow()" style="background-color: #fff;float:left;width:40%;border-radius:25px" placeholder="Search by Task or Maingroup or Subgroup " title="Type in task name" class="form-control  total_fields" />
</div>
<div style="width:38%;float:left;">&nbsp; </div>
<div >
   <input type=button id="myQuickTask" class='btn btn-default' title="Add New Task" value="Quick Task" style="font-family:'Open Sans';width:150px;height:32px;border-radius:25px;margin:0px 16px 0px 0px;" onclick="popup('popUpDiv','addtask','')"/>
   <img style="border:none;background:#eee;float:right;cursor: pointer;margin:0px 40px 0px 0px;" alt="" src="images/Filters.svg" onclick="openfilter()" />
</div>
<br clear='all'/>
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
<div class="row shadow p-3 bg-white rounded">
   <div class="col-md-6">
      <a onclick="closefilter()" class="fl times_a"><i class="fas fa-times"></i></a> 
      <label class="fl filter_label" > 
      Filters
      </label>
   </div>
   <div class="col-md-6">
      <button type="submit" name="btnFilter" class="fr btn-save">Save</button>
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
               <span class="fl">Company(4)</span>
               <div class="active_div li_button_div" id="com_li_round"></div>
            </button>
         </li>
         <li>
            <button class="li_button" type="button" id="main_li" onclick="changecontentpart('main_li')">
               <span class="fl">Main Task Groups</span> 
               <div class="li_button_div" id="main_li_round"></div>
            </button>
         </li>
         <li>
            <button class="li_button" type="button" id="sub_li" onclick="changecontentpart('sub_li')">
               <span class="fl">Sub Task Groups</span> 
               <div class="li_button_div" id="sub_li_round"></div>
            </button>
         </li>
         <li>
            <button class="li_button" type="button" id="assign_li" onclick="changecontentpart('assign_li')">
               <span class="fl">Assigned to</span> 
               <div class="li_button_div" id="assign_li_round"></div>
            </button>
         </li>
         <li>
            <button class="li_button" type="button" id="tag_li" onclick="changecontentpart('tag_li')">
               <span class="fl">Tags</span> 
               <div>
                  </div class="li_button_div" id="tag_li_round">
            </button>
         </li>
         <li>
         <button class="li_button" type="button" id="view_li" onclick="changecontentpart('view_li')"><span class="fl">View Completed</span><div class="li_button_div" id="view_li_round"></button>
         </li>
      </ul>
      </div>
      <div class="col-md-9 fieks_content">
      <div id="com_li_div" class="div_1" >
      <div class="reset_view_div" >
      <a href="javascript:void()" onclick="selectallbox('companybox')" class="resetview">Select all</a>
      </div>
      <?php  $i=0; 
         while($i<$maxcompanycode)
         {   
             $valueof= $AllCompanyCode_arr[$i][0] ;
             
             if ($ForCompany == $valueof) {  ?>
      <div class="checkbox_div">
      <input type="checkbox" class="companybox" id="company-<?=$i?>" name="ForCompany[]" value="<?php echo $valueof; ?>" checked>
      <label for="company-<?=$i?>"> <?php echo $AllCompanyCode_arr[$i][2] ?></label>
      </div>
      <?php } else{ ?>    
      <div class="checkbox_div">
      <input type="checkbox" class="companybox" id="company-<?=$i?>" name="ForCompany[]" value="<?php echo $valueof; ?>" >
      <label for="company-<?=$i?>"> <?php echo $AllCompanyCode_arr[$i][2] ?></label>
      </div>
      <?php  }  $i++; } ?>
      </div>
      <div id="main_li_div" class="hide div_1">
      <div class="reset_view_div" >
      <a href="javascript:void()" onclick="selectallbox('mainbox')" class="resetview">Select all</a>
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
      <input type="checkbox" class="mainbox" id="maintask-<?=$i?>" name="MainGroup[]" value="<?php echo $valueof; ?>" checked>
      <label for="maintask-<?=$i?>"> <?php echo $AllTaskMainGroups_arr[$i][1] ?></label>
      </div>
      <?php } else{ ?>    
      <div class="checkbox_div">
      <input type="checkbox" class="mainbox" id="maintask-<?=$i?>" name="MainGroup[]" value="<?php echo $valueof; ?>">
      <label for="maintask-<?=$i?>"> <?php echo $AllTaskMainGroups_arr[$i][1] ?></label>
      </div>
      <?php  }  $i++; } ?>
      </div>
      </div>
      <div id="sub_li_div" class="hide div_1">
      <div class="reset_view_div" >
      <a href="javascript:void()" onclick="selectallbox('subbox')" class="resetview">Select all</a>
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
      <input type="checkbox" class="subbox" id="subtask-<?=$i?>" name="SubGroup[]" value="<?php echo $valueof; ?>" checked>
      <label for="subtask-<?=$i?>"> <?php echo $SubGroupsOfMain_arr[$i][1] ?></label>
      </div>
      <?php } else{ ?>    
      <div class="checkbox_div">
      <input type="checkbox" class="subbox" id="subtask-<?=$i?>" name="SubGroup[]" value="<?php echo $valueof; ?>">
      <label for="subtask-<?=$i?>"> <?php echo $SubGroupsOfMain_arr[$i][1] ?></label>
      </div>
      <?php  }  $i++; } ?>
      </div>
      </div>
      <div id="assign_li_div" class="hide div_1">
      <div class="reset_view_div" >
      <a href="javascript:void()" onclick="selectallbox('assignbox')" class="resetview">Select all</a>
      </div>
      <div>
        <?php   while($i<$maxusercodename)
      {   
          $valueof= $UserCodeName_arr[$i][0] ;
          
          if ($ForRefUSR == $valueof) {  ?>
            <div class="checkbox_div">
      <input type="checkbox" class="assignbox" id="assignto-<?=$i?>" name="ForRefUSR[]" value="<?php echo $valueof; ?>" checked>
      <label for="assignto-<?=$i?>"> <?php echo $UserCodeName_arr[$i][1] ?></label>
      </div>
     
      <?php } else{ ?>    
      <div class="checkbox_div">
      <input type="checkbox" class="assignbox" id="assignto-<?=$i?>" name="ForRefUSR[]" value="<?php echo $valueof; ?>">
      <label for="assignto-<?=$i?>"> <?php echo $UserCodeName_arr[$i][1] ?></label>
      </div>
      <?php  }  $i++; } ?>
      </div>
      </div>
      <div id="tag_li_div" class="hide div_1">
      <div class="reset_view_div" >
      <a href="javascript:void()" onclick="selectallbox('tagbox')" class="resetview">Reset all</a>
      </div>
      <div>
       <?php  $i=0; 
      while($i<$maxtasktags)
      {   
          $valueof= $AllTasksTagList_arr[$i][0] ;
          
          if ($ForTaskTag == $valueof) {  ?>
            <div class="checkbox_div">
              <input type="checkbox" class="tagbox" id="tags-<?=$i?>" name="ForTaskTag[]" value="<?php echo $valueof; ?>" checked>
              <label for="tags-<?=$i?>"> <?php echo $AllTasksTagList_arr[$i][0] ?></label>
            </div>
      
      <?php } else{ ?>    
      <div class="checkbox_div">
              <input type="checkbox" class="tagbox" id="tags-<?=$i?>" name="ForTaskTag[]" value="<?php echo $valueof; ?>">
              <label for="tags-<?=$i?>"> <?php echo $AllTasksTagList_arr[$i][0] ?></label>
            </div>
      <?php  }  $i++; } ?>
      </div>
      </div>
      <div id="view_li_div" class="hide div_1">
      <div class="reset_view_div" >
      <a href="javascript:void()" onclick="selectallbox('completebox')" class="resetview">Reset all</a>
      </div>
      <div>
            <div class="checkbox_div">
              <input type="checkbox" class="subbox" id="chkViewCompleted" name="chkViewCompleted" value="YES">
              <label for="chkViewCompleted">&nbsp;&nbsp;View Completed &nbsp;</label>
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
   
   function selectallbox(val){
         $("."+val).prop('checked',true);
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
</script>
<!-- wrapper -->