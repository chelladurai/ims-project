 <hr>
 <div class="panel panel-gradient" >
            
                <div class="panel-heading">
                    <div class="panel-title">
					 <?php echo get_phrase('exam_subject'); ?>
					</div>
					</div>
            <!----TABLE LISTING STARTS-->
            
                            <div class="panel-body table-responsive">
                                <div class="form-group">
						<h4>
						<div align="center">
								<span class="label label-info">
								Exam Date:
                                    <?php
                                    date_default_timezone_set('Asia/Hong_Kong');
                                   echo date('Y.m.d');
                                    ?>
									</span>
									</div>
									</h4>
                                </div>
<hr style="color:#FF0000">
                                <div class="form-group">
                                    <?php
                                    foreach ($subject_list as $row):
                                        if ($row['result_count'] > 0) {
                                            ?>
                                            <button class="btn btn-primary disabled">
                                                <?php echo $row['subject']; ?>(<?php echo $row['session']; ?>) | <?php echo $row['marks']; ?> / <?php echo $row['question_count'];
                                                ?>
                                            </button>
                                            <?php
                                        } else {
                                            ?>
                                            <button class="btn btn-success" onclick="location.href = '<?php echo base_url(); ?>index.php?student/exam/second/<?php echo $row['subject_id']; ?>/<?php echo $row['session']; ?>';">
                                                <?php echo $row['subject']; ?>(<?php echo $row['session']; ?>)
                                            </button>
                                            <?php
                                        }
                                    endforeach;
                                    ?>
<hr>
<div class="alert alert-warning">KINDLY NOTE THAT YOU ARE ABOUT TO START EXAM FOR THE ABOVE SUBJECT/SUBJECTS. KINLY CLICK ON THE AVAILABLE SUBJECT/SUBJECTS ABOVE TO START THE EXAM. ALSO NOTE THAT THE EXAM CAN NOT BE DONE TWICE, ONCE YOU START THE EXAM, YOUR DATA WILL BE SAVED AUTOMATICALLY TO THE DATABASE.</div>
                               
                </div>
            </div>
            <!----TABLE LISTING ENDS--->
        </div>

