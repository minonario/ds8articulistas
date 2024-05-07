<?php

if (!defined('ABSPATH')) exit;

class DS8Articulistas {
  
        private static $instance = null;
        public $version;
        public $themes;
        /**
         * Function constructor
         */
        function __construct() {
            $this->load_dependencies();
            $this->define_admin_hooks();
            
            add_action('widgets_init', array($this, 'ds8_columnist_register_widget'));
            
            add_action('admin_enqueue_scripts', array($this, 'ds8_selectively_enqueue_admin_script'), 10 );
            add_action('wp_enqueue_scripts', array($this, 'ds8_articulistas_javascript'), 10);
            add_shortcode( 'ds8articulista', array($this, 'ds8articulista_shortcode_fn') );
            add_shortcode('simple-author-box-ds8', array($this, 'shortcode'));
            
            add_filter('author_template', array($this,'load_author_template'), 10, 1);
            add_filter('single_template', array($this,'load_single_template'), 10, 1);
        }
        
        /**
        * Singleton pattern
        *
        * @return void
        */
        public static function get_instance() {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }
        
        private function load_dependencies() {
            
            require_once DS8ARTICULISTAS_PLUGIN_DIR . 'includes/class-ds8-columnist-helper.php';
            require_once DS8ARTICULISTAS_PLUGIN_DIR . 'includes/class-ds8-articulista-widget.php';
          
            if (is_admin()) {
                require_once DS8ARTICULISTAS_PLUGIN_DIR . 'includes/class-ds8-profile-user-image.php';
            }
        }
        
        /**
          * Admin hooks
          *
          * @return void
          */
        private function define_admin_hooks() {
            add_filter('get_avatar', array($this, 'replace_gravatar_image'), 10, 6);
        }
        
        public function ds8_columnist_register_widget() {
          register_widget('DS8_Columnist_Widget');
        }

        /**
        * See this: https://codex.wordpress.org/Plugin_API/Filter_Reference/get_avatar
        *
        * Custom function to overwrite WordPress's get_avatar function
        *
        * @param [type] $avatar
        * @param [type] $id_or_email
        * @param [type] $size
        * @param [type] $default
        * @param [type] $alt
        * @param [type] $args
        *
        * @return void
        */
        public function replace_gravatar_image($avatar, $id_or_email, $size, $default, $alt, $args = array()) {
            // Process the user identifier.
            $user = false;
            if (is_numeric($id_or_email)) {
                $user = get_user_by('id', absint($id_or_email));
            } elseif (is_string($id_or_email)) {

                $user = get_user_by('email', $id_or_email);
            } elseif ($id_or_email instanceof WP_User) {
                // User Object
                $user = $id_or_email;
            } elseif ($id_or_email instanceof WP_Post) {
                // Post Object
                $user = get_user_by('id', (int) $id_or_email->post_author);
            } elseif ($id_or_email instanceof WP_Comment) {

                if (!empty($id_or_email->user_id)) {
                    $user = get_user_by('id', (int) $id_or_email->user_id);
                }
            }

            if (!$user || is_wp_error($user)) {
                return $avatar;
            }

            $custom_profile_image = get_user_meta($user->ID, 'ds8box-profile-image', true);
            $custom_profile_image_id = get_user_meta($user->ID, 'ds8box-profile-image-id', true);
            $class                = array('avatar', 'avatar-' . (int) $args['size'], 'photo');

            // FEATURE 05052024
            //$image_ = wp_get_attachment_image_src($custom_profile_image_id, array(65,65));
            //$ts = wp_calculate_image_sizes(array(65,65), null, null, $custom_profile_image_id);
            
            /*if ($custom_profile_image_id != 0){
              $base = rtrim(ABSPATH, '/');
              $parsed = parse_url($custom_profile_image);
              $pathimg = $base.$parsed['path'];
              $metadata = wp_get_attachment_metadata($custom_profile_image_id);
              $metadata = wp_generate_attachment_metadata( $custom_profile_image_id, $pathimg );
              wp_update_attachment_metadata( $custom_profile_image_id, $metadata );
            }*/
            
            $imagen =  !is_author() ? wp_get_attachment_image( $custom_profile_image_id, 'articulista', false ) : null;
            
            if (!$args['found_avatar'] || $args['force_default']) {
                $class[] = 'avatar-default';
            }

            if ($args['class']) {
                if (is_array($args['class'])) {
                    $class = array_merge($class, $args['class']);
                } else {
                    $class[] = $args['class'];
                }
            }

            $class[] = 'sab-custom-avatar';

            if ('' !== $custom_profile_image && true !== $args['force_default']) {

                if ($imagen == null){
                $avatar = sprintf(
                    "<img alt='%s' src='%s' srcset='%s' class='%s' height='%d' width='%d' %s/>",
                    esc_attr($args['alt']),
                    esc_url($custom_profile_image),
                    esc_url($custom_profile_image) . ' 2x',
                    esc_attr(join(' ', $class)),
                    (int) $args['height'],
                    (int) $args['width'],
                    $args['extra_attr']
                );
                }else{
                  $avatar = $imagen;
                }
            }

            return $avatar;
        }
        
        /**
        * Enqueue a script in the WordPress admin user-edit.php.
        *
        * @param int $pagenow Hook suffix for the current admin page.
        */
        public function ds8_selectively_enqueue_admin_script( $hook ) {
             global $pagenow;
             if ($pagenow != 'user-edit.php') {
                 return;
             }
             wp_enqueue_media();
             wp_enqueue_script('media-upload');
             wp_enqueue_script('thickbox');
             wp_enqueue_style('thickbox');
             wp_register_script( 'profile-image', plugin_dir_url( __FILE__ ) .'/assets/js/profile-image.js', array('jquery-core'), '1.1', true );
             wp_enqueue_script( 'profile-image' );
             
             wp_enqueue_style('ds8boxplugin-admin-style', plugin_dir_url( __FILE__ ) . '/assets/css/admin-opinion.css');
        }
        
        public function shortcode($atts) {
            $defaults = array(
                'ids' => '',
            );

            $atts = wp_parse_args($atts, $defaults);

            if ('' != $atts['ids']) {


                if ('all' != $atts['ids']) {
                    $ids = explode(',', $atts['ids']);
                } else {
                    $ids = get_users(array('fields' => 'ID'));
                }

                ob_start();
                $sabox_options = DS8_Columnist_Helper::get_option('saboxplugin_options');
                if (!empty($ids)) {
                    foreach ($ids as $user_id) {

                        $template        = DS8_Columnist_Helper::get_template();
                        $sabox_author_id = $user_id;
                        echo '<div class="sabox-plus-item">';
                        include($template);
                        echo '</div>';
                    }
                }

                $html = ob_get_clean();
            } else {
                $html = wpsabox_author_box_ds8();
            }

            return $html;
        }
        
        public static function _prefix_get_users_by_post_date() {
            $args  = array(
                    'role'    => 'Contributor',
                    'number'  => -1 //change this to the number of users you want to get
            );
            $users = get_users( $args );

            $post_dates = array();
            if ( $users ) {
                    foreach ( $users as $user ) {
                            $ID                = $user->ID;
                            $posts             = get_posts( 'author=' . $ID );
                            $post_dates[ $ID ] = '';

                            if ( $posts ) {
                                    $post_dates[ $ID ] = $posts[0]->post_date;
                            }
                    }
            }

            //remove this line to order users by oldest post first
            arsort( $post_dates );

            $users = array();
            foreach ( $post_dates as $key => $value ) {
                    $users[] = get_userdata( $key );
            }

            return $users;
        }

        public static function get_custom_post_type_template( $archive_template ) {
             global $post;

             if ( is_post_type_archive ( 'articulista' ) ) {
                  $archive_template = dirname( __FILE__ ) . '/archive.php';
             }
             return $archive_template;
        }
        
        public static function load_author_template($template) {
            global $post;
            $author = get_user_by( 'slug', get_query_var( 'author_name' ) );
            $rol = in_array('contributor', $author->roles) ? 'contributor' : '';

            if ($post->post_type == "post" && $rol === 'contributor'){

                $plugin_path = plugin_dir_path( __FILE__ );
                $template_name = 'author.php';

                if($template === get_stylesheet_directory() . '/' . $template_name
                    || !file_exists($plugin_path . $template_name)) {

                    return $template;
                }
                return $plugin_path . $template_name;
            }

            return $template;
        }
        
        public static function load_single_template($template) {
            global $post;

            $author = get_userdata($post->post_author);
            $rol = in_array('contributor', $author->roles) ? 'contributor' : '';

            if ($post->post_type == "post" && $rol === 'contributor'){

                $plugin_path = plugin_dir_path( __FILE__ );
                $template_name = 'single.php';

                // A specific single template for my custom post type exists in theme folder? Or it also doesn't exist in my plugin?
                if($template === get_stylesheet_directory() . '/' . $template_name
                    || !file_exists($plugin_path . $template_name)) {

                    //Then return "single.php" or "single-my-custom-post-type.php" from theme directory.
                    return $template;
                }

                // If not, return my plugin custom post type template.
                return $plugin_path . $template_name;
            }

            return $template;
        }
        
        public static function load_cpt_template($template) {
            global $post;

            if ($post->post_type == "articulista"){

                $plugin_path = plugin_dir_path( __FILE__ );
                $template_name = 'singular.php';

                if($template === get_stylesheet_directory() . '/' . $template_name
                    || !file_exists($plugin_path . $template_name)) {

                    return $template;
                }

                return $plugin_path . $template_name;
            }

            return $template;
        }
        
        public function ds8articulista_shortcode_fn($atts) {
          
          if (is_admin()) return;
          
          extract( shortcode_atts( array(
              'type' => 'articulista',
              'perpage' => 3
          ), $atts ) );

          $users = self::_prefix_get_users_by_post_date();
          ob_start();
          include('template-parts/lista-articulistas.php');
          return ob_get_clean();
        }
        
        /**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since    1.0
	 */
	private static function set_locale() {
		load_plugin_textdomain( 'ds8articulista', false, plugin_dir_path( dirname( __FILE__ ) ) . '/languages/' );

	}
        
        public static function ds8articulista_textdomain( $mofile, $domain ) {
                if ( 'ds8articulista' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
                        $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
                        $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
                }
                return $mofile;
        }
        
        
        /**
	 * Check if plugin is active
	 *
	 * @since    1.0
	 */
	private static function is_plugin_active( $plugin_file ) {
		return in_array( $plugin_file, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
	}

        public function ds8_articulistas_javascript(){
          
            wp_enqueue_style('opinion-css', plugin_dir_url( __FILE__ ) . 'assets/css/opinion.css', array(), DS8ARTICULISTAS_VERSION);
            wp_enqueue_style('bootstrap-css', plugin_dir_url( __FILE__ ) . 'assets/css/bootstrap.min.css', array(), DS8ARTICULISTAS_VERSION);
            wp_enqueue_style('bootstrap-theme-css', plugin_dir_url( __FILE__ ) . 'assets/css/bootstrap-theme.css', array(), DS8ARTICULISTAS_VERSION);
            
            wp_register_script( 'tabs.js', plugin_dir_url( __FILE__ ) . 'assets/js/bootstrap.js', array('jquery'), DS8ARTICULISTAS_VERSION, true );
            wp_enqueue_script( 'tabs.js' );

        }

        public static function view( $name, array $args = array() ) {
                $args = apply_filters( 'ds8articulista_view_arguments', $args, $name );

                foreach ( $args AS $key => $val ) {
                        $$key = $val;
                }

                load_plugin_textdomain( 'ds8articulista' );

                $file = DS8ARTICULISTAS_PLUGIN_DIR . 'views/'. $name . '.php';

                include( $file );
	}
        
        public static function plugin_deactivation( ) {
            unregister_post_type( 'calendar' );
            flush_rewrite_rules();
        }

        /**
	 * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
	 * @static
	 */
	public static function plugin_activation() {
		if ( version_compare( $GLOBALS['wp_version'], DS8ARTICULISTAS_MINIMUM_WP_VERSION, '<' ) ) {
			load_plugin_textdomain( 'ds8articulista' );
                        
			$message = '<strong>'.sprintf(esc_html__( 'FD Estadisticas %s requires WordPress %s or higher.' , 'ds8articulista'), DS8ARTICULISTAS_VERSION, DS8ARTICULISTAS_MINIMUM_WP_VERSION ).'</strong> '.sprintf(__('Please <a href="%1$s">upgrade WordPress</a> to a current version, or <a href="%2$s">downgrade to version 2.4 of the Akismet plugin</a>.', 'ds8articulista'), 'https://codex.wordpress.org/Upgrading_WordPress', 'https://wordpress.org/extend/plugins/ds8articulista/download/');

			DS8Articulistas::bail_on_activation( $message );
		} elseif ( ! empty( $_SERVER['SCRIPT_NAME'] ) && false !== strpos( $_SERVER['SCRIPT_NAME'], '/wp-admin/plugins.php' ) ) {
                        flush_rewrite_rules();
			add_option( 'Activated_DS8Articulistas', true );
		}
	}

        private static function bail_on_activation( $message, $deactivate = true ) {
?>
<!doctype html>
<html>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<style>
* {
	text-align: center;
	margin: 0;
	padding: 0;
	font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
}
p {
	margin-top: 1em;
	font-size: 18px;
}
</style>
</head>
<body>
<p><?php echo esc_html( $message ); ?></p>
</body>
</html>
<?php
		if ( $deactivate ) {
			$plugins = get_option( 'active_plugins' );
			$ds8articulista = plugin_basename( DS8CALENDAR__PLUGIN_DIR . 'ds8articulista.php' );
			$update  = false;
			foreach ( $plugins as $i => $plugin ) {
				if ( $plugin === $ds8articulista ) {
					$plugins[$i] = false;
					$update = true;
				}
			}

			if ( $update ) {
				update_option( 'active_plugins', array_filter( $plugins ) );
			}
		}
		exit;
	}

}