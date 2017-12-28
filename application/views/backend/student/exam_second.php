 <hr>
 <div class="panel panel-gradient" >
            
                <div class="panel-heading">
                    <div class="panel-title">
					 <?php echo get_phrase('exam_information'); ?>
					</div>
					</div>
        <!------CONTROL TABS END------>

                    <div class="panel panel-gradient">
                        <div id="collapse" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <?php echo form_open(base_url() . 'index.php?student/exam_site', array('id' => 'form1', 'class' => 'form-horizontal form-groups-bordered validate', 'target' => '_top')); ?>
                                <div class="padded">
                                    <div class="form-group">
                                        <div class="col-sm-5 ">
								<h3>
										<span class="label label-info ">
										Exam date: &nbsp;&nbsp;
                                            <?php
                                            date_default_timezone_set('Asia/Hong_Kong');
                                            echo date('Y.m.d');
                                            ?>
											</span>
							</h3>
                                        </div>
                                    </div>
<hr>
                                    <div class="form-group">
									<h4>
                                        <label class="col-sm-1 control-label text-right padding-top-none"><?php echo get_phrase('exam_subject'); ?>: </label>
									</h4>
                                        <div class="col-sm-5">
										<h4>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="label label-success">
                                        <?php echo $data['name']; ?>
										</span>
										</h4>
                                        </div>
                                    </div>

                                    <div class="form-group">
									<h4>
                                        <label class="col-sm-1 control-label text-right padding-top-none"><?php echo get_phrase('exam_duration'); ?>: </label>
									</h4>
                                        <div class="col-sm-5">
										<h4>
										&nbsp;&nbsp;&nbsp;&nbsp;
									<span class="label label-success ">
                                            <?php echo $data['duration']; ?>&nbsp;Minutes
											</span>
											</h4>
                                        </div>
                                    </div>

                                    <div class="form-group">
								<h4>
                                        <label class="col-sm-1 control-label text-right padding-top-none"><?php echo get_phrase('total_question'); ?>: </label>
								</h4>
                                        <div class="col-sm-5">
										<h4>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="label label-success ">

                                            <?php echo $data['question_count']; ?>&nbsp;Questions
									     </span>
										<h4>
                                        </div>
                                    </div>
									
									
<hr>
                                    <div class="form-group">
                                        <div class="col-sm-0 text-center">
                                            <button type="submit" class="btn btn-success btn-sm btn-icon icon-left"><i class="entypo-pencil"></i><?php echo get_phrase('click_to_exam'); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="class_id" value="<?php echo $data['class_id']; ?>"/>
                                <input type="hidden" name="subject_id" value="<?php echo $data['subject_id']; ?>"/>
                                <input type="hidden" name="duration" value="<?php echo $data['duration']; ?>"/>
                                <input type="hidden" name="question_count" value="<?php echo $data['question_count']; ?>"/>
                                <input type="hidden" name="session" value="<?php echo $data['session']; ?>"/>
                                <input type="hidden" name="date" value="<?php echo $data['date']; ?>"/>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!----TABLE LISTING ENDS--->