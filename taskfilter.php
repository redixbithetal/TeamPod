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

   <div class=sidenav1 id=sidenav1>
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
   </div>
</div>
<!-- wrapper -->