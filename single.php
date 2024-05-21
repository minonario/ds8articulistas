<?php
/**
 * The Template for displaying all single posts.
 *
 * @package GeneratePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

<?php
if (is_single()):
  $hero = get_field('internas_superior','option');
  if( $hero ): 
    if ( $hero['banner_superior']):
      $class = ' banner-active';
    endif;
  endif; 
endif;?>
<div class="si-container<?php echo (isset($class) ? $class : '') ?>">
	<div id="primary" class="content-area">
          <?php
              if( have_rows('repetidor_superior', 'option') ):
                while( have_rows('repetidor_superior', 'option') ): the_row();
                    $hero = get_sub_field('internas_superior','option');
                    if( $hero ):
                      if ( !isMobileDevice() ):
                        if ( $hero['banner_superior']): ?>
                          <div class="ad-banners center"><?php echo do_shortcode($hero['shortcode_banner']); ?></div>
                        <?php endif;
                      else:
                        if ( $hero['banner_superior']): ?>
                          <div class="ad-banners center"><?php echo do_shortcode($hero['shortcode_banner_mobile']); ?></div>
                        <?php endif;
                      endif;
                    endif;
                endwhile;
              endif;
          ?>
		<main id="content" class="site-content">
                  
			<?php
                        if ( sinatra_option( 'blog_horizontal_post_categories' ) ) {
					get_template_part( 'template-parts/entry/entry-category' );
				}
                        get_template_part( 'template-parts/entry/entry-header' );
                        echo '<div class="entry-meta"><div class="entry-meta-elements">';
                        echo sinatra_entry_meta_date();
                        echo '</div></div>';
                        
			/**
			 * generate_before_main_content hook.
			 *
			 * @since 0.1
			 */
			do_action( 'generate_before_main_content' );

			if ( true ) {
				while ( have_posts() ) :

					the_post();
                                
                                        the_content();

					//generate_do_template_part( 'single' );

				endwhile;
			}

			/**
			 * generate_after_main_content hook.
			 *
			 * @since 0.1
			 */
			do_action( 'generate_after_main_content' );
			?>
                  
                        <!-- wp:columns -->
                        <div class="wp-block-columns bloque-articulistas"><!-- wp:column {"width":"150px"} -->
                        <div class="wp-block-column" style="flex-basis:150px"><!-- wp:html -->
                        <a title="Ver articulistas" class="boton-suscribir" href="https://finanzasdigital.com/opinion/" aria-label="Ver articulistas">Ver articulistas</a>
                        <!-- /wp:html --></div>
                        <!-- /wp:column -->

                        <!-- wp:column {"width":"200px"} -->
                        <div class="wp-block-column" style="flex-basis:200px"><!-- wp:shortcode -->
                        <?php
                                global $post;
                                $ds8box_author_id = $post->post_author;
                                $autor_nickname = get_the_author_meta( 'nickname', $ds8box_author_id );
                                $linkAutor='https:///finanzasdigital.com/author/' .$autor_nickname.'/';
                        ?> 
                        <a title='Ver otros artículos' class='boton-suscribir' href='<?php echo $linkAutor;?>' aria-label='Ver otros artículos'>Ver otros artículos</a>
                        <!-- /wp:shortcode --></div>
                        <!-- /wp:column -->

                        <!-- wp:column -->
                        <div class="wp-block-column"></div>
                        <!-- /wp:column --></div>
                        <!-- /wp:columns -->
                  
                        <?php echo do_shortcode('[simple-author-box-ds8]')?>
                        
                        <?php echo do_shortcode( '[ds8relatedposts]' ); ?>
                        
                        <?php
                            if( have_rows('repetidor_inferior', 'option') ):
                              while( have_rows('repetidor_inferior', 'option') ): the_row();
                                  $hero = get_sub_field('internas_inferior','option');
                                  if( $hero ):
                                    if ( !isMobileDevice() ):
                                      if ( $hero['banner_inferior']): ?>
                                        <div class="ad-banners center"><?php echo do_shortcode($hero['shortcode_banner']); ?></div>
                                      <?php endif;
                                    else:
                                      if ( $hero['banner_inferior']): ?>
                                        <div class="ad-banners center"><?php echo do_shortcode($hero['shortcode_banner_mobile']); ?></div>
                                      <?php endif;
                                    endif;
                                  endif;
                              endwhile;
                            endif;
                        ?>
                        
                        <?php do_action( 'sinatra_after_singular' ); ?>
		</main>
	</div>
  <?php 
  get_sidebar();
  ?>
</div>

<?php

get_footer();