<hr>

 <div class="panel panel-gradient" >
            
                <div class="panel-heading">
                    <div class="panel-title">
					 <?php echo get_phrase('ID_card_information_page'); ?>
					</div>
					</div>
<div class="table-responsive">
<br>
           &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_hostel_add/');" 
            	class="btn btn-primary ">
                <i class="entypo-plus-circled"></i>
            	<?php echo get_phrase('add_new_hostel_manager');?>
                </a> 
                <br><br><br>
               <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            <th width="80"><div><?php echo get_phrase('photo');?></div></th>
                            <th><div><?php echo get_phrase('name');?></div></th>
                            <th><div><?php echo get_phrase('sex');?></div></th>
                            <th><div><?php echo get_phrase('email');?></div></th>
                            <th><div><?php echo get_phrase('address');?></div></th>
                            <th><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $hostel_id_card	=	$this->db->get('hostel' )->result_array();
                                foreach($hostel_id_card as $row):?>
                        <tr>
                            <td><img src="<?php echo $this->crud_model->get_image_url('hostel',$row['hostel_id']);?>" class="img-circle" width="30" /></td>
                            <td><?php echo $row['name'];?></td>
                            <td><?php echo $row['sex'];?></td>
                            <td><?php echo $row['email'];?></td>
                            <td><?php echo $row['address'];?></td>
                            <td>
                                
                           <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                                       Generate ID Cards <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        
                                        <!-- teacher EDITING LINK -->
                                        <li>
                                        	<a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_id_card/<?php echo $row['hostel_id'];?>');">
                                            	<i class="entypo-pencil"></i>
													<?php echo get_phrase('genereate_ID_cards');?>
                                               	</a>
                                        				</li>
                                        <li class="divider"></li>
                                        
                                        <!-- teacher DELETION LINK -->
                                        <li>
                                         <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/hostel_id_card/delete/<?php echo $row['hostel_id'];?>');">
                                            	<i class="entypo-trash"></i>
													<?php echo get_phrase('delete_hostel_manager');?>
                                               	</a>
                                        				</li>
                                    </ul>
                                </div>							
                            </td>
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
		

		var datatable = $("#table_export").dataTable({
			"sPaginationType": "bootstrap",
			"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
			"oTableTools": {
				"aButtons": [
					
					{
						"sExtends": "xls",
						"mColumns": [1,2]
					},
					{
						"sExtends": "pdf",
						"mColumns": [1,2]
					},
					{
						"sExtends": "print",
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(0, false);
							datatable.fnSetColumnVis(3, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(0, true);
									  datatable.fnSetColumnVis(3, true);
								  }
							});
						},
						
					},
				]
			},
			
		});
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
		
</script>

