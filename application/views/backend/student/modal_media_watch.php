<?php 
$class_info                 = $this->db->get('class')->result_array();
$single_study_material_info = $this->db->get_where('media', array('media_id' => $param2))->result_array();
foreach ($single_study_material_info as $row) {
?>
    <div class="row">
        <div class="col-md-12">


                <div class="panel-heading">
                    <div class="panel-title">
                        <h3 style="color:#FF0000"><?php echo get_phrase('Watching_media'); ?></h3>
                    </div>
                </div>

                <div class="panel-body">
				<?php echo $row['mlink']; ?>


            </div>

        </div>
    </div>
<?php } ?>