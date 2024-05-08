<?php

class Ds8_Simple_User_Profile {

    function __construct() {
        add_action('init', array($this, 'init'));
    }

    public function init(){
      // Custom Profile Image
      add_action('show_user_profile', array($this, 'add_profile_image'), 9, 1);
      add_action('edit_user_profile', array($this, 'add_profile_image'), 9, 1);

      add_action('personal_options_update', array($this, 'save_user_profile'));
      add_action('edit_user_profile_update', array($this, 'save_user_profile'));

      // Allow HTML in user description.
      remove_filter('pre_user_description', 'wp_filter_kses');
      add_filter('pre_user_description', 'wp_kses_post');
    }


    public function add_profile_image($user) {

        if (!current_user_can('upload_files')) {
            return;
        }

        $default_url = DS8_AUTHOR_BOX_ASSETS . 'img/default.png';
        $image       = get_user_meta($user->ID, 'ds8box-profile-image', true);
        $image_id    = get_user_meta($user->ID, 'ds8box-profile-image-id', true);

        ?>

        <div id="ds8box-custom-profile-image">
            <h3><?php esc_html_e('Custom User Profile Image (Simple Author Box)', 'simple-author-box'); ?></h3>
            <table class="form-table">
                <tr>
                    <th><label for="cupp_meta"><?php esc_html_e('Profile Image', 'simple-author-box'); ?></label></th>
                    <td>
                        <div id="sab-current-image">
                            <?php wp_nonce_field('ds8box-profile-image', 'ds8box-profile-nonce'); ?>
                            <img data-default="<?php echo esc_url_raw($default_url); ?>"
                                 src="<?php echo '' != $image ? esc_url_raw($image) : esc_url_raw($default_url); ?>"><br>
                            <input type="text" name="ds8box-custom-image" id="ds8box-custom-image" class="regular-text"
                                   value="<?php echo esc_attr($image); ?>">
                            
                            <input type="hidden" name="ds8box-custom-image-id" id="ds8box-custom-image-id" class="regular-text"
                                   value="<?php echo esc_attr($image_id); ?>">
                        </div>
                        <div class="actions">
                            <a href="#" class="button-secondary"
                               id="ds8box-remove-image"><?php esc_html_e('Remove Image', 'simple-author-box'); ?></a>
                            <a href="#" class="button-primary"
                               id="ds8box-add-image"><?php esc_html_e('Upload Image', 'simple-author-box'); ?></a>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <?php
    }

    public function save_user_profile($user_id) {

        if (!isset($_POST['ds8box-profile-nonce']) || !wp_verify_nonce($_POST['ds8box-profile-nonce'], 'ds8box-profile-image')) {
            return;
        }

        if (!current_user_can('upload_files', $user_id)) {
            return;
        }

        if (isset($_POST['ds8box-custom-image']) && '' != $_POST['ds8box-custom-image']) {
            // FEATURE 05052024
            // Get the path to the download directory.
            $wp_upload_dir = wp_upload_dir();
            
            $base = rtrim(ABSPATH, '/');
            $parsed = parse_url($_POST['ds8box-custom-image']);
            $pathimg = $base.$parsed['path'];
            if (file_exists($pathimg)){
                $path_parts = pathinfo($pathimg);
                $file = $path_parts['filename'].'.'.$path_parts['extension'];
            }
            
            if ($_POST['ds8box-custom-image-id'] != 0){
              $attach_id = $_POST['ds8box-custom-image-id'];
              $metadata = wp_generate_attachment_metadata( $attach_id, $pathimg );
              wp_update_attachment_metadata( $attach_id, $metadata );
            }
            
            $image = wp_get_image_editor($base.$parsed['path']);
            if ( ! is_wp_error( $image ) ) {
              $image->resize( 65, 65, false );
              $image->save( $path_parts['dirname'].'/'.$path_parts['filename'].'-65x65.'.$path_parts['extension']);
            }
          
          
            update_user_meta($user_id, 'ds8box-profile-image', esc_url_raw($_POST['ds8box-custom-image']));
            update_user_meta($user_id, 'ds8box-profile-image-id', $_POST['ds8box-custom-image-id']);
        } else {
            delete_user_meta($user_id, 'ds8box-profile-image');
            delete_user_meta($user_id, 'ds8box-profile-image-id');
        }

    }

}

new Ds8_Simple_User_Profile();
