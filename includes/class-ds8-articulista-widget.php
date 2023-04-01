<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */


class DS8_Columnist_Widget extends WP_Widget {

	var $defaults;
        
	public function __construct() {
	
            $widget_ops  = array(
            'classname' => 'ds8_simple_columnist_widget',
            'description' => esc_html__('Use this widget to display columnist', 'ds8articulista')
            );

            $control_ops = array( 'id_base' => 'ds8_columnist_widget' );
            parent::__construct( 'ds8_columnist_widget', esc_html__('Columnist Tab', 'ds8articulista'), $widget_ops, $control_ops );
            }

	public function form( $instance ) {	
            $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'ds8articulista' );
            $tab_one = ! empty( $instance['page_one'] ) ? $instance['page_one'] : esc_html__( 'Link Tab One', 'ds8articulista' );
            $tab_two = ! empty( $instance['page_two'] ) ? $instance['page_two'] : esc_html__( 'Link Tab Two', 'ds8articulista' );
		?>
		<p>
                  <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'ds8articulista' ); ?></label>
                  <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
                <p>
                  <label for="<?php echo esc_attr( $this->get_field_id( 'page_one' ) ); ?>"><?php esc_attr_e( 'Link Tab One:', 'ds8articulista' ); ?></label>
                  <?php wp_dropdown_pages(array(
                                'depth'                 => 0,
                                'child_of'              => 0,
                                'selected'              => $tab_one,
                                'echo'                  => 1,
                                'name'                  => esc_attr( $this->get_field_name( 'page_one' ) ),
                                'id'                    => $this->get_field_id( 'page_one' ),
                                'class'                 => 'widefat',
                                'show_option_none'      => '',
                                'show_option_no_change' => '',
                                'option_none_value'     => '',
                                'value_field'           => 'ID',
                        )); 
                  ?>
                </p>
                <p>
                  <label for="<?php echo esc_attr( $this->get_field_id( 'page_two' ) ); ?>"><?php esc_attr_e( 'Link Tab Two:', 'ds8articulista' ); ?></label>
                  <?php 
                        wp_dropdown_categories(array(
                                'show_option_all'   => '',
                                'show_option_none'  => '',
                                'orderby'           => 'id',
                                'order'             => 'ASC',
                                'show_count'        => 0,
                                'hide_empty'        => 1,
                                'child_of'          => 0,
                                'exclude'           => '',
                                'echo'              => 1,
                                'selected'          => $tab_two,
                                'hierarchical'      => 0,
                                'name'              => esc_attr( $this->get_field_name( 'page_two' ) ),
                                'id'                => $this->get_field_id( 'page_two' ),
                                'class'             => 'widefat',
                                'depth'             => 0,
                                'tab_index'         => 0,
                                'taxonomy'          => 'category',
                                'hide_if_empty'     => false,
                                'option_none_value' => -1,
                                'value_field'       => 'term_id',
                                'required'          => false,
                                'aria_describedby'  => '',
                        ));
                  ?>
                </p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
            $instance            = $old_instance;
            $instance['title']   = sanitize_text_field( $new_instance['title'] );
            $instance['page_one']   = sanitize_text_field( $new_instance['page_one'] );
            $instance['page_two']   = sanitize_text_field( $new_instance['page_two'] );

            return $instance;
        }
        
        public function _prefix_get_users_by_post_date() {
          $args = array(
              'role' => 'Contributor',
              'number' => -1
          );
          $users = get_users($args);

          $post_dates = array();
          if ($users) {
            foreach ($users as $user) {
              $ID = $user->ID;
              $posts = get_posts('author=' . $ID);
              $post_dates[$ID] = '';

              if ($posts) {
                $post_dates[$ID] = $posts[0]->post_date;
              }
            }
          }
          //remove this line to order users by oldest post first
          arsort($post_dates);

          $users = array();
          foreach ($post_dates as $key => $value) {
            $users[] = get_userdata($key);
          }
          return $users;
        }

	public function widget( $args, $instance ) {
            $get_users = $this->_prefix_get_users_by_post_date();
            $users = array_slice($get_users, 0, 5, true);
            $tab_one = $instance['page_one'];
            $tab_two = $instance['page_two'];
            ?>
            <div id="tab_container_ds8">
                <ul class="nav nav-tabs columnist" role="tablist">
                  <li role="presentation" class="active"><a href="#opinion" aria-controls="opinion" role="tab" data-toggle="tab">Opinión</a></li>
                  <li role="presentation"><a href="#prensa" aria-controls="prensa" role="tab" data-toggle="tab">Prensa</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="opinion">
                        <div class="fd-lista-opinion-container">
                                <?php
                                foreach ($users as $user ) :
                                        $ID = $user->data->ID;
                                        $args = array(
                                                      'numberposts' => 1,
                                                      'author' => $ID,
                                                );
                                        $posts = get_posts( $args );
                                        
                                        if ( count($posts) > 0 ) :
                                ?>
                                    <div class="fd-lista-colaboradores-item">
                                            <div class="ctavatar">
                                            <?php echo get_avatar($ID, '65', 'mystery', 'Artículista: '.$user->data->display_name, array('fd-lista-opinion-picture'));  ?>
                                            </div>
                                            <div class="fd-lista-name-and-article">
                                              <a href="<?php echo get_author_posts_url($ID); ?>" class="fd-articulista-nombre"><strong><?php echo $user->data->display_name; ?></strong></a>
                                                      <?php foreach($posts as $post): ?>
                                              <a class="fd-lista-opinion-article" href="<?php the_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php //echo wp_trim_words($post->post_title, 7, '...');
                                                    echo mb_strimwidth($post->post_title, 0, 45, "...");?></a>
                                                    <?php endforeach; ?>
                                            </div>

                                    </div>
                                <?php
                                        endif;
                                endforeach; ?>
                        </div>
                        <div class="ds8-ver-todos-opinion">
                          <a href="<?php echo get_permalink($tab_one) ?>">Ver todos</a>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="prensa">
                    <?php
                    $query_args = array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'orderby' => 'date',
                        'posts_per_page' => '5',
                        'ignore_sticky_posts' => true,
                        'cat' => $tab_two,
                    );
                    $the_query = new WP_Query( $query_args );
                    
                    if ( $the_query->have_posts() ) {
                            while ( $the_query->have_posts() ) {
                                      $the_query->the_post(); 
                                    ?>
                            <div class="fd-lista-prensa-item">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo wp_trim_words( get_the_title(), 15, '...' ); ?></a>
                            </div>
                            <?php
                            }
                            wp_reset_postdata();
                    } else {
                            // no posts found
                    }
                    ?>
                    <div class="ds8-ver-todos-informes">
                        <a href="<?php echo get_category_link($tab_two)?>">Ver todos</a>
                    </div>
                    </div>
                    
                </div>
            </div>
            <?php
            //ob_get_clean();
	}

}