<?php               if ($Priority =="P1") {$taskcolor="#FA8654";}
                    if ($Priority =="P2") {$taskcolor="#FACA54";}
                    if ($Priority =="P3") {$taskcolor="#FAF054";}
                    $UserCodeName_arr = array(); 

                    $query11="SELECT t1.RefUSR, t1.FirstName, t1.LastName FROM `tUser` AS t1, `tUserAccessLevels` AS t2  
                            WHERE t1.RefUSR=t2.RefUSR AND t1.Status='ACT' AND t2.FCompany='$CoCode' 
                            GROUP BY t1.RefUSR ORDER BY t1.FirstName, t1.LastName "; 
                            //echo '<br>-----'.$query11;
                    $sql11 = mysqli_query($mysqli, $query11);
                    $i=0;
                    while($row11=mysqli_fetch_array($sql11))						//------------------- Store Practice ID & Full Name from database to AllPractice_arr ------
                    {
                        $UserCodeName_arr[$i][0]=$row11['RefUSR'];
                        $UserCodeName_arr[$i][1]=$row11['FirstName'].' '.$row11['LastName'];
                        // echo '<br><br> Yes '.$UserCodeName_arr[$i][0].'--'.$UserCodeName_arr[$i][1];
                        $i++;
                    }
                    
                    $query21 = "SELECT * FROM `tTaskNotes` t1 WHERE `TRecRef` ='$TRecRef' AND `Stage`='STARTTIME' AND cRecRef='$cRecRef' AND not EXISTS (
                        SELECT * FROM `tTaskNotes` t2 WHERE `TRecRef` ='$TRecRef' AND `Stage`='ENDTIME' AND cRecRef='$cRecRef' AND `NotesDT` > t1.`NotesDT`)";
                    $sql21 = mysqli_query($mysqli, $query21);
                    $row21=mysqli_fetch_array($sql21);
                    $NRecRef=$row21['NRecRef'];
                    $startdisplay="inline";$clockdisplay="none"; $enddisplay="none";
                    $taskid='';
                    if ($NRecRef!=""){ $startdisplay="none";$clockdisplay="inline";$enddisplay="inline";$taskid=$TRecRef; }
                    
                    $outall.= "<input type=hidden id=EditCalendarRef".$celnodv." name=EditCalendarRef".$celnodv." value=".$cRecRef." > ";
                    $outall.= "<input type=hidden id=EditScheduleRef".$celnodv." name=EditScheduleRef".$celnodv." value=".$sRecRef." > ";
                
                    $outall.= "<div class='WHITbkgLghtBLUEborder taskboard'  style='width:99%; border-left-width: 10px; margin-top:10px;border-left-color: ".$statusBARcolor." ;' 
                               id=dv-".$celnodv." >";
                    if ($cStage!="Completed") {
                    $outall.= "<span style='background:#fff;color:blue;padding:3px 5px;float:right;margin-top:-15px;border-radius:3px;'><a style='color:blue' href='#' onclick=popup('popUpDiv','addsubtask','$cRecRef')><b> + Sub Task </b></a></span>";           
                    }
                    $outall.= "<span style='background:$statusBARcolor;color:#fff;padding:5px 7px;float:left;margin-top:-15px;border-radius:3px;display:none'><b> Task#$TRecRef - $Priority </b></span><table class='myTable' cellpadding=4 cellspacing=0 width=100% border=0>";
                    
                    $outall.= "
                          <tr $newassigncolor id=".$celnodv.">
                          <td style='width:15%' align=left><a href='#' onclick=popup('popUpDiv','tasknotes','$celnodv')><span style='background:$taskcolor;color:#000;border-radius:50%;padding:5px;border:1px solid #666'>$TRecRef</span>&nbsp;&nbsp;$CoShortCode</a></td>
                          <td style='width:60%;text-align:left;'>";
                    if ($RepeatSchedule1!="") {
                        $outall.="<img src='../focinc/images/iconcircle.png' width='14px' title='Repeated Task - $RepeatSchedule1'/> &nbsp;";
                    }
                    $outall.="<b>$TaskTitle</b>";
                    $outall.= "<div id='groupdetails".$celnodv."' style='display:inline;line-height:1;font-size:12px'>";
                    if ($TaskMainGroupTitle!="") {
                    $outall.= "($TaskMainGroupTitle - $TaskSubGroupTitle)";
                    }
                    $outall.= "</div>";
                    $outall.= "</td><td style='width:24%;line-height:1.2' align=right>$showdate</td>";
                          if ($PrivateTask==1) { $outall.= "<td width=20px><img src='../focinc/images/icon-private.png' height=20 /></td>"; }

                    $outall.= "</tr>";
                    $margin="0px";
                    if ($TaskDescr!=""){ 
                    $margin="-25px";
                    $outall.= "<tr><td></td>
                          <td align='left' style='line-height:1.2'><i>&nbsp;&nbsp;$TaskDescr</i></td>
                          <td></td>
                          <td></td></tr>";
                    }  
                    $outall.= "<tr><td style='width:15%' align=left><div id='tasktags".$celnodv."' style='line-height:1;margin-top:$margin'>$ThisTaskTags</div></td>
                          <td style='width:60%;line-height:1.2;font-size:14px;margin-top:5px;'  align=left>$initials</td>
                          <td class='tableicons' width=270px align=right>";
                          if ($cStage!="Completed") {
                            $outall.="<span id=clockstarticon".$celnodv." style='display:$clockdisplay'><img src='../focinc/images/iconclockgif.gif' height=20 title='Clock Started' /></span>
                                    <a onclick=showdiv('divstarttime','".$celnodv."')    id=starttimeicon".$celnodv." style='display:$startdisplay'>&nbsp;<img src='../focinc/images/iconstarttime.png' height=20 title='Start Time' /></a>
                                    <a onclick=showdiv('divendtime','".$celnodv."')      id=endtimeicon".$celnodv." style='display:$enddisplay'>&nbsp;<img src='../focinc/images/iconendtime.png' height=20 title='End Time' /></a>
                                    <a onclick=showdiv('diveditgroup','".$celnodv."')>&nbsp;<img src='../focinc/images/iconregroup.png' height=20 title='Re-Group' /></a>
                                    <a onclick=showdiv('divedittags','".$celnodv."')>&nbsp;<img src='../focinc/images/icontag.png' height=20 title='Add Tag' /></a>
                                    <a onclick=showdiv('divaddattach','".$celnodv."')>&nbsp;<img src='../focinc/images/iconattach.png' height=20 title='Add Attachment' /></a>
                                    <a onclick=showdiv('divnewnote','".$celnodv."')>&nbsp;<img src='../focinc/images/iconBedit.png' height=20 title='Add Comments' /></a>";
                                    if ($id == $taskowner) {
                                    $outall.= " <a onclick=showdiv('divnewdate','".$celnodv."')>&nbsp;<img src='../focinc/images/iconBcalander.png' height=20 title='Re-schedule Date' /></a>";
                                    }
                                    $outall.= "<a onclick=showdiv('divnewuser','".$celnodv."')>&nbsp;<img src='../focinc/images/iconBstaff.png' height=20 title='Reassign' /></a>
                                    <a onclick=showdiv('divcomplete','".$celnodv."')>&nbsp;<img src='../focinc/images/imgcheck.png' height=20 title='Mark Complete' /></a> </td>";
                          }
                    $outall.= "</tr>";
                    $outall.= "</table>";
                    if ($cStage!="Completed") {
                    $outall.= "
                                <div id=divstarttime-".$celnodv." style='display: none;margin-top:20px' >
                                    <input type='hidden' class=noteid id=notid".$celnodv." value='$NRecRef'>
                                    <input type='hidden' class=ntaskid id=ntaskid".$celnodv." value='$taskid'>
                                    <div style='margin-top:10px;float:left'>Start Notes:</div> <textarea class='total_fields NewCompNote' id=StartNote".$celnodv." name=StartNote".$celnodv." rows=4 style='width: 80%;margin-top:10px;height:50px' ></textarea>
                                    <div style='margin:30px 8% 0 0;float:right' class='btnSaveNewNote'><input type=button onClick='addstartnote($celnodv)' name=btnSaveStartNote".$celnodv." value='_' title='Save' style='background: url(../focinc/images/iconsave.png) no-repeat;;background-size: 20px 20px;  border: none; width: 20px; height: 20px;' /></div>
                                </div>
                                
                                <div id=divendtime-".$celnodv." style='display: none;margin-top:20px' >
                                    <div style='margin-top:10px;float:left'>End Notes:*</div> <textarea class='total_fields NewCompNote' id=EndNote".$celnodv." name=EndNote".$celnodv." rows=4 style='width: 80%;margin-top:10px;height:50px' ></textarea>
                                    <div style='margin:30px 8% 0 0;float:right' class='btnSaveNewNote'><input type=button onClick='addendnote($celnodv)' name=btnSaveEndNote".$celnodv." value='_' title='Save' style='background: url(../focinc/images/iconsave.png) no-repeat;;background-size: 20px 20px;  border: none; width: 20px; height: 20px;' /></div>
                                </div>
                                
                                <div id=diveditgroup-".$celnodv." style='display: none;margin-top:20px' >
                                    <div style='margin-top:10px;float:left'>Main Group: <input type=hidden id='MainGroup".$celnodv."' value='$eMainGroup' >
                                    <select class='form-control total_fields' name='eMainGroup".$celnodv."' id='eMainGroup".$celnodv."' style='width:250px' onchange='loadsubgrp1($celnodv)'>
                                    <option value=''>----- Select -----</option>";
                                        $maxtaskmaingrouptitle = sizeof($AllTaskMainGroups_arr);
                                        $i=0; 
                                        while($i<$maxtaskmaingrouptitle)
                                        {   
                                            $valueof= $AllTaskMainGroups_arr[$i][0] ;
                                            if ($eMainGroup == $valueof) {  
                                                $outall.= "<option value='$valueof' selected>".$AllTaskMainGroups_arr[$i][1]."</option>";
                                             } else{     
                                              $outall.= "<option value='$valueof'>".$AllTaskMainGroups_arr[$i][1]."</option>";
                                         }  $i++; } 
                                $outall.= "</select></div><br clear='all'/>
                                <div style='margin-top:10px;float:left'>Sub Group: &nbsp; <input type=hidden id='SubGroup".$celnodv."' value='$eSubGroup'>
                                    <select class='form-control total_fields' name='eSubGroup".$celnodv."' id='eSubGroup".$celnodv."' style='width:250px'>";
                                        if ($eMainGroup!="0") {
                                        $SubGroupsOfMain_arr=getSubGroupOfMain($eMainGroup);
                                        if ($SubGroupsOfMain_arr!="") {
                                        $maxtasksubgrouptitle = sizeof($SubGroupsOfMain_arr);
                                        } else {
                                        $maxtasksubgrouptitle=0;
                                        }
                                        $i=0; 
                                        while($i<$maxtasksubgrouptitle)
                                        {   
                                            $valueof= $SubGroupsOfMain_arr[$i][0] ;
                                            if ($eSubGroup == $valueof) {  
                                            $outall.= "<option value='$valueof' selected>".$SubGroupsOfMain_arr[$i][1]." </option>";
                                            } else{     
                                            $outall.= "<option value='$valueof'>".$SubGroupsOfMain_arr[$i][1]."</option>";
                                        }  $i++; } 
                                        }else {
                                            $outall.= "<option value=''>--Select Maingroup--</option>";
                                        }
                                $outall.= "</select></div>
                                    <div style='margin:20px 0 0 10px;float:left' class='btnSaveNewNote'><input type=button onClick='regroup($celnodv)' name=btnSaveGroup".$celnodv." value='_' title='Save' style='background: url(../focinc/images/iconsave.png) no-repeat;;background-size: 20px 20px;  border: none; width: 20px; height: 20px;' /></div>
                                </div>
                                
                                <div id=divedittags-".$celnodv." style='display: none;margin-top:20px' >
                                    <div style='margin-top:10px;float:left'>Tag: &nbsp;&nbsp;
                                    <input type=text class='form-control total_fields sTaskTag' name='sTaskTag".$celnodv."' id='sTaskTag".$celnodv."' style='width:200px' value='$sTaskTag' />
                                    </div>
                                    <div style='margin:20px 0 0 10px;float:left' class='btnSaveNewNote'><input type=button onClick='addtag($celnodv)' name=btnAddTag".$celnodv." value='_' title='Save' style='background: url(../focinc/images/iconsave.png) no-repeat;;background-size: 20px 20px;  border: none; width: 20px; height: 20px;' /></div>
                                </div>
                                
                                <div id=divaddattach-".$celnodv." style='display: none;margin-top:20px' >
                                    <form>
                                    <div style='margin-top:10px;float:left;text-align:left'>
                                    <label>Supporting Document Notes: </label><br clear='all' /><br clear='all' />
                                    <textarea  class='form-control total_fields' name='sDocNote".$celnodv."' id='sDocNote".$celnodv."' style='width:270px;align:left;' row=4 value=''></textarea><br clear='all' /><br clear='all' />
                                    <label>Upload: </label><br clear='all' /><br clear='all' />
                                    <input id='AttachDoc".$celnodv."' name='AttachDoc".$celnodv."' style='float:left;' type='file' class='btn btn-default total_fields' />
                                    </div>
                                    <div style='margin:110px 0 0 10px;float:left' class='btnSaveNewNote'><input type=button onclick=addfile(".$celnodv.") name=btnUploadFile".$celnodv." value='_' title='Save' style='background: url(../focinc/images/iconsave.png) no-repeat;;background-size: 20px 20px;  border: none; width: 20px; height: 20px;' /></div><br clear='all' /><br clear='all' />
                                    </form>
                                </div>
                                
                                <div id=divnewnote-".$celnodv." style='display: none;margin-top:20px' >
                                <div style='width:100px;float:left;line-height:1.2;text-align:left'>Time Taken: &nbsp;&nbsp;</div>
                                <input type=text class='total_fields'  name='noteupTimeTaken".$celnodv."' id='noteupTimeTaken".$celnodv."'  style='width:70px;text-align:right;float:left' placeholder='HH:MM' />
                                <br clear='all'/>
                                <br clear='all'/>
                                    <div style='width:100px;margin-top:10px;float:left;text-align:left'>Notes:</div> <textarea class='total_fields' id=NewNote".$celnodv." name=NewNote".$celnodv." rows=4 style='width: 250px;margin-top:10px;height:50px;float:left' ></textarea>
                                    <div style='margin:30px 0 0 20px;float:left' class='btnSaveNewNote'><input type=button onclick=newnote(".$celnodv.") name=btnSaveNewNote".$celnodv." value='_' title='Save' style='background: url(../focinc/images/iconsave.png) no-repeat;;background-size: 20px 20px;  border: none; width: 20px; height: 20px;' /></div>
                                <br clear='all'/>
                                <br clear='all'/>
                                </div>

                                <div id=divcomplete-".$celnodv." style='display: none;margin-top:20px' >
                                <div style='width:15%;float:left;line-height:1.2;text-align:left'>Time Taken:* &nbsp;&nbsp;</div>
                                <input type=text class='total_fields'  name='upTimeTaken".$celnodv."' id='upTimeTaken".$celnodv."'  style='width:70px;text-align:right;float:left' placeholder='HH:MM' />
                                <br clear='all'/>
                                <br clear='all'/>
                                <div style='width:15%;float:left;line-height:1.2;text-align:left'>Completion Note:*</div>
                                     <textarea id=NewCompNote".$celnodv." name=NewCompNote".$celnodv." style='width:80%;float:left;height:50px' rows=4 class='total_fields NewCompNote'></textarea>
                                <div style='margin:20px 1% 0 0;float:right'><input type=button onclick=completetask(".$celnodv.")  value='_' title='Save' style='background: url(../focinc/images/iconsave.png);background-size: 20px 20px;  border: none; width: 20px; height: 20px;' /></div>
                                </div>
                                
                                <div id=divnewdate-".$celnodv." style='display: none;margin-top:20px;'>
                                    <input type=button name='btnReSchCurrD1' id='btnReSchCurrD1' onclick=showschedule('".$celnodv."','divreschdulecurr') value='Re-Schedule Current Date' class='btn btn-default' style='margin-top:5px;width:225px' />
                                    <input type=button name='btnRemovCurrD1' id='btnRemovCurrD1' onclick=showschedule('".$celnodv."','removecurrent')    value='Remove Current Date' class='btn btn-default' style='margin-top:5px;width:225px' /><br/>";
                                if ($RepeatSchedule1!='') { 
                        $outall.= " <input type=button name='btnReSchFullS1' id='btnReSchFullS1'  onclick=showschedule('".$celnodv."','divreschdulefuture') value='Re-Schedule All Future Series' class='btn btn-default' style='margin-top:5px;width:225px'  />
                                    <input type=button name='btnRemovFullS1' id='btnRemovFullS1'  onclick=showschedule('".$celnodv."','removefuture')    value='Remove All Future Series' class='btn btn-default' style='margin-top:5px;width:225px' /><br/>";
                                 }
                               $outall.= "<div id=divreschdulecurr-".$celnodv." style='display: none;margin-top:20px;'>Start Date: <input type=text style='width:100px' id=NewStartDate".$celnodv." name=NewStartDate".$celnodv." class='datepicker total_fields' value=".$cScheduleDateUK.">
                                            <br><br> Due Date: &nbsp; <input type=text style='width:100px' id=NewDueDate".$celnodv." name=NewDueDate".$celnodv." class='datepicker total_fields' value=".$cDueDateUK.">
                                    <input type=button onclick=reschedulecurrent(".$celnodv.") name=btnSaveReSchedule".$celnodv." value='_' title='Save' style='background: url(../focinc/images/iconsave.png);background-size: 20px 20px;  border: none; width: 20px; height: 20px;' />
                                    <input type=hidden style='width:100px' id=OldStartDate".$celnodv." name=OldStartDate".$celnodv." class='datepicker total_fields' value=".$csqlScheduleDate.">
                                    <input type=hidden style='width:100px' id=OldDueDate".$celnodv." name=OldDueDate".$celnodv." class='datepicker total_fields' value=".$csqlDueDate.">
                                    </div>
                                    <div id=divreschdulefuture-".$celnodv." style='display: none;margin-top:20px;'>
    <span style='float:left;width:100px;margin-top:7px;text-align:left'>Start Date:</span>
    <input type='text' class='total_fields' style='width:120px;float:left' id=StartDate".$celnodv." name=StartDate".$celnodv." value=''/>
    
    <script type='text/javascript'>
            $(function(){
                    $('*[name=StartDate".$celnodv."]').appendDtpicker({
                            'inline': false,
                            'dateOnly': true,
                            'dateFormat': 'DD-MM-YYYY',
                            'closeOnSelected': true
                    });
            });
    </script>
    <br clear='all'/>
    <br clear='all'/>
    <span style='float:left;width:100px;margin-top:7px;text-align:left'>Due Date:</span>
    <input type='text' class='total_fields' style='width:120px;float:left' id=DueDate".$celnodv." name=DueDate".$celnodv." value=''/>
    
    <script type='text/javascript'>
            $(function(){
                    $('*[name=DueDate".$celnodv."]').appendDtpicker({
                            'inline': false,
                            'dateOnly': true,
                            'dateFormat': 'DD-MM-YYYY',
                            'closeOnSelected': true
                    });
            });
    </script>
    
    <br clear='all'/>
    <br clear='all'/>
    <span style='float:left;width:100px;margin-top:7px;text-align:left'>Repeat:</span>
    <select class='total_fields' name='RepeatSchedule' id='RepeatSchedule' style='width:120px;float:left' >";
    $displaydays="none";
        if ($RepeatSchedule1 == 'Daily') {  $outall.= "<option value='Daily' selected >" ; } else{  $outall.= "<option value='Daily' >"; }  $outall.= "Daily</option>";
        if ($RepeatSchedule1 == 'Weekly') { $displaydays="block"; $outall.= "<option value='Weekly' selected >" ; } else{  $outall.= "<option value='Weekly' >"; }  $outall.= "Weekly</option>";
        if ($RepeatSchedule1 == 'Monthly') { $outall.= "<option value='Monthly' selected >";  } else{ $outall.= "<option value='Monthly' >"; }  $outall.= "Monthly</option>";
        if ($RepeatSchedule1 == 'Yearly') { $outall.= "<option value='Yearly' selected >" ; } else{  $outall.= "<option value='Yearly' >"; } $outall.= "Yearly</option>";
    $outall.= "</select>
    <br clear='all'/>
    <br clear='all'/>
    <br clear='all'/>
    
    <div id='DivSelectDay' style='display:$displaydays;float:left'>
    &nbsp;
        Mo <input type=checkbox id=cbxDays name=cbxDays[] value='Mon'"; if (strpos($awdays,"Mon") !== false) { $outall.= " checked";} $outall.= " > &nbsp;&nbsp;";
        $outall.= "Tu <input type=checkbox id=cbxDays name=cbxDays[] value='Tue'"; if (strpos($awdays,"Tue") !== false) { $outall.= " checked";} $outall.= " > &nbsp;&nbsp;";
        $outall.= "We <input type=checkbox id=cbxDays name=cbxDays[] value='Wed'"; if (strpos($awdays,"Wed") !== false) { $outall.= " checked";} $outall.= " > &nbsp;&nbsp;";
        $outall.= "Th <input type=checkbox id=cbxDays name=cbxDays[] value='Thu'"; if (strpos($awdays,"Thu") !== false) { $outall.= " checked";} $outall.= " > &nbsp;&nbsp;";
        $outall.= "Fr <input type=checkbox id=cbxDays name=cbxDays[] value='Fri'"; if (strpos($awdays,"Fri") !== false) { $outall.= " checked";} $outall.= " > &nbsp;&nbsp;";
        $outall.= "Sa <input type=checkbox id=cbxDays name=cbxDays[] value='Sat'"; if (strpos($awdays,"Sat") !== false) { $outall.= " checked";} $outall.= " > &nbsp;&nbsp;";
        $outall.= "Su <input type=checkbox id=cbxDays name=cbxDays[] value='Sun'"; if (strpos($awdays,"Sun") !== false) { $outall.= " checked";} $outall.= " > &nbsp;&nbsp;";

    $outall.= "</div><br clear='all'/><br clear='all'/><br clear='all'/>
    <div id='DivSelectRepeat' style='display:block;'>
    <span style='float:left;width:130px;margin-top:7px;text-align:left'>Next Task After:</span>
    <input type='text' class='total_fields' style='width:50px;float:left' name='NextAfter' id='NextAfter' value='1'/>&nbsp;&nbsp;<label id='LblTextNext'></label><br clear='all'/><br clear='all'/>

    <span style='float:left;width:130px;margin-top:7px;text-align:left'><input type='radio' name='radioNoOfTimes' id='radioNoOfTimes' value='EndAfter' checked>&nbsp;&nbsp;End After </input></span><input type='text' class='total_fields' style='width:50px;float:left' name='EndAfterOccur' id='EndAfterOccur' value='10'/> <span style='float:left;margin-top:7px;text-align:right'>&nbsp;&nbsp;&nbsp;Occurrences</span><br clear='all'/>   <br clear='all'/>
    <span style='float:left;width:130px;margin-top:7px;text-align:left'><input type='radio' name='radioNoOfTimes' id='radioNoOfTimes' value='EndBy'>&nbsp;&nbsp;End By </input></span><input type='text' class='total_fields' style='width:120px;float:left' name='EndByDate' id='EndByDate' value=''/>  <br clear='all'/>   <br clear='all'/>
    <span style='float:left;width:170px;margin-top:7px;text-align:left'><input type='radio' name='radioNoOfTimes' id='radioNoOfTimes' value='NoEnd'>&nbsp;&nbsp;End after 10 years </input></span>

    <script type='text/javascript'>
            $(function(){
                    $('*[name=EndByDate]').appendDtpicker({
                            'inline': false,
                            'dateOnly': true,
                            'dateFormat': 'DD-MM-YYYY',
                            'closeOnSelected': true
                    });
            });
    </script>
    
    </div>
                                    <input type=submit name=btnSaveReSchedule".$celnodv." value='_' title='Save' onclick=reschedulefuture(".$celnodv.") style='background: url(../focinc/images/iconsave.png);background-size: 20px 20px;  border: none; width: 20px; height: 20px;float:left' />
                                    
                                    </div>
                                </div>

                                <div id=divnewuser-".$celnodv." class='divnewuser' style='display: none;width:450px;margin-top:20px' >";
                                $sizeofuserarr=sizeof($UserCodeName_arr);
                                $outall.= "<div style='float:left;line-height:1.2;margin-top:5px;width:110px;text-align:left' class='divnewuserlbl'>Reassign Option: &nbsp;</div>";
                                $outall.= "<select name=reassignopt".$celnodv." style='width:200px;float:left' id=reassignopt".$celnodv." class='total_fields'>";
                                $outall.= "<option value='current' $selected> Current Schedule</option>";
                                if ($RepeatSchedule1!='') { 
                                    $outall.= "<option value='allfuture'>All Future Schedule</option>";
                                }
                                $outall.= "</select><br clear='all'/><br clear='all'/>";
                                $outall.= "<div style='float:left;margin-top:15px;line-height:1.2;width:110px;text-align:left' class='divnewuserlbl'>Reassign: &nbsp;</div>";
                                    if ($id == $taskowner) {
                                   $outall.= "<select name=selNewUser".$celnodv."  id=ForRefUSR".$celnodv." class='total_fields' multiple>";
                                             $i=0;$maxassigneduser=sizeof($assigneduser);
                                             
                                             while($i<$sizeofuserarr)
                                             {   
                                                 $x=0;$selected='';
                                                while($x<$maxassigneduser) {
                                                    if ($UserCodeName_arr[$i][0] == $assigneduser[$x]) {
                                                        $selected ='selected';
                                                    }
                                                    $x++;
                                                }
                                                $outall.= "<option value=".$UserCodeName_arr[$i][0]." $selected> ".$UserCodeName_arr[$i][1]."</option>";
                                               $i++; }
                                    $outall.= "</select> <input type=hidden name=taskowner".$celnodv." value='YES' /> <input type=hidden name=selectedusers".$celnodv." id=selectedusers".$celnodv." value='$selectedusers' />
                                     <script>
                                    document.multiselect('#ForRefUSR".$celnodv."')
                            		.setCheckBoxClick('checkboxAll', function(target, args) {
                            			
                            		})
                            		.setCheckBoxClick('1', function(target, args) {
                            		});
                            		
                            		</script> ";  
                            		//document.getElementById('ForRefUSR".$celnodv."_itemList').style.width ='300px';
                            		//document.getElementById('ForRefUSR".$celnodv."_input').style.width ='300px';
                            		$outall.="<div style='display:inline'> &nbsp; <input type=button onclick=reassign(".$celnodv.") id=btnSaveNewUser".$celnodv." name=btnSaveNewUser".$celnodv." value='_' title='Save' onClick=selecteduser('".$celnodv."') style='background: url(../focinc/images/iconsave.png);background-size: 20px 20px;  border: none; width: 20px; height: 20px;' />";
                                    } else {
                                       $outall.= "<select name=selNewUser".$celnodv." style='width:300px;float:left' id=ForRefUSR".$celnodv." class='total_fields'>";
                                             $i=0;$maxassigneduser=sizeof($assigneduser);
                                             while($i<$sizeofuserarr)
                                             {   
                                                 $x=0;$match="";
                                                while($x<$maxassigneduser) {
                                                    if ($UserCodeName_arr[$i][0] == $assigneduser[$x]) {
                                                     $match="true";  
                                                    }
                                                    $x++;
                                                }
                                                if ($match!="true"){
                                                    $outall.= "<option value=".$UserCodeName_arr[$i][0]." > ".$UserCodeName_arr[$i][1]."</option>";
                                                }
                                               $i++; 
                                             }
                                    $outall.= "</select> <input type=hidden name=taskowner".$celnodv." value='NO' />";
                                    $outall.="<div style='float:left;display:inline;margin-top:5px'> &nbsp; <input type=button onclick=reassignuser(".$celnodv.") name=btnSaveNewUser".$celnodv." value='_' title='Save' style='background: url(../focinc/images/iconsave.png);background-size: 20px 20px;  border: none; width: 20px; height: 20px;' />";
                                    }
                            		
                          /*  		<!--           <br clear='all'/><br clear='all'/>
		                         <div style='float:left'> &nbsp;&nbsp;Schedule Date: &nbsp;&nbsp;
                                    <input type=text style='width:140px' name=NewUserStartDate".$celnodv." class='datepicker total_fields' value=".$cScheduleDateUK.">
                        -->     
                        */
                        
                        $outall.="</div>
                                </div>
                                <br clear='all'/>
                                <div class='taskicon' id=div-".$celnodv."  align=center>
                                    <span onclick=startclock('divstarttime','".$celnodv."') id=clockstarticon".$celnodv." style='display:$clockdisplay'><img src='../focinc/images/iconclockgif.gif' height=20 title='Clock Started' /></span>
                                    <a onclick=showdiv('divstarttime','".$celnodv."')    id=starttimeicon".$celnodv." style='display:$startdisplay'>&nbsp;&nbsp;&nbsp;<img src='../focinc/images/iconstarttime.png' height=20 title='Start Time' /></a>
                                    <a onclick=showdiv('divendtime','".$celnodv."')      id=endtimeicon".$celnodv." style='display:$enddisplay'>&nbsp;&nbsp;&nbsp;<img src='../focinc/images/iconendtime.png' height=20 title='End Time' /></a>
                                    <a onclick=showdiv('diveditgroup','".$celnodv."')>&nbsp;&nbsp;&nbsp;<img src='../focinc/images/iconregroup.png' height=20 title='Re-Group' /></a>
                                    <a onclick=showdiv('divedittags','".$celnodv."')>&nbsp;&nbsp;&nbsp;<img src='../focinc/images/icontag.png' height=20 title='Add Tag' /></a>
                                    <a onclick=showdiv('divaddattach','".$celnodv."')>&nbsp;&nbsp;&nbsp;<img src='../focinc/images/iconattach.png' height=20 title='Add Attachment' /></a>
                                    <a onclick=showdiv('divnewnote','".$celnodv."')>&nbsp;&nbsp;&nbsp;<img src='../focinc/images/iconBedit.png' height=20 title='Add Comments' /></a>";
                                    if ($id == $taskowner) {
                                    $outall.= " <a onclick=showdiv('divnewdate','".$celnodv."')>&nbsp;&nbsp;&nbsp;<img src='../focinc/images/iconBcalander.png' height=20 title='Re-schedule Date' /></a>";
                                    }
                                    $outall.= "<a onclick=showdiv('divnewuser','".$celnodv."')>&nbsp;&nbsp;&nbsp;<img src='../focinc/images/iconBstaff.png' height=20 title='Reassign' /></a>
                                    <a onclick=showdiv('divcomplete','".$celnodv."')>&nbsp;&nbsp;&nbsp;<img src='../focinc/images/imgcheck.png' height=20 title='Mark Complete' /></a>
                                </div>
                                
                             ";
                    }         
        $query11="SELECT * FROM `tSubTasks` a WHERE STRecRef in (Select STRecRef from tSubTaskCal b where cRecRef in 
                (select cRecRef from tCalendar where SRecRef in (select SRecRef from tSchedule c where TRecRef = '$TRecRef') 
                and (`cScheduleDate`,`cDueDate`) in (select `cScheduleDate`,`cDueDate` from tCalendar where cRecRef='$cRecRef') ) AND b.cRecRef in (select cRecRef from tCalendar where cRecRef=b.cRecRef and Status='A')) ORDER BY a.STRecRef ";
        //$query11="SELECT * FROM `tSubTasks` a WHERE  TRecRef = '$TRecRef' ORDER BY a.STRecRef ";
        $sql11 = mysqli_query($mysqli, $query11);    
        $existCount11 = mysqli_num_rows($sql11);
        if ($existCount11>0){
        $SubUserCodeName_arr=array();
        $y=0;
        $query3011 = "SELECT ForRefUSR from tCalendar where TRecRef='$TRecRef' and (cScheduleDate,cDueDate)=(select cScheduleDate,cDueDate from tCalendar where cRecRef='$cRecRef') and Status='A'";
        $sql3011 = mysqli_query($mysqli, $query3011);
        //$outall.=$query3011;
        while($row3011=mysqli_fetch_array($sql3011))
        {
            $ForRefUSR=$row3011['ForRefUSR'];
            $query31="SELECT `RefUSR`, `FirstName`, `LastName`  FROM `tUser` WHERE `RefUSR`='$ForRefUSR' ";
            $sql31 = mysqli_query($mysqli, $query31);
            while($row31 = mysqli_fetch_array($sql31)){
            $UserRef   =$row31["RefUSR"];
            $FirstName  =$row31["FirstName"];
            $LastName  =$row31["LastName"];
            $FullName=$FirstName.' '.$LastName;
            $FullName1=$FirstName.$LastName;
            $FullName=ucwords(strtolower($FullName)); //----- convert to UpperLower Case
            }
        $SubUserCodeName_arr[$y][0]=$UserRef;
        $SubUserCodeName_arr[$y][1]=$FullName;
      //  $outall.=$SubUserCodeName_arr[$y][0];
        //$outall.=$SubUserCodeName_arr[$y][1];
        $y++;
        
        }            
         $sizeofsubuserarr =   sizeof($SubUserCodeName_arr);
             
            $scelnodv=0;
            $outall.="<input type='hidden' id='subgroupcount-".$celnodv."' value='$existCount11'/>";
            $outall.="<span style='float:left'><b>Sub Task</b></span><br/>";
            while($row11=mysqli_fetch_array($sql11))
            {
                $TRecRefsub=$row11['TRecRef'];
                $STRecRefsub=$row11['STRecRef'];
                //$cRecRefsub=$row11['cRecRef'];
                $TaskTitlesub=$row11['TaskTitle'];
                $Descrsub=$row11['Descr'];
                $Prioritysub=$row11['Priority'];
                $Stagesub=$row11['Stage'];
                $TimeTakensub=$row11['TimeTaken'];
                $CompleteBysub=$row11['CompleteBy'];
                $CompleteDTsub=$row11['CompleteDT'];
                $subtaskowner=$row11['CreatedBy'];
                $CreatedDateTimesub=$row301['CreatedDateTime'];
                if ($Prioritysub =="P1") {$subtaskcolor="#FA8654";}
                if ($Prioritysub =="P2") {$subtaskcolor="#FACA54";}
                if ($Prioritysub =="P3") {$subtaskcolor="#FAF054";}
                if ($Stagesub =="Completed") {$subtaskcolor="green";}
                $subassigneduser=array();
                $x=0;
                $subinitials='';
                $usertask='N';
                $squery3011 = "SELECT ForRefUSR from tSubTaskCal a where STRecRef='$STRecRefsub' and Status='A' and cRecRef in (select cRecRef from tCalendar where Status='A' and cRecRef=a.cRecRef)";
                $ssql3011 = mysqli_query($mysqli, $squery3011);
                while($row30111=mysqli_fetch_array($ssql3011))
                {
                    $sForRefUSR=$row30111['ForRefUSR'];
                    $squery31="SELECT `RefUSR`, `FirstName`, `LastName`  FROM `tUser` WHERE `RefUSR`='$sForRefUSR' ";
                    $ssql31 = mysqli_query($mysqli, $squery31);
                    while($row311 = mysqli_fetch_array($ssql31)){
                        $sFirstName  =$row311["FirstName"];
                        $sLastName  =$row311["LastName"];
                        $sFullName=$sFirstName.' '.$sLastName;
                        $sFullName=ucwords(strtolower($sFullName)); //----- convert to UpperLower Case
                    }
                    if($ForRefUSRC==$sForRefUSR) {$color1="#ccc";$usertask='Y';} else { $color1="#fff";};
                    $subinitials.="<span style='background:$color1;color:#000;border-radius:50%;padding:5px;border:1px solid #000' ><a href='#' title='$sFullName'>".substr($sFirstName,0,1).substr($sLastName,0,1)."</a></span>&nbsp;&nbsp;";
                    $subassigneduser[$x]=$sForRefUSR;
                    $x++;
                }
                
                $query212 = "SELECT * FROM `tTaskNotes` t1 WHERE `STRecRef` ='$STRecRefsub' AND `Stage`='SUBSTARTTIME' AND cRecRef='$cRecRef' AND not EXISTS (
                        SELECT * FROM `tTaskNotes` t2 WHERE `STRecRef` ='$STRecRefsub' AND `Stage`='SUBENDTIME' AND cRecRef='$cRecRef' AND `NotesDT` > t1.`NotesDT`)";
                    $sql212 = mysqli_query($mysqli, $query212);
                    $row212=mysqli_fetch_array($sql212);
                    $sNRecRef=$row212['NRecRef'];
                    $substartdisplay="inline";$subclockdisplay="none"; $subenddisplay="none";
                    $subtaskid='';
                    if ($sNRecRef!=""){ $substartdisplay="none";$subclockdisplay="inline";$subenddisplay="inline";$subtaskid=$STRecRefsub; }
                
                
                $outall.="<input type='hidden' id='EditSubTaskRef-".$celnodv."-".$scelnodv."' value='$STRecRefsub'/>";
                $outall.="<input type='hidden' id='subgroupstatus-".$celnodv."-".$scelnodv."' value='$Stagesub'/>";
                $outall.="<div class='WHITbkgLghtBLUEborder taskboard'  style='width:99%; border-left-width: 10px;background:#fff; margin-top:10px;border-color: ".$subtaskcolor." ;' 
                               id=sdv-".$celnodv."-".$scelnodv." >";
                $outall.="<table class='myTable1' cellpadding=4 cellspacing=0 width=100% border=0>";
                $outall.="<tr><td style='width:50%;text-align:left;line-height:1.2'><a href='#' onclick=popup('popUpDiv','subtasknotes','$celnodv','$scelnodv')><b>$TaskTitlesub </b><br/><i>&nbsp;&nbsp;$Descrsub</i></a></td>";
                $outall.="<td style='width:30%;text-align:left;'>$subinitials</td>";
                $outall.="<td class='tableicons' width=270px align=right>";
                if ($usertask=='Y' && $Stagesub !="Completed") {
                $outall.="          <span id=clockstarticon".$celnodv."-".$scelnodv." style='display:$subclockdisplay'><img src='../focinc/images/iconclockgif.gif' height=20 title='Clock Started' /></span>
                                    <a onclick=showsubdiv('divsubstarttime','".$celnodv."','".$scelnodv."')    id=starttimeicon".$celnodv."-".$scelnodv." style='display:$substartdisplay'>&nbsp;<img src='../focinc/images/iconstarttime.png' height=20 title='Start Time' /></a>
                                    <a onclick=showsubdiv('divsubendtime','".$celnodv."','".$scelnodv."')      id=endtimeicon".$celnodv."-".$scelnodv." style='display:$subenddisplay'>&nbsp;<img src='../focinc/images/iconendtime.png' height=20 title='End Time' /></a>
                                    <a onclick=showsubdiv('divsubaddattach','".$celnodv."','".$scelnodv."')>&nbsp;<img src='../focinc/images/iconattach.png' height=20 title='Add Attachment' /></a>
                                    <a onclick=showsubdiv('divsubnewnote','".$celnodv."','".$scelnodv."')>&nbsp;<img src='../focinc/images/iconBedit.png' height=20 title='Add Comments' /></a>
                                    <a onclick=showsubdiv('divsubnewuser','".$celnodv."','".$scelnodv."')>&nbsp;<img src='../focinc/images/iconBstaff.png' height=20 title='Reassign' /></a>
                                    <a onclick=showsubdiv('divsubcomplete','".$celnodv."','".$scelnodv."')>&nbsp;<img src='../focinc/images/imgcheck.png' height=20 title='Mark Complete' /></a>";
                }
                $outall.="</td></tr>";
                
                $outall.="</table><br clear='all'/>";
                
                $outall.="      <div id=divsubstarttime-".$celnodv."-".$scelnodv." style='display: none;margin-top:20px' >
                                    <input type='hidden' class=subnoteid id=notid".$celnodv."-".$scelnodv."    value='$sNRecRef'>
                                    <input type='hidden' class=subntaskid id=ntaskid".$celnodv."-".$scelnodv." value='$subtaskid'>
                                </div>
                                
                                <div id=divsubendtime-".$celnodv."-".$scelnodv." style='display: none;margin-top:20px' >
                                    <div style='margin-top:10px;float:left'>End Notes:*</div> <textarea class='total_fields NewCompNote' id=EndNote".$celnodv."-".$scelnodv." name=EndNote".$celnodv."-".$scelnodv." rows=4 style='width: 80%;margin-top:10px;height:50px' ></textarea>
                                    <div style='margin:30px 8% 0 0;float:right' class='btnSaveNewNote'><input type=button onClick='addsubendnote($celnodv,$scelnodv)' name=btnSaveEndNote".$celnodv." value='_' title='Save' style='background: url(../focinc/images/iconsave.png) no-repeat;;background-size: 20px 20px;  border: none; width: 20px; height: 20px;' /></div>
                                </div>
                                
                                <div id=divsubaddattach-".$celnodv."-".$scelnodv." style='display: none;margin-top:20px' >
                                    <form>
                                    <div style='margin-top:10px;float:left;text-align:left'>
                                    <label>Supporting Document Notes:* </label><br clear='all' /><br clear='all' />
                                    <textarea  class='form-control total_fields' name='sDocNote".$celnodv."-".$scelnodv."' id='sDocNote".$celnodv."-".$scelnodv."' style='width:270px;align:left;' row=4 value=''></textarea><br clear='all' /><br clear='all' />
                                    <label>Upload: </label><br clear='all' /><br clear='all' />
                                    <input id='AttachDoc".$celnodv."-".$scelnodv."' name='AttachDoc".$celnodv."-".$scelnodv."' style='float:left;' type='file' class='btn btn-default total_fields' />
                                    </div>
                                    <div style='margin:110px 0 0 10px;float:left' class='btnSaveNewNote'><input type=button onclick=addsubfile(".$celnodv.",".$scelnodv.") name=btnUploadFile".$celnodv."-".$scelnodv." value='_' title='Save' style='background: url(../focinc/images/iconsave.png) no-repeat;;background-size: 20px 20px;  border: none; width: 20px; height: 20px;' /></div><br clear='all' /><br clear='all' />
                                    </form>
                                </div>
                                
                                <div id=divsubnewuser-".$celnodv."-".$scelnodv." class='divnewuser' style='display: none;width:450px;margin-top:20px' >";
                                
                                  $outall.= "<div style='float:left;margin-top:5px;line-height:1.2;width:110px;text-align:left' class='divnewuserlbl'>Reassign: &nbsp;</div>";
                                    if ($id == $subtaskowner) {
                                   $outall.= "<select name=selNewUser".$celnodv."  id=ForRefUSR".$celnodv."-".$scelnodv." class='total_fields' multiple>";
                                             $i=0;$maxsubassigneduser=sizeof($subassigneduser);
                                             
                                             while($i<$sizeofsubuserarr)
                                             {   
                                                 $x=0;$selected='';
                                                while($x<$maxsubassigneduser) {
                                                    if ($SubUserCodeName_arr[$i][0] == $subassigneduser[$x]) {
                                                        $selected ='selected';
                                                    }
                                                    $x++;
                                                }
                                                $outall.= "<option value=".$SubUserCodeName_arr[$i][0]." $selected> ".$SubUserCodeName_arr[$i][1]."</option>";
                                               $i++; 
                                             }
                                    $outall.= "</select>
                                     <script>
                                    document.multiselect('#ForRefUSR".$celnodv."-".$scelnodv."')
                            		.setCheckBoxClick('checkboxAll', function(target, args) {
                            			
                            		})
                            		.setCheckBoxClick('1', function(target, args) {
                            		});
                            		
                            		</script> ";  
                            		$outall.="<div style='display:inline'> &nbsp; <input type=button onclick=reassignsub(".$celnodv.",".$scelnodv.") value='_' title='Save' onClick=selecteduser('".$celnodv."','".$scelnodv."') style='background: url(../focinc/images/iconsave.png);background-size: 20px 20px;  border: none; width: 20px; height: 20px;' /></div>";
                                    } else {
                                       $outall.= "<select name=selNewUser".$celnodv."-".$scelnodv." style='width:300px;' id=ForRefUSR".$celnodv."-".$scelnodv." class='total_fields'>";
                                             $i=0;$maxsubassigneduser=sizeof($subassigneduser);
                                             while($i<$sizeofsubuserarr)
                                             {   
                                                 $x=0;$match="";
                                                while($x<$maxsubassigneduser) {
                                                    if ($SubUserCodeName_arr[$i][0] == $subassigneduser[$x]) {
                                                     $match="true";  
                                                    }
                                                    $x++;
                                                }
                                                if ($match!="true"){
                                                    $outall.= "<option value=".$SubUserCodeName_arr[$i][0]." > ".$SubUserCodeName_arr[$i][1]."</option>";
                                                }
                                               $i++; 
                                             }
                                    $outall.= "</select> ";
                                    $outall.="<div style='display:inline;margin-top:5px'> &nbsp; <input type=button onclick=reassignsubuser(".$celnodv.",".$scelnodv.")  value='_' title='Save' style='background: url(../focinc/images/iconsave.png);background-size: 20px 20px;  border: none; width: 20px; height: 20px;' /></div>";
                                    }
                                $outall.=" <br clear='all'/> <br clear='all'/></div>
                                
                                <div id=divsubcomplete-".$celnodv."-".$scelnodv." style='display: none;margin-top:20px' >
                                <div style='width:130px;float:left;line-height:1.2;text-align:left'>Time Taken*: &nbsp;&nbsp;</div>
                                <input type=text class='total_fields'  name='upTimeTaken".$celnodv."-".$scelnodv."' id='upTimeTaken".$celnodv."-".$scelnodv."'  style='width:70px;text-align:right;float:left' placeholder='HH:MM' />
                                <br clear='all'/>
                                <br clear='all'/>
                                    <div style='width:130px;margin-top:10px;float:left;text-align:left'>Completion Note:*</div> <textarea class='total_fields' id=NewCompNote".$celnodv."-".$scelnodv." name=NewCompNote".$celnodv."-".$scelnodv." rows=4 style='width: 250px;margin-top:10px;height:50px;float:left' ></textarea>
                                    <div style='margin:30px 0 0 20px;float:left' class='btnSaveNewNote'><input type=button onclick=completesubtask(".$celnodv.",".$scelnodv.") name=btnSaveNewNote".$celnodv."-".$scelnodv." value='_' title='Save' style='background: url(../focinc/images/iconsave.png) no-repeat;background-size: 20px 20px;  border: none; width: 20px; height: 20px;' /></div>
                                <br clear='all'/>
                                <br clear='all'/>
                                </div>
                                
                                <div id=divsubnewnote-".$celnodv."-".$scelnodv." style='display: none;margin-top:20px' >
                                <div style='width:100px;float:left;line-height:1.2;text-align:left'>Time Taken: &nbsp;&nbsp;</div>
                                <input type=text class='total_fields'  name='noteupTimeTaken".$celnodv."-".$scelnodv."' id='noteupTimeTaken".$celnodv."-".$scelnodv."'  style='width:70px;text-align:right;float:left' placeholder='HH:MM' />
                                <br clear='all'/>
                                <br clear='all'/>
                                    <div style='width:100px;margin-top:10px;float:left;text-align:left'>Notes:</div> <textarea class='total_fields' id=NewNote".$celnodv."-".$scelnodv." name=NewNote".$celnodv."-".$scelnodv." rows=4 style='width: 250px;margin-top:10px;height:50px;float:left' ></textarea>
                                    <div style='margin:30px 0 0 20px;float:left' class='btnSaveNewNote'><input type=button onclick=newsubnote(".$celnodv.",".$scelnodv.") name=btnSaveNewNote".$celnodv."-".$scelnodv." value='_' title='Save' style='background: url(../focinc/images/iconsave.png) no-repeat;background-size: 20px 20px;  border: none; width: 20px; height: 20px;' /></div>
                                <br clear='all'/>
                                <br clear='all'/>
                                </div>

                                
                                ";
                
                $outall.="</div>";
                $scelnodv++;
            }
        } else {
            $outall.="<input type='hidden' id='subgroupcount-".$celnodv."' value='0'/>";
        }
    
?>