<hr>
<div class="row">
	<div class="col-md-12">       
           		<!----CREATION FORM STARTS---->
 <div class="panel panel-gradient" >
                <div class="panel-heading">
                    <div class="panel-title">
                        <?php echo get_phrase('submit_complaint_here');?>
                    </div>
                </div>
				
                	<?php echo form_open(base_url() . 'index.php?student/help_desk/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
							                <div class="panel-body">

							 <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                                <div class="col-sm-5">
                                    <input name="name" type="text"  class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('purpose');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="purpose"/>
                                </div>
                            </div>
							
							 <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('content');?></label>
                                <div class="col-sm-5">
                                    <textarea type="text" class="form-control" name="content"></textarea>
                                </div>
                            </div>
                            
                           <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_helpful_link');?></button>
                              </div>
							</div>
                    </form>                
					        
                </div>                
			            <?php echo form_close();?>

			<!----CREATION FORM ENDS-->
			
			
	</div>