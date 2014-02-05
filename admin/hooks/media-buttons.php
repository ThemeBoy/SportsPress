<?php
function sportspress_media_buttons() {

	//if ( ! in_array( array('post.php', 'page.php', 'page-new.php', 'post-new.php')

	?>
	<a href="#TB_inline?width=480&inlineId=select_sp_table" class="thickbox button sp-insert sp-insert-map" id="add_gform" title="<?php printf( __( 'Add %s', 'sportspress' ), __( 'Map', 'sportspress' ) ); ?>"><span class="sp-buttons-icon sp-map-buttons-icon "></span> <?php printf( __( 'Add %s', 'sportspress' ), __( 'Map', 'sportspress' ) ); ?></a>
	<a href="#TB_inline?width=480&inlineId=select_sp_table" class="thickbox button sp-insert sp-insert-table" id="add_gform" title="<?php printf( __( 'Add %s', 'sportspress' ), __( 'League Table', 'sportspress' ) ); ?>"><span class="sp-buttons-icon sp-table-buttons-icon "></span> <?php printf( __( 'Add %s', 'sportspress' ), __( 'League Table', 'sportspress' ) ); ?></a>
	<?php
}
add_action( 'media_buttons', 'sportspress_media_buttons', 20 );

/*
//Action target that displays the popup to insert a form to a post/page
public static function add_mce_popup(){
    ?>
    <script>
        function InsertForm(){
            var form_id = jQuery("#add_form_id").val();
            if(form_id == ""){
                alert("<?php _e("Please select a form", "gravityforms") ?>");
                return;
            }

            var form_name = jQuery("#add_form_id option[value='" + form_id + "']").text().replace(/[\[\]]/g, '');
            var display_title = jQuery("#display_title").is(":checked");
            var display_description = jQuery("#display_description").is(":checked");
            var ajax = jQuery("#gform_ajax").is(":checked");
            var title_qs = !display_title ? " title=\"false\"" : "";
            var description_qs = !display_description ? " description=\"false\"" : "";
            var ajax_qs = ajax ? " ajax=\"true\"" : "";

            window.send_to_editor("[gravityform id=\"" + form_id + "\" name=\"" + form_name + "\"" + title_qs + description_qs + ajax_qs + "]");
        }
    </script>

    <div id="select_gravity_form" style="display:none;">
        <div class="wrap <?php echo GFCommon::get_browser_class() ?>">
            <div>
                <div style="padding:15px 15px 0 15px;">
                    <h3 style="color:#5A5A5A!important; font-family:Georgia,Times New Roman,Times,serif!important; font-size:1.8em!important; font-weight:normal!important;"><?php _e("Insert A Form", "gravityforms"); ?></h3>
                    <span>
                        <?php _e("Select a form below to add it to your post or page.", "gravityforms"); ?>
                    </span>
                </div>
                <div style="padding:15px 15px 0 15px;">
                    <select id="add_form_id">
                        <option value="">  <?php _e("Select a Form", "gravityforms"); ?>  </option>
                        <?php
                            $forms = RGFormsModel::get_forms(1, "title");
                            foreach($forms as $form){
                                ?>
                                <option value="<?php echo absint($form->id) ?>"><?php echo esc_html($form->title) ?></option>
                                <?php
                            }
                        ?>
                    </select> <br/>
                    <div style="padding:8px 0 0 0; font-size:11px; font-style:italic; color:#5A5A5A"><?php _e("Can't find your form? Make sure it is active.", "gravityforms"); ?></div>
                </div>
                <div style="padding:15px 15px 0 15px;">
                    <input type="checkbox" id="display_title" checked='checked' /> <label for="display_title"><?php _e("Display form title", "gravityforms"); ?></label> &nbsp;&nbsp;&nbsp;
                    <input type="checkbox" id="display_description" checked='checked' /> <label for="display_description"><?php _e("Display form description", "gravityforms"); ?></label>&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" id="gform_ajax" /> <label for="gform_ajax"><?php _e("Enable AJAX", "gravityforms"); ?></label>
                </div>
                <div style="padding:15px;">
                    <input type="button" class="button-primary" value="<?php _e("Insert Form", "gravityforms"); ?>" onclick="InsertForm();"/>&nbsp;&nbsp;&nbsp;
                <a class="button" style="color:#bbb;" href="#" onclick="tb_remove(); return false;"><?php _e("Cancel", "gravityforms"); ?></a>
                </div>
            </div>
        </div>
    </div>

    <?php
}
*/