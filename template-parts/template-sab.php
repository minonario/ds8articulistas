<?php

if (isset($sabox_options['sab_colored']) && '1' == $sabox_options['sab_colored']) {
    $sabox_color = 'sabox-colored';
} else {
    $sabox_color = '';
}

if (isset($sabox_options['sab_web_position']) && '0' != $sabox_options['sab_web_position']) {
    $sab_web_align = 'sab-web-position';
} else {
    $sab_web_align = '';
}

if (isset($sabox_options['sab_web_target']) && '1' == $sabox_options['sab_web_target']) {
    $sab_web_target = '_blank';
} else {
    $sab_web_target = '_self';
}

if (isset($sabox_options['sab_web_rel']) && '1' == $sabox_options['sab_web_rel']) {
    $sab_web_rel = 'rel="nofollow"';
} else {
    $sab_web_rel = '';
}

$sab_author_link = sprintf('<a href="%s" class="vcard author" rel="author" itemprop="url"><span class="fn" itemprop="name">%s</span></a>', esc_url(get_author_posts_url($sabox_author_id)), esc_html(get_the_author_meta('display_name', $sabox_author_id)));

$author_description = apply_filters('sab_user_description', get_the_author_meta('description', $sabox_author_id), $sabox_author_id);

if ('' != $author_description || isset($sabox_options['sab_no_description']) && '0' == $sabox_options['sab_no_description']) { // hide the author box if no description is provided

    $show_guest_only = (get_post_meta(get_the_ID(), '_disable_sab_author_here', true)) ? get_post_meta(get_the_ID(), '_disable_sab_author_here', true) : "false";

    if ($show_guest_only != "on") {

        echo '<div class="ds8boxplugin-wrap" itemtype="http://schema.org/Person" itemscope itemprop="author">'; // start ds8boxplugin-wrap div

        echo '<div class="ds8boxplugin-tab">';

        // author box gravatar
        echo '<div class="ds8boxplugin-gravatar">';
        $custom_profile_image = get_the_author_meta('sabox-profile-image', $sabox_author_id);
        if ('' != $custom_profile_image) {
            $mediaid = attachment_url_to_postid($custom_profile_image);
            $alt     = $mediaid ? get_post_meta($mediaid, '_wp_attachment_image_alt', true) : get_the_author_meta('display_name', $sabox_author_id);

            $link = null;
            $nofollow = '';
            if (isset($sabox_options['sab_author_link'])) {
                if ('author-page' == $sabox_options['sab_author_link']) {

                    $link = get_user_meta($sabox_author_id, 'sab_box_link', true);
                    if (empty($link))
                        $link = get_author_posts_url($sabox_author_id);
                } elseif ('author-website' == $sabox_options['sab_author_link']) {
                    if (isset($sabox_options['sab_author_link_noffolow']) && '1' == $sabox_options['sab_author_link_noffolow']) {
                        $nofollow = ' rel="nofollow"';
                    }

                    $link = get_the_author_meta('user_url', $sabox_author_id);
                }
            }

            if ($link != null)
                echo "<a $nofollow href='" . $link . "'>";

            echo '<img src="' . esc_url($custom_profile_image) . '" width="' . $sabox_options['sab_avatar_size'] . '"  height="' . $sabox_options['sab_avatar_size'] . '" alt="' . esc_attr($alt) . '" itemprop="image">';

            if ($link != null)
                echo "</a>";
        } else {
            echo get_avatar(get_the_author_meta('user_email', $sabox_author_id), $sabox_options['sab_avatar_size'], '', get_the_author_meta('display_name', $sabox_author_id), array('extra_attr' => 'itemprop="image"'));
        }

        echo '</div>';

        // author box name
        echo '<div class="ds8boxplugin-authorname">';
        echo apply_filters('sabox_author_html', $sab_author_link, $sabox_options, $sabox_author_id);
        if (is_user_logged_in() && get_current_user_id() == $sabox_author_id) {
            echo '<a class="sab-profile-edit" target="_blank" href="' . get_edit_user_link() . '"> ' . esc_html__('Edit profile', 'simple-author-box') . '</a>';
        }
        echo '</div>';

        // author box description
        echo '<div class="ds8boxplugin-desc">';
        echo '<div itemprop="description">';

        $author_description = wptexturize($author_description);
        $author_description = wpautop($author_description);
        echo wp_kses_post($author_description);
        if ('' == $author_description && is_user_logged_in() && $sabox_author_id == get_current_user_id()) {
            echo '<a target="_blank" href="' . admin_url() . 'profile.php?#wp-description-wrap">' . esc_html__('Add Biographical Info', 'simple-author-box') . '</a>';
        }
        echo '</div>';
        echo '</div>';

        if (is_single() || is_page()) {
            if (get_the_author_meta('user_url') != '' && '1' == $sabox_options['sab_web']) { // author website on single
                echo '<div class="ds8boxplugin-web ' . esc_attr($sab_web_align) . '">';
                echo '<a href="' . esc_url(get_the_author_meta('user_url', $sabox_author_id)) . '" target="' . esc_attr($sab_web_target) . '" ' . $sab_web_rel . '>' . esc_html(Simple_Author_Box_Helper::strip_prot(get_the_author_meta('user_url', $sabox_author_id))) . '</a>';
                echo '</div>';
            }
        }


        if (is_author() || is_archive()) {
            if (get_the_author_meta('user_url') != '') { // force show author website on author.php or archive.php
                echo '<div class="ds8boxplugin-web ' . esc_attr($sab_web_align) . '">';
                echo '<a href="' . esc_url(get_the_author_meta('user_url', $sabox_author_id)) . '" target="' . esc_attr($sab_web_target) . '" ' . $sab_web_rel . '>' . esc_html(Simple_Author_Box_Helper::strip_prot(get_the_author_meta('user_url', $sabox_author_id))) . '</a>';
                echo '</div>';
            }
        }

        // author box clearfix
        //echo '<div class="clearfix"></div>';

        // author box social icons
        $author            = get_userdata($sabox_author_id);
        $show_social_icons = apply_filters('sabox_hide_social_icons', true, $author);

        /*if (is_user_logged_in() && current_user_can('manage_options')) {
            echo '<div class="sab-edit-settings">';
            echo '<a target="_blank" href="' . admin_url() . 'themes.php?page=simple-author-box">' . esc_html__('Settings', 'simple-author-box') . '<i class="dashicons dashicons-admin-settings"></i></a>';
            echo '</div>';
        }*/

            echo '<div class="clearfix"></div>';
            echo '<div class="ds8boxplugin-socials ' . esc_attr($sabox_color) . '">';
            ?>
            <?php  $curauth = $author; ?>
                          <?php if (strlen($curauth->user_url)> 1){
                                  echo '<a href="'. $curauth->user_url .'" target="_blank" rel="noopener" title="WEB"><img src ="'.DS8_AUTHOR_BOX_ASSETS.'img/card-web-25-finanzasdigital.jpeg"  alt="WEB" width="25" height="25"/></a>&nbsp;';
                          }
                          ?>
                          <?php if (strlen($curauth->facebook)> 1){
                                  echo '<a href="https://facebook.com/'. $curauth->facebook .'" target="_blank" rel="noopener" title="Facebook"><img src ="'.DS8_AUTHOR_BOX_ASSETS.'img/card-facebook-25-finanzasdigital.jpeg"  alt="Facebook" width="25" height="25"/></a>&nbsp;';
                          }
                          ?>
                          <?php if (strlen($curauth->instagram)> 1){
                                  echo '<a href="https://instagram.com/'. $curauth->instagram .'" target="_blank" rel="noopener" title="Instagram"><img src ="'.DS8_AUTHOR_BOX_ASSETS.'img/card-instagram-25-finanzasdigital.jpeg"  alt="Instagram" width="25" height="25"/></a>&nbsp;';
                          }
                          ?>
                          <?php if (strlen($curauth->linkedin)> 1){
                                  echo '<a href="https://www.linkedin.com/in/'. $curauth->linkedin .'" target="_blank" rel="noopener" title="Linkedin"><img src ="'.DS8_AUTHOR_BOX_ASSETS.'img/card-linkedin-25-finanzasdigital.jpeg"  alt="Linkedin" width="25" height="25"/></a>&nbsp;';
                          }
                          ?>
                          <?php if (strlen($curauth->myspace)> 1){
                                  echo '<a href="https://myspace.com/'. $curauth->myspace .'" target="_blank" rel="noopener" title="MySpace"><img src ="'.DS8_AUTHOR_BOX_ASSETS.'img/card-myspace-25-finanzasdigital.jpeg"  alt="MySpace" width="25" height="25"/></a>&nbsp;';
                          }
                          ?>
                          <?php if (strlen($curauth->pinterest)> 1){
                                  echo '<a href="https://www.pinterest.com/'. $curauth->pinterest .'" target="_blank" rel="noopener" title="Pinterest"><img src ="'.DS8_AUTHOR_BOX_ASSETS.'img/card-pinterest-25-finanzasdigital.jpeg"  alt="Pinterest" width="25" height="25"/></a>&nbsp;';
                          }
                          ?>
                          <?php if (strlen($curauth->soundcloud)> 1){
                                  echo '<a href="https://soundcloud.com/'. $curauth->soundcloud .'" target="_blank" rel="noopener" title="SoundCloud"><img src ="'.DS8_AUTHOR_BOX_ASSETS.'img/card-soundcloud-25-finanzasdigital.jpeg"  alt="SoundCloud" width="25" height="25"/></a>&nbsp;';
                          }
                          ?>
                          <?php if (strlen($curauth->tumblr)> 1){
                                  echo '<a href="https://www.tumblr.com/blog/view/'. $curauth->tumblr .'" target="_blank" rel="noopener" title="Tumblr"><img src ="'.DS8_AUTHOR_BOX_ASSETS.'img/card-tumblr-25-finanzasdigital.jpeg"  alt="Tumblr" width="25" height="25"/></a>&nbsp;';
                          }
                          ?>
                          <?php if (strlen($curauth->twitter)> 1){
                                  echo '<a href="https://twitter.com/'. $curauth->twitter .'" target="_blank" rel="noopener" title="Twitter"><img src ="'.DS8_AUTHOR_BOX_ASSETS.'img/card-twitter-25-finanzasdigital.jpeg"  alt="Twitter" width="25" height="25"/></a>&nbsp;';
                          }
                          ?>
                          <?php if (strlen($curauth->youtube)> 1){
                                  echo '<a href="https://www.youtube.com/'. $curauth->youtube .'" target="_blank" rel="noopener" title="YouTube"><img src ="'.DS8_AUTHOR_BOX_ASSETS.'img/card-youtube-25-finanzasdigital.jpeg"  alt="YouTube" width="25" height="25"/></a>&nbsp;';
                          }
                          ?>
                          <?php if (strlen($curauth->wikipedia)> 1){
                                  echo '<a href="'. $curauth->wikipedia .'" target="_blank" rel="noopener" title="Wikipedia"><img src ="'.DS8_AUTHOR_BOX_ASSETS.'img/card-wikipedia-25-finanzasdigital.jpeg"  alt="Wikipedia" width="25" height="25"/></a>&nbsp;';
                          }
                          ?>
            <?php
            echo '</div>';
        //} // sab_socials

        echo '</div>'; // sabox-tab

    } // show guest only
    echo '</div>'; // end of ds8boxplugin-wrap div

}
