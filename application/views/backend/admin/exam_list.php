<div class="row">
    <div class="col-md-12">
        <!------CONTROL TABS END------>
<hr>
            <!----TABLE LISTING STARTS-->
<div class="panel panel-gradient" >
            
                <div class="panel-heading">
                    <div class="panel-title">
                        <?php echo get_phrase('list_all_exams');?>
                    </div>
                </div>
                <div class="panel-body">
                <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            <th><div>#</div></th>
                            <th><div><?php echo get_phrase('class_name'); ?></div></th>
                            <th><div><?php echo get_phrase('subject_name'); ?></div></th>
                            <th><div><?php echo get_phrase('session'); ?></div></th>
                            <th><div><?php echo get_phrase('duration'); ?></div></th>
                            <th><div><?php echo get_phrase('exam_date'); ?></div></th>
                            <th><div><?php echo get_phrase('options'); ?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        foreach ($question_data as $row):
                            ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo $row->class_name ?></td>
                                <td><?php echo $row->subject_name ?></td>
                                <td><?php echo $row->session ?></td>
                                <td><?php echo $row->duration ?></td>
                                <td><?php echo $row->date ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-blue btn-sm dropdown-toggle" data-toggle="dropdown">
                                            Action <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-primary pull-right" role="menu">
                                            <!-- DELETION LINK -->
                                            <li>
                                                <a href="#" onclick="confirm_modal('<?php echo base_url(); ?>index.php?admin/exam_list/<?php echo $row->class_id . '/' . $row->subject_id . '/' . $row->duration . '/' . $row->date . '/' . ($row->session == '' ? '%null' : $row->session) ?>/delete');">
                                                    <i class="entypo-trash"></i>
                                                    <?php echo get_phrase('delete'); ?>
                                                </a>
                                            </li>
                                            <li class="divider"></li>

                                            <li>
                                                <a href="#" onclick="viewExam('<?php echo $row->class_id ?>', '<?php echo $row->subject_id ?>', '<?php echo $row->session ?>', '<?php echo $row->duration ?>', '<?php echo $row->date ?>')">
                                                    <i class="entypo-right-circled"></i>
                                                    <?php echo get_phrase('view_exam'); ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!----TABLE LISTING ENDS--->
        </div>
		
    </div>
</div>



<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">

    jQuery(document).ready(function ($)
    {


        var datatable = $("#table_export").dataTable();

        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });
    });

    function viewExam(class_id, subject_id, session, duration, date) {
        location.href = '<?php echo base_url(); ?>index.php?admin/exam_view/' + class_id + '/' + subject_id + '/' + duration + '/' + date + '/' + session;
    }

</script>