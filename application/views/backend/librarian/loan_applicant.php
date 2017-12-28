<hr />

    <div class="row">
    <?php echo form_open(base_url() . 'index.php?librarian/loan_applicant/create' , 
      array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
        <div class="col-md-12">
            
            <div class="panel panel-gradient" >
            
                <div class="panel-heading">
                    <div class="panel-title">
                        <?php echo get_phrase('loan_applicant');?>
                    </div>
                </div>
                
                <div class="panel-body">
          
		  			<div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('date'); ?></label>

                        <div class="col-sm-7">
                            <div class="date-and-time">
                                <input type="text" name="date" class="form-control datepicker" data-format="D, dd MM yyyy" placeholder="date here">
                            </div>
                        </div>
                    </div>
                    
                  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('staff_name');?></label>
                      <div class="col-sm-9">
 										<select name="staff_name" class="form-control select2" style="width:100%;" required>
                                    	<?php 
										$librarians = $this->db->get('librarian')->result_array();
										foreach($librarians as $row):
										?>
                                            <option value=""><?php echo get_phrase('select');?></option>
                                    		<option value="<?php echo $row['name'];?>"><?php echo $row['name'];?></option>
                                        <?php
										endforeach;
										?>
                                    </select>                              
                      </div>
                  </div>
                    
                  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('loan_amount');?></label>
                      <div class="col-sm-9">
                          <input type="number" class="form-control" name="amount" / required>
                      </div>
                  </div>
                    
                  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('purpose');?></label>
                      <div class="col-sm-9">
                          <textarea type="text" class="form-control" name="purpose" required></textarea>                      
				   </div>
                  </div>
                    
                  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('loan_duration');?></label>
                      <div class="col-sm-9">

						<select name="l_duration" class="form-control" required>
				  <option value="One Month">One Month</option>
				  <option value="Two Month">Two Months</option>
				  <option value="Three Months">Three Months</option>
				  <option value="Four Months">Four Months</option>
				   <option value="Five Month">Five Month</option>
				  <option value="Six Month">Six Months</option>
				  <option value="Seven Months">Seven Months</option>
				  <option value="Eight Months">Eight Months</option>
				  <option value="Nine Months">Nine Months</option>				 
				   <option value="Ten Months">Ten Months</option>
				  <option value="Eleven Months">Eleven Months</option>
				  <option value="One Year">One Year</option>
				  <option value="Two Years">Two Years</option>
				  <option value="Above Two Years">Above Two Years</option>
				  </select>
					                  
                      </div>
                  </div>
                    
                  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('payment_mode');?></label>
                      <div class="col-sm-9">

						<select name="mop" class="form-control" required>
				  <option value="Daily">Daily</option>
				  <option value="Weekly">Weekly</option>
				  <option value="Bi-weekly">Bi-weekly</option>
				  <option value="Monthly">Monthly</option>
				  <option value="Bi-Monthly">Bi-Monthly</option>
				  <option value="Yearly">Yearly</option>
				  </select>                              
                      </div>
                  </div>
<hr>	
<div class="alert-danger">&nbsp;GUARANTOR'S INFORMATION</div>
<hr>
				  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('guarantor_name');?></label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" name="g_name"  / required>
                              
                      </div>
                  </div>
				  
				  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('relationship');?></label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" name="g_relationship"  / required>
                              
                      </div>
                  </div>
				  
				  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('guarantor_number');?></label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" name="g_number"  /required>
                              
                      </div>
                  </div>
				  
				   <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('guarantor_address');?></label>
                      <div class="col-sm-9">
                          <textarea type="text" class="form-control" name="g_address" required></textarea>
                              
                      </div>
                  </div>
				  
				  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('guanrator_country');?></label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" name="g_country"  /required>
                              
                      </div>
                  </div>
<hr>	
<div class="alert-danger">&nbsp;COLLATERAL INFORMATION</div>
<hr>
				  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('collaral_name');?></label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" name="c_name"  /required>
                              
                      </div>
                  </div>
				  
				  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('collaral_type');?></label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" name="c_type"  /required>
                              
                      </div>
                  </div>
				  
				  
				  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('collaral_model');?></label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" name="model"  /required>
                              
                      </div>
                  </div>
				  
				  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('collaral_make');?></label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" name="make"  /required>
                              
                      </div>
                  </div>
				  
				   <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('serial_number');?></label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" name="serial_number"  /required>
                              
                      </div>
                  </div>
				  
				  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('collateral_value');?></label>
                      <div class="col-sm-9">
                          <input type="number" class="form-control" name="value" placeholder= "How Much Does it Worth" /required>
                              
                      </div>
                  </div>
				  
				  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('condition');?></label>
                      <div class="col-sm-9">
				  <select name="condition" class="form-control" required>
				  <option value="Daily">fair</option>
				  <option value="Weekly">Bad</option>
				  <option value="Bi-weekly">Good</option>
				  <option value="Monthly">Others</option>
				  </select>                              
                      </div>
                  </div>
				  
				    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('collateral_documents'); ?></label>
                        <div class="col-sm-5">
                        <input type="file" name="file_name" class="form-control file2 inline btn btn-primary" data-label="<i class='glyphicon glyphicon-file'></i> Browse" />
						<div style="color:#FF0000">Note that you are to submit hardcopy the adminstrattive officer for proper verifications.<br>
												   You can upload zip files here, so zip all the documents and upload here.</div>

                    </div>
                    </div>
				  
				   <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('status');?></label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" name="status" value="Pending" readonly="true"/required>
                              
                      </div>
                  </div>
                
                  <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-info"><?php echo get_phrase('apply_now');?></button>
                    </div>
                  </div>
                    <?php echo form_close();?>
                    
                </div>
            
            </div>
          

        </div>
        
        </div>