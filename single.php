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
<div class="si-container">
	<div id="primary" class="content-area">
		<main id="content" class="site-content">
			<?php
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
                        <div class="wp-block-columns"><!-- wp:column {"width":"150px"} -->
                        <div class="wp-block-column" style="flex-basis:150px"><!-- wp:html -->
                        <a title="Ver articulistas" class="boton-suscribir" href="https://test.finanzasdigital.com/opinion/" aria-label="Ver articulistas">Ver articulistas</a>
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
		</main>
	</div>
  <?php 
  get_sidebar();
  ?>
</div>

<?php

get_footer();