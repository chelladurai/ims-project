
<hr>

 <div class="panel panel-gradient" >
            
                <div class="panel-heading">
                    <div class="panel-title">
					 <?php echo get_phrase('loan_information_page'); ?>
					</div>
					</div>
<br>
           &nbsp;&nbsp;&nbsp;&nbsp;<button onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_loan_add');" 
    class="btn btn-primary">
        <?php echo get_phrase('add_loan_applicant'); ?>
</button>
<div style="clear:both;"></div>
<br>
<div class="table-responsive">

<table class="table table-bordered table-striped datatable" id="table-2">
    <thead>
        <tr>
            <th>#</th>
            <th><?php echo get_phrase('date');?></th>
            <th><?php echo get_phrase('staff_name');?></th>
            <th><?php echo get_phrase('amount');?></th>

            <th><?php echo get_phrase('purpose');?></th>
            <th><?php echo get_phrase('loan_duration');?></th>
            <th><?php echo get_phrase('mode_of_payment');?></th>
			
			<th><?php echo get_phrase('guarantor_name');?></th>
            <th><?php echo get_phrase('number');?></th>
            <th><?php echo get_phrase('collateral_name');?></th>
            <th><?php echo get_phrase('colateral_value');?></th>
            <th><?php echo get_phrase('status');?></th>
            <th><?php echo get_phrase('download_files');?></th>
        </tr>
    </thead>

    <tbody>
        <?php 
                                $loan	=	$this->db->get('loan' )->result_array();
                                foreach($loan as $row):?>
            <tr>
                <td><?php echo $row['loan_id']?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['staff_name']?></td>
                <td><?php echo $row['amount']?></td>
                <td><?php echo $row['purpose']?></td> 
				
                <td><?php echo $row['l_duration']; ?></td>
                <td><?php echo $row['mop']?></td>
                <td><?php echo $row['g_name']?></td>
				
				 <td><?php echo $row['g_number']?></td>
                <td><?php echo $row['c_name']; ?></td>
								
				 <td><?php echo $row['value']?></td>
                <td>
<span class="label label-<?php if($status =='Approved')echo 'success'; elseif($status =='Disapproved')echo 'danger'; else echo 'warning';?>"><?php echo $row['status']?></span>				
				</td>
                <td>
                    <a href="<?php echo base_url().'uploads/loan_applicant/'.$row['file_name']; ?>" class="btn btn-blue btn-icon icon-left">
                        <i class="entypo-download"></i>
                        Download
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