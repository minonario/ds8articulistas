<?php

/**
 *
 */
class DS8_Columnist_Helper
{

    public static $options = array();
    
    public static $social_icons = array(
        'addthis'       => 'Add This',
        'behance'       => 'Behance',
        'delicious'     => 'Delicious',
        'deviantart'    => 'Deviantart',
        'digg'          => 'Digg',
        'discord'       => 'Discord',
        'dribbble'      => 'Dribbble',
        'facebook'      => 'Facebook',
        'whatsapp'      => 'WhatsApp',
        'flickr'        => 'Flickr',
        'github'        => 'Github',
        'google'        => 'Google',
        'googleplus'    => 'Google Plus',
        'html5'         => 'Html5',
        'instagram'     => 'Instagram',
        'linkedin'      => 'Linkedin',
        'pinterest'     => 'Pinterest',
        'reddit'        => 'Reddit',
        'rss'           => 'Rss',
        'sharethis'     => 'Sharethis',
        'skype'         => 'Skype',
        'soundcloud'    => 'Soundcloud',
        'spotify'       => 'Spotify',
        'stackoverflow' => 'Stackoverflow',
        'steam'         => 'Steam',
        'stumbleUpon'   => 'StumbleUpon',
        'tumblr'        => 'Tumblr',
        'twitter'       => 'Twitter',
        'vimeo'         => 'Vimeo',
        'windows'       => 'Windows',
        'wordpress'     => 'WordPress',
        'yahoo'         => 'Yahoo',
        'youtube'       => 'Youtube',
        'xing'          => 'Xing',
        'mixcloud'      => 'MixCloud',
        'goodreads'     => 'Goodreads',
        'twitch'        => 'Twitch',
        'vk'            => 'VK',
        'medium'        => 'Medium',
        'quora'         => 'Quora',
        'meetup'        => 'Meetup',
        'user_email'    => 'Email',
        'snapchat'      => 'Snapchat',
        '500px'         => '500px',
        'mastodont'     => 'Mastodon',
        'telegram'      => 'Telegram',
        'phone'         => 'Phone'
    );
    
    public static function get_sabox_social_icon($url, $icon_name)
    {

        $options = self::get_option('ds8boxplugin_options');

        if ('0' != $options['sab_link_target'] && 'user_email' != $icon_name) {
            $sabox_blank = '_blank';
        } else {
            $sabox_blank = '_self';
        }

        if ('1' == $options['sab_colored']) {
            $sab_color = 'ds8boxplugin-icon-color';
        } else {
            $sab_color = 'ds8boxplugin-icon-grey';
        }

        $type = 'simple';
        if ('1' == $options['sab_colored']) {
            if ('1' == $options['sab_icons_style']) {
                $type = 'circle';
            } else {
                $type = 'square';
            }
        }

        $url = ('skype' != $icon_name) ? esc_url($url) : esc_attr($url);

        $svg_icon = DS8_Columnist_Helper::icon_to_svg($icon_name, $type);
        return '<a target="' . esc_attr($sabox_blank) . '" href="' .  $url . '" aria-label="' . esc_attr(ucfirst(str_replace('_',' ',$icon_name))) . '" rel="nofollow noopener" class="' . esc_attr($sab_color) . '">' . $svg_icon . '</span></a>';
    }
    
    public static function get_user_social_links($userd_id, $show_email = false)
    {

        $social_icons = apply_filters('sabox_social_icons', DS8_Columnist_Helper::$social_icons);
        $social_links = get_user_meta($userd_id, 'sabox_social_links', true);

        if (!is_array($social_links)) {
            $social_links = array();
        }

        if ($show_email) {
            $social_links['user_email'] = get_the_author_meta('user_email', $userd_id);
        }

        return $social_links;
    }

    public static function get_template($template_name = 'template-sab.php')
    {

        $template = '';

        if (!$template) {
            $template = locate_template(array('sab/' . $template_name));
        }

        if (!$template && file_exists(DS8ARTICULISTAS_PLUGIN_DIR . 'template-parts/' . $template_name)) {
            $template = DS8ARTICULISTAS_PLUGIN_DIR . 'template-parts/' . $template_name;
        }

        if (!$template) {
            $template = DS8ARTICULISTAS_PLUGIN_DIR . 'template-parts/template-sab.php';
        }

        // Allow 3rd party plugins to filter template file from their plugin.
        $template = apply_filters('sabox_get_template_part', $template, $template_name);
        if ($template) {
            return $template;
        }
    }

    public static function reset_options()
    {
        self::$options = array();
    }

    public static function get_option($key, $force = false)
    {

        $defaults = apply_filters('sab_box_options_defaults', array(
            'ds8boxplugin_options' => array(
                'sab_autoinsert'         => '0',
                'sab_show_latest_posts'  => '0',
                'sab_show_custom_html'   => '0',
                'sab_no_description'     => '0',
                'sab_email'              => '0',
                'sab_link_target'        => '0',
                'sab_hide_socials'       => '0',
                'sab_hide_on_archive'    => '0',
                'sab_box_border_width'   => '1',
                'sab_avatar_style'       => '0',
                'sab_avatar_size'        => '100',
                'sab_avatar_hover'       => '0',
                'sab_web'                => '0',
                'sab_web_target'         => '0',
                'sab_web_rel'            => '0',
                'sab_web_position'       => '0',
                'sab_colored'            => '0',
                'sab_icons_style'        => '0',
                'sab_social_hover'       => '0',
                'sab_box_long_shadow'    => '0',
                'sab_box_thin_border'    => '0',
                'sab_box_author_color'   => '',
                'sab_box_web_color'      => '',
                'sab_box_border'         => '',
                'sab_box_icons_back'     => '',
                'sab_box_author_back'    => '',
                'sab_box_author_p_color' => '',
                'sab_box_author_a_color' => '',
                'sab_box_icons_color'    => '',
                'sab_footer_inline_style' => '',
                'sab_whitelabel' => 0,
            ),
            'sab_box_margin_top'         => '0',
            'sab_box_margin_bottom'      => '0',
            'sab_box_padding_top_bottom' => '0',
            'sab_box_padding_left_right' => '0',
            'sab_box_subset'             => 'none',
            'sab_box_name_font'          => 'None',
            'sab_box_web_font'           => 'None',
            'sab_box_desc_font'          => 'None',
            'sab_box_name_size'          => '18',
            'sab_box_web_size'           => '14',
            'sab_box_desc_size'          => '14',
            'sab_box_icon_size'          => '18',
            'sab_desc_style'             => '0',

        ));

        if ('ds8boxplugin_options' == $key) {

            if (!isset(self::$options['ds8boxplugin_options'])) {
                self::$options['ds8boxplugin_options'] = get_option('ds8boxplugin_options', array());
            }

            return wp_parse_args(self::$options['ds8boxplugin_options'], $defaults['ds8boxplugin_options']);
        } else {

            if (isset(self::$options[$key])) {

                return self::$options[$key];
            } else {

                $option = get_option($key, false);
                if (false === $option && isset($defaults[$key])) {
                    return $defaults[$key];
                } elseif (false !== $option) {
                    self::$options[$key] = $option;
                    return self::$options[$key];
                }
            }
        }

        return false;
    }

}
