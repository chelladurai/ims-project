<hr> 
<div class="panel panel-gradient" >
            
                <div class="panel-heading">
                    <div class="panel-title">
					 <?php echo get_phrase('todays_thoughts_information_page'); ?>
					</div>
					</div>
<div class="table-responsive">
    
    	
		<div class="tab-content">
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane box active" id="list">
                <table class="table table-bordered datatable" id="table_export">
                	<thead>
                		<tr>
                    		<th><div>#</div></th>
                    		<th><div><?php echo get_phrase('thought');?></div></th>
                    		
						</tr>
					</thead>
                    <tbody>
                    <?php $count = 1;foreach($todays_thoughts as $row):?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td><?php echo $row['thought'];?></td>
							
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
			</div>
            <!----TABLE LISTING ENDS-->
            
            
			
            
		</div>
	</div>
</div>