<?php 
$class_info                 = $this->db->get('enquiry_category')->result_array();
$single_study_material_info = $this->db->get_where('enquiry_category', array('enquirycat_id' => $param2))->result_array();
foreach ($single_study_material_info as $row) {
?>
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-primary" data-collapsed="0">

                <div class="panel-heading">
                    <div class="panel-title">
                        <h3><?php echo get_phrase('edit_enquiry_category'); ?></h3>
                    </div>
                </div>

                <div class="panel-body">

                    <form role="form" class="form-horizontal form-groups-bordered" action="<?php echo base_url(); ?>index.php?admin/enquiry_setting/do_update/<?php echo $row['enquirycat_id'] ?>" method="post" enctype="multipart/form-data">

                      
					  
					     <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('category'); ?></label>

                        <div class="col-sm-5">
                            <input type="text" name="category"  value="<?php echo $row['category']; ?>" class="form-control" id="field-1" >
                        </div>
                    </div>
					
                      <div class="form-group">
                        <label for="field-ta" class="col-sm-3 control-label"><?php echo get_phrase('purpose'); ?></label>

                        <div class="col-sm-9">
                            <input type="text" name="purpose"  value="<?php echo $row['purpose']; ?>" class="form-control" id="field-1" >
                        </div>
                    </div>
                        
                         <div class="form-group">
                        <label for="field-ta" class="col-sm-3 control-label"><?php echo get_phrase('whom'); ?></label>

                        <div class="col-sm-9">
                            <input type="text" name="whom"  value="<?php echo $row['whom']; ?>" class="form-control" id="field-1" >
                        </div>
                    </div>
					
                        <div class="col-sm-3 control-label col-sm-offset-1">
                            <input type="submit" class="btn btn-success" value="Update">
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>
<?php } ?>