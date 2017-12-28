<?php 
    $active_sms_service = $this->db->get_where('settings' , array('type' => 'active_sms_service'))->row()->description;
?>
<hr />
<div class="row">

    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
    	<thead>
        	<tr>
                <th>Class</th>
                <th>Section</th>
                <th>Date</th>
                <th></th>
           </tr>
       </thead>
		<tbody>
        	<form method="post" action="<?php echo base_url();?>index.php?admin/attendance_selector" class="form">
            	<tr class="gradeA">
                    <td>
                        <select name="class_id" id="class_id" class="form-control" onchange="select_section(this.value)">
                            <option value="">Select a class</option>
                            <?php 
                            $classes    =   $this->db->get('class')->result_array();
                            foreach($classes as $row):?>
                            <option value="<?php echo $row['class_id'];?>"
                                <?php if(isset($class_id) && $class_id==$row['class_id'])echo 'selected="selected"';?>>
                                    <?php echo $row['name'];?>
                                        </option>
                            <?php endforeach;?>
                        </select>

                    </td>
                    <td>
                    	<select name="section_id" id="section" class="form-control">
                            <option value="" class="section">Select Class First</option>
                            <?php 
                            $sections    =   $this->db->get('section')->result_array();
                            foreach($sections as $row):?>
                            <option class="section" value="<?php echo $row['section_id'];?>"
                                <?php if(isset($section_id) && $section_id==$row['section_id'])echo 'selected="selected"';?> style="display:none;">
                                    <?php echo $row['name'];?>
                                        </option>
                            <?php endforeach;?>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control datepicker" name="timestamp" data-format="dd-mm-yyyy" value="<?php echo $date."-".$month."-".$year ;?>">
                    </td>
                    <td align="center">
    <button type="submit" class="btn btn-blue btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>&nbsp;<?php echo get_phrase('manage_attendance'); ?></button>

                    
                    </td>
                </tr>
            </form>
		</tbody>
	</table>
</div>

<hr />
<?php if($date!='' && $month!='' && $year!='' && $class_id!=''):?>
<div class="row" style="text-align: center;">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <div class="tile-stats tile-gray">
            <div class="icon"><i class="entypo-chart-area"></i></div>
            <?php 
                $classes    =   $this->db->get('class')->result_array();
                foreach ($classes as $row) {
                    if(isset($class_id) && $class_id==$row['class_id']) $calss_name = $row['name'];
                }
            ?>
            <h3 style="color: #696969;">Attendance For Class <?php echo $calss_name;?></h3>
            <?php 
                $sections    =   $this->db->get('section')->result_array();
                foreach ($sections as $row) {
                    if(isset($section_id) && $section_id==$row['section_id']) $calss_name = $row['name'];
                }
            ?>
            <h4 style="color: #696969;">Section <?php echo $calss_name; ?></h4>
            <?php
                $full_date = $date."-".$month."-".$year;
                $full_date = date_create($full_date);
                $full_date = date_format($full_date,"d M Y");
             ;?>
            <h4 style="color: #696969;"><?php echo $full_date;?></h4>
        </div>
    </div>
    <div class="col-sm-4"></div>
</div>

<center>
    <a class="btn btn-green btn-sm btn-icon icon-left" onclick="mark_all_present()">
        <i class="entypo-check"></i> Mark All Present    </a>
    <a class="btn btn-red btn-sm btn-icon icon-left" onclick="mark_all_absent()">
        <i class="entypo-cancel"></i> Mark All Absent    </a>
</center>

<br>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <form action="<?php echo base_url();?>index.php?admin/manage_attendance/<?php echo $date.'/'.$month.'/'.$year.'/'.$class_id.'/'.$section_id;?>" method="post" accept-charset="utf-8">
        <div id="attendance_update">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Roll</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    //STUDENTS ATTENDANCE
                    $students   =   $this->db->get_where('student' , array('class_id'=>$class_id))->result_array();
                    $full_date = $year."-".$month."-".$date;
                    $i = 1;
                    foreach($students as $row)
                    {
                        ?>
                    <tr class="gradeA">
                        <td><?php echo $i;?></td>
                        <td><?php echo $row['roll'];?></td>
                        <td><?php echo $row['name'];?></td>
                        <td>
                            <?php 
                            //inserting blank data for students attendance if unavailable
                            $verify_data    =   array(  'student_id' => $row['student_id'],
                                                        'date' => $full_date);
                            $query = $this->db->get_where('attendance' , $verify_data);
                            if($query->num_rows() < 1)
                            $this->db->insert('attendance' , $verify_data);
                            
                            //showing the attendance status editing option
                            $attendance = $this->db->get_where('attendance' , $verify_data)->row();
                            $status     = $attendance->status;
                            ?>
                            
                            
                                <select name="status_<?php echo $row['student_id'];?>" class="status form-control">
                                    <option value="0" <?php if($status == 0)echo 'selected="selected"';?>>Undefined</option>
                                    <option value="1" <?php if($status == 1)echo 'selected="selected"';?>>Present</option>
                                    <option value="2" <?php if($status == 2)echo 'selected="selected"';?>>Absent</option>
                                </select>
                        </td>
                    </tr>
                    <?php
                        $i++; 
                    }
                    ;?>
                </tbody>
            </table>
        </div>
        <center>
            <button type="submit" class="btn btn-green btn-sm btn-icon icon-left" id="submit_button"><i class="entypo-check"></i> Save Changes</button>
        </center>
        </form>
    </div>
</div>





<br>

<?php 
        if ($active_sms_service == ''):
    ?>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
           <div class="alert alert-danger">
                SMS <?php echo get_phrase('service_is_not_selected');?>
           </div> 
        </div>
        <div class="col-md-4"></div>
    </div>
    <?php endif;?>
    <?php 
        if ($active_sms_service == 'disabled'):
    ?>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="alert alert-warning">
                SMS <?php echo get_phrase('service_is_disabled');?>
           </div>
        </div>
        <div class="col-md-4"></div>
    </div>
    <?php endif;?>
    <?php 
        if ($active_sms_service == 'clickatell'):
    ?>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="alert alert-info">
                SMS <?php echo get_phrase('will_be_sent_by_clickatell');?>
           </div>
        </div>
        <div class="col-md-4"></div>
    </div>
    <?php endif;?>
    <?php 
        if ($active_sms_service == 'twilio'):
    ?>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="alert alert-info">
                SMS <?php echo get_phrase('will_be_sent_by_twilio');?>
           </div>
        </div>
        <div class="col-md-4"></div>
    </div>
    <?php endif;?>

<?php endif;?>

<script type="text/javascript">

    $("#update_attendance").hide();

    function update_attendance() {

        $("#attendance_list").hide();
        $("#update_attendance_button").hide();
        $("#update_attendance").show();

    }

    function select_section(class_id) {

        var sections = $(".section");
        for (var i = sections.length - 1; i >= 0; i--) {
            sections[i].style.display = "none";
            if (sections[i].value == class_id){
                sections[i].style.display = "block";
                sections[i].selected = "selected";
            }
        }
    }

    function mark_all_present() {
        var status = $(".status");
        for(var i = 0; i < status.length; i++)
            status[i].value = "1";
    }

    function mark_all_absent() {
        var status = $(".status");
        for(var i = 0; i < status.length; i++)
            status[i].value = "2";
    }


</script>
<style>
    div.datepicker{
        border: 1px solid #c4c4c4 !important;
    }
</style>