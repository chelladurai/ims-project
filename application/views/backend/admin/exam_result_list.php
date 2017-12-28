<hr>
<div class="row">
    <div class="col-md-12">
        <!------CONTROL TABS START------>

        <!------CONTROL TABS END------>

<div class="panel panel-gradient" >
            
                <div class="panel-heading">
                    <div class="panel-title">
                        <?php echo get_phrase('exam_result');?>
                    </div>
                </div>
                <div class="panel-body">            <!----TABLE LISTING STARTS-->
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="col-md-2 text-right" style="margin-top: 5px;"><?php echo get_phrase('class'); ?>:</label>
                        <div class="col-md-6">
                            <select id="class_id" class="form-control" onchange="get_class_subject()">
                                <?php foreach ($classes as $class) { ?>
                                    <option value="<?php echo $class['class_id'] ?>"><?php echo $class['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="col-md-2 text-right" style="margin-top: 5px;"><?php echo get_phrase('subject'); ?>:</label>
                        <div class="col-md-6">
                            <select id="subject_id" class="form-control" onchange="getExamList()">
                                <?php foreach ($subjects as $subject) { ?>
                                    <option value="<?php echo $subject['subject_id'] ?>"><?php echo $subject['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            <th class="num"><div class="num">#</div></th>
                            <th><div><?php echo get_phrase('class'); ?></div></th>
                            <th><div><?php echo get_phrase('student'); ?></div></th>
                            <th><div><?php echo get_phrase('subject'); ?></div></th>
                            <th><div><?php echo get_phrase('exam_date'); ?></div></th>
                            <th><div><?php echo get_phrase('session'); ?></div></th>
                            <th><div><?php echo get_phrase('marks'); ?></div></th>
                            <th><div><?php echo get_phrase('options'); ?></div></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <!----TABLE LISTING ENDS--->

        </div>
    </div>
</div>
<form id="form1" action="<?php echo base_url(); ?>index.php?admin/exam_result_detail/" method="post">
    <input type="hidden" name="class_id" />
    <input type="hidden" name="subject_id" />
    <input type="hidden" name="student_id" />
    <input type="hidden" name="date" />
    <input type="hidden" name="duration" />
    <input type="hidden" name="session" />
</form>
<script>
    $(function () {
        get_class_subject();
    });
    function get_class_subject() {
        var class_id = $('#class_id :selected').val();
        $.ajax({
            url: '<?php echo base_url(); ?>index.php?admin/get_class_subject/' + class_id,
            async: false,
            success: function (response)
            {
                $('#subject_id').html(response);
                getExamList();
            }
        });
    }

    function getExamList() {
        var class_id = $('#class_id :selected').val();
        var subject_id = $('#subject_id :selected').val();
        $.ajax({
            url: '<?php echo base_url(); ?>index.php?admin/exam_result_list',
            async: false,
            method: 'post',
            data: {
                mode: 'get_list',
                class_id: class_id,
                subject_id: subject_id
            },
            success: function (result_list)
            {
                result_list = JSON.parse(result_list);
                console.log(result_list);
                var htmltext = '';
                for (var i = 0; i < result_list.length; i++) {
                    htmltext += '<tr>' +
                            '<td class="num"><div class="num">' + Number(i + 1) + '</div></td>' +
                            '<td><div>' + result_list[i]["class"] + '</div></td>' +
                            '<td><div>' + result_list[i]["student"] + '</div></td>' +
                            '<td><div>' + result_list[i]["subject"] + '</div></td>' +
                            '<td><div>' + result_list[i]["date"] + '</div></td>' +
                            '<td><div>' + result_list[i]["session"] + '</div></td>' +
                            '<td><div>' + result_list[i]["marks"] + ' / ' + result_list[i]["question_count"] + '</div></td>' +
                            '<td><div><button class="btn btn-blue" onclick="viewDetail(\'' + result_list[i]["class_id"] + '\',\'' + result_list[i]["subject_id"] + '\',\'' + result_list[i]["student_id"] + '\',\'' + result_list[i]["duration"] + '\',\'' + result_list[i]["session"] + '\',\'' + result_list[i]["date"] + '\')"><?php echo get_phrase('view_detail') ?></button></div></td>' +
                            '</tr>';
                }
                $('#table_export tbody').html(htmltext);
            }
        });
    }

    function viewDetail(class_id, subject_id, student_id, duration, session, date) {
        $('#form1 input[name=class_id]').val(class_id);
        $('#form1 input[name=subject_id]').val(subject_id);
        $('#form1 input[name=student_id]').val(student_id);
        $('#form1 input[name=duration]').val(duration);
        $('#form1 input[name=session]').val(session);
        $('#form1 input[name=date]').val(date);
        $('#form1').submit();
    }
</script>