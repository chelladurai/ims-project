<!DOCTYPE html>
<html>
<head>
	<title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<div class="container">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style type="text/css">
		td {
			padding: 5px;
            /*color: #000 !important;*/
            border: 1px solid #D2CBCB;
		}
	</style>
<?php 
    $students   =   $this->crud_model->get_students($class_id); 
    foreach($students as $row): 
        $student_id = $row['student_id'];
        $total_marks = 0;
        $total_class_score = 0;

        $total_grade_point = 0;
        ?>


    <div class="print">
        <br/>
        <br/>
        <br/>
        <br/>
        <div class="row">
            <div class="col-md-2 logo" style="text-align: right;">
                <img src="uploads/logo.png" style="max-height:100px;margin-left: 200px;">
            </div>
            <div class="col-md-10" style="text-align: center;">
                <div class="tile-stats tile-white tile-white-primary">
                    <span style="text-align: center;font-size: 32px;">WA TECHNICAL INSTITUTE</span>
                    <br/>
                    <span style="text-align: center;font-size: 20px;">P. O. Box 238, Wa, Upper West Region</span>
                    <br/>
                    <span style="text-align: center;font-size: 26px;">TERMINAL REPORT</span>
                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-1" style="text-align: center;"><h4>NAME</h4></div>
            <div class="col-md-7" style="border-bottom: 1px dotted #D2CBCB;text-align: center;height: 30px;"><h4><?php echo $row['name'];?></h4></div>
            <div class="col-md-2" style="text-align: center;"><h4>CLASS/YEAR</h4></div>
            <div class="col-md-2" style="border-bottom: 1px dotted #D2CBCB;text-align: center;height: 30px;"><h4><?php
             $class_name = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
             echo $class_name;?></h4></div>
        </div>
        <div class="row">
            <div class="col-md-1" style="text-align: center;"><h4>COURSE</h4></div>
            <div class="col-md-1" style="text-align: left;"><h4></h4></div>
            <div class="col-md-10" style="border-bottom: 1px dotted #D2CBCB;text-align: center;height: 30px;margin-left: -40px;"><h4></h4></div>
        </div>
        <div class="row">
            <div class="col-md-1" style="text-align: center;"><h4>TERM</h4></div>
            <div class="col-md-3" style="border-bottom: 1px dotted #D2CBCB;text-align: center;height: 30px;"><h4></h4></div>
            <div class="col-md-2" style="text-align: center;"><h4>NO. ON ROLL</h4></div>
            <div class="col-md-2" style="border-bottom: 1px dotted #D2CBCB;text-align: center;height: 30px;"><h4><?php echo $student_id;?></h4></div>
            <div class="col-md-1" style="text-align: center;"><h4>DATE</h4></div>
            <div class="col-md-3" style="border-bottom: 1px dotted #D2CBCB;text-align: center;height: 30px;"><h4></h4></div>
        </div>
        <br/>
        <br/>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td style="text-align: center;vertical-align: middle;">
                                <h4><?php echo get_phrase('SUBJECTS');?></h4>
                            </td>
                            <td style="text-align: center;vertical-align: middle;">
                                <h5>CLASS<br/>SCORE<br/>30%</h5>
                            </td>
                            <td style="text-align: center;vertical-align: middle;">
                                <h5>EXAMS<br/>SCORE<br/>70%</h5>
                            </td>
                            <td style="text-align: center;vertical-align: middle;">
                                <h5>TOTAL<br/>SCORE<br/>100%</h5>
                            </td>
                            <td style="text-align: center;vertical-align: middle;">
                                <h5>GRADE<br/>OBTAINED</h5>
                            </td>
                            <td style="text-align: center;vertical-align: middle;">
                                <h5>POSITION<br/>IN<br/>SUBJECT</h5>
                            </td>
                            <td style="text-align: center;vertical-align: middle;">
                                <h5>ATTENDANCE</h5>
                            </td>
                            <td style="text-align: center;vertical-align: middle;">
                                <h5>REMARKS<br/>SIGNATURES</h5>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $subjects = $this->db->get_where('subject' , array('class_id' => $class_id))->result_array();
                        foreach($subjects as $row):
                    ?>
                        <tr>
                            <td style="text-align: center;"><h5><?php echo $row['name'];?></h5></td>
                        <?php
                            $obtained_mark_query =  $this->db->get_where('mark' , array(
                                                            'class_id' => $class_id , 
                                                                'exam_id' => $exam_id , 
                                                                    'subject_id' => $row['subject_id'] , 
                                                                        'student_id' => $student_id
                                                        ));
                            if ( $obtained_mark_query->num_rows() > 0) {
                                $obtained_marks = $obtained_mark_query->row()->mark_obtained;
                                $obtained_class_score = $obtained_mark_query->row()->class_score;
                                $total_marks += $obtained_marks;
                                $total_class_score += $obtained_class_score;
                                if ($obtained_marks >= 0 && $obtained_marks != '') {
                                    $grade = $this->crud_model->get_grade($obtained_marks);
                                    $grade_point = $grade['grade_point'];
                                    $total_grade_point += $grade_point;
                                }
                            }
                        ?>
                            <td style="text-align: center;"><h5><?php echo $obtained_class_score;?></h5></td>
                            <td style="text-align: center;"><h5><?php echo $obtained_marks;?></h5></td>
                            <td style="text-align: center;"><h5><?php echo ($obtained_marks + $obtained_class_score);?></h5></td>
                            <td style="text-align: center;"><h5><?php if($obtained_marks != "0") echo $grade_point;?></h5></td>
                            <td style="text-align: center;"><h5></h5></td>
                        <?php
                            $exam = $this->db->get_where('exam' , array('exam_id' => $exam_id))->row();
                            $full_date = $exam->date;
                            $date = explode("/", $full_date);
                            $full_date = $date[2]."-".$date[0]."-".$date[1];
                            $verify_data    =   array(  'student_id' => $student_id,
                                                        'date' => $full_date);
                            $attendance = $this->db->get_where('attendance' , $verify_data)->row();
                            $status     = $attendance->status;
                            ?>
                            <td style="text-align: center;"><h5>
                                <?php if ($status == "1"):?>
                                    <i>P</i>
                                <?php endif;?>
                                <?php if ($status == "2"):?>
                                    <i>A</i>
                                <?php endif;?></h5>
                            </td>
                            <td style="text-align: center;"><h5></h5></td>    
                        <?php endforeach;?>
                        <tr>
                            <td style="text-align: center;">
                                <h4><?php echo get_phrase('TOTALS: ');?></h4>
                            </td>
                            <td style="text-align: center;">
                                <h5><?php echo $total_class_score;?></h5>
                            </td>
                            <td style="text-align: center;">
                                <h5><?php echo $total_marks;?></h5>
                            </td>
                            <td style="text-align: center;">
                                <h5><?php echo ($total_marks + $total_class_score);?></h5>
                            </td>
                            <td style="text-align: center;">
                                <h5><?php if($total_grade_point != "0") echo $total_grade_point;?></h5>
                            </td>
                            <td style="text-align: center;">
                               <h5></h5> 
                            </td>
                            <td style="text-align: center;">
                                <h5>
                                <?php if ($status == "1"):?>
                                    <i>P</i>
                                <?php endif;?>
                                <?php if ($status == "2"):?>
                                    <i>A</i>
                                <?php endif;?></h5>
                            </td>
                            <td style="text-align: center;">
                                <h5></h5>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row" style="border-bottom: 1px dotted #D2CBCB;text-align: center;height: 40px;">
            <div class="col-md-2" style="margin-top: -15px;text-align: left"><h3>GRADES: </h3></div>
            <div class="col-md-1"></div>
            <div class="col-md-9" style="margin-top: -7px;">
                <div class="col-md-2">
                    <div>50 - 59</div>
                    <div>60 - 69</div>
                </div>
                <div class="col-md-2">
                    <div>PASS</div>
                    <div>CREDIT</div>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-2">
                    <div>70 - 79</div>
                    <div>80 - 100</div>
                </div>
                <div class="col-md-2">
                    <div>GOOD</div>
                    <div>DISTINCTION</div>
                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-3" style="text-align: left;"><h5>CLASS TEATHER'S COMMENTS:</h5></div>
            <div class="col-md-9" style="border-bottom: 1px dotted #D2CBCB;text-align: left;height: 23px;margin-left: -23px;"><h5></h5></div>
        </div>
        <div class="row">
            <div class="col-md-3" style="text-align: left;"><h5>REMARKS BY COURSE OFFICER:</h5></div>
            <div class="col-md-9" style="border-bottom: 1px dotted #D2CBCB;text-align: left;height: 23px;margin-left: -23px;"><h5></h5></div>
        </div>
        <div class="row">
            <div class="col-md-2" style="text-align: left;"><h5>CONDUCT:</h5></div>
            <div class="col-md-10" style="border-bottom: 1px dotted #D2CBCB;text-align: left;height: 23px;margin-left: -23px;"><h5></h5></div>
        </div>
        <div class="row">
            <div class="col-md-2" style="text-align: left;"><h5>PRINCIPAL'S REPORT:</h5></div>
            <div class="col-md-10" style="border-bottom: 1px dotted #D2CBCB;text-align: left;height: 23px;margin-left: -23px;"><h5></h5></div>
        </div>
        <div class="row">
            <div class="col-md-2" style="text-align: left;"><h5>FEES DUE:</h5></div>
            <div class="col-md-10" style="border-bottom: 1px dotted #D2CBCB;text-align: left;height: 23px;margin-left: -23px;"><h5></h5></div>
        </div>
        <div class="row">
            <div class="col-md-2" style="text-align: left;"><h5>NEXT TERM BEGINS:</h5></div>
            <div class="col-md-10" style="border-bottom: 1px dotted #D2CBCB;text-align: left;height: 23px;margin-left: -23px;"><h5></h5></div>
        </div>

    </div>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <?php endforeach;?>
    </div>
</body>
<script type="text/javascript" src="js/html2canvas.min.js"></script>
<script type="text/javascript" src="js/jspdf.min.js"></script>
<script type="text/javascript">
    var pages = $('.print');
    var doc = new jsPDF();
    var j = 0;
    for (var i = 0 ; i < pages.length; i++) {
        html2canvas(pages[i]).then(function(canvas) {
        var img=canvas.toDataURL("image/png");
        // debugger;
        var height =  canvas.height / 440 * 80;
        doc.addImage(img,'JPEG',10,0,190,height);
        if (j < (pages.length - 1) ) doc.addPage();
        if (j == (pages.length - 1) ) {doc.save('Report.pdf');}
        j++;
        });
    }
    
</script>
</html>
