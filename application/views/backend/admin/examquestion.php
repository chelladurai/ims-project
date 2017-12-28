<hr> 
<div class="panel panel-gradient" >
            
                <div class="panel-heading">
                    <div class="panel-title">
					 <?php echo get_phrase('exam_information_page'); ?>
					</div>
					</div>
<br>
<div class="table-responsive">
&nbsp;&nbsp;&nbsp;&nbsp;<button onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_examquestion_add');" 
    class="btn btn-primary">
        <?php echo get_phrase('add_examquestion'); ?>
</button>
<br><br>
<table class="table table-bordered table-striped datatable" id="table-2">
    <thead>
        <tr>
            <th>#</th>
            <th><?php echo get_phrase('date');?></th>
            <th><?php echo get_phrase('name');?></th>
            <th><?php echo get_phrase('subject');?></th>
            <th><?php echo get_phrase('description');?></th>
            <th><?php echo get_phrase('class');?></th>
            <th><?php echo get_phrase('download');?></th>
            <th><?php echo get_phrase('status');?></th>
            <th><?php echo get_phrase('options');?></th>
        </tr>
    </thead>

    <tbody>
        <?php 
                                $examquestions	=	$this->db->get('examquestion' )->result_array();
                                foreach($examquestions as $row):?>
            <tr>
                <td><?php echo $row['examquestion_id']?></td>
                <td><?php echo $row['timestamp']; ?></td>
                <td><?php echo $row['name']?></td>
                <td><?php echo $row['title']?></td>
                <td><?php echo $row['description']?></td>
                <td>
                    <?php $name = $this->db->get_where('class' , array('class_id' => $row['class_id'] ))->row()->name;
                     echo $name;?>
                </td>
                <td>
                    <a href="<?php echo base_url().'uploads/examquestion/'.$row['file_name']; ?>" class="btn btn-blue btn-icon icon-left">
                        <i class="entypo-download"></i>
                        Download
                    </a>
                </td>
			<td>
											  <span class="label label-<?php if($row['status']=='Approved')echo 'success'; elseif ($row['status']=='Disapproved') echo 'danger'; else echo 'warning';?>"><?php echo $row['status'];?></span>

			</td>

                <td>
                    <a  onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_examquestion_edit/<?php echo $row['examquestion_id']?>');" 
                        class="btn btn-info btn-sm btn-icon icon-left">
                            <i class="entypo-pencil"></i>
                            Edit
                    </a>
                    <a href="<?php echo base_url();?>index.php?admin/examquestion/delete/<?php echo $row['examquestion_id']?>" 
                        class="btn btn-danger btn-sm btn-icon icon-left" onclick="return confirm('Are you sure to delete?');">
                            <i class="entypo-cancel"></i>
                            Delete
                    </a>
                </td>
            </tr>
               <?php endforeach;?>
    </tbody>
</table>
</div>
</div>
<script type="text/javascript">
    jQuery(window).load(function ()
    {
        var $ = jQuery;

        $("#table-2").dataTable({
            "sPaginationType": "bootstrap",
            "sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>"
        });

        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });

        // Highlighted rows
        $("#table-2 tbody input[type=checkbox]").each(function (i, el)
        {
            var $this = $(el),
                    $p = $this.closest('tr');

            $(el).on('change', function ()
            {
                var is_checked = $this.is(':checked');

                $p[is_checked ? 'addClass' : 'removeClass']('highlight');
            });
        });

        // Replace Checboxes
        $(".pagination a").click(function (ev)
        {
            replaceCheckboxes();
        });
    });
</script>