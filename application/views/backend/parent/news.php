<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------>
                <table  class="table table-bordered datatable" id="table_export">
                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('news_title');?></div></th>
                    		<th><div><?php echo get_phrase('news_content');?></div></th>
                    		<th><div><?php echo get_phrase('date');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php foreach($news as $row):?>
                        <tr>
							<td><?php echo $row['news_title'];?></td>
							<td><?php echo $row['news_content'];?></td>
							<td><?php echo $row['date'];?></td>
							
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
			</div>
			
</div>


<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		

		var datatable = $("#table_export").dataTable();
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
		
</script>