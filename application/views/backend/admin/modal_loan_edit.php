<?php 
$edit_data		=	$this->db->get_where('loan' , array('loan_id' => $param2) )->result_array();
foreach ( $edit_data as $row):
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('update_loan_approval');?>
            	</div>
            </div>
			<div class="panel-body">
                    <?php echo form_open(base_url() . 'index.php?admin/loan_approval/do_update/'.$row['loan_id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                        		
                                 
				   <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('status');?></label>
                      <div class="col-sm-9">
 <select name="status" class="form-control">
                                <option value=""><?php echo $row ['status']?></option>
                                <option value="Approved"><?php echo get_phrase('Approved'); ?></option>
                                <option value="Disapproved"><?php echo get_phrase('Disapproved'); ?></option>
                                <option value="Pending"><?php echo get_phrase('Pending'); ?></option>
                            </select>                              
                      </div>
                  </div>
                            
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-5">
                                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i>&nbsp;<?php echo get_phrase('update_loan');?></button>
                            </div>
                        </div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<?php
endforeach;
?>