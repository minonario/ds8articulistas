
<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>
<?php
if (is_author()):
  $post_id = get_the_ID();
  $hero = get_field('superior', $post_id);
  if( $hero ): 
    if ( $hero['banner_superior']):
      $class = ' banner-active';
    endif;
  endif; 
endif;?>

<div class="si-container<?php echo (isset($class) ? $class : '') ?>" id="nuevo">
  <div id="primary" class="content-area">
      <?php
      //if (is_author()):
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
      //endif;
      ?>
      <main id="content" class="site-content">
          <?php
          /**
           * generate_before_main_content hook.
           *
           * @since 0.1
           */
          do_action( 'generate_before_main_content' );?>
<?php
// Definimos la variable $curauth donde almacenaremos al info del usuario
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
?>

<div class="ds8boxplugin-wrap" itemtype="http://schema.org/Person" itemscope="" itemprop="author">
	<div class="post-opinion-card">
		<div>
			<div class="posts-opinion-card-gravatar">
				<?php echo get_avatar( $curauth->user_email , '145 ', '', $curauth->data->display_name); ?>
			</div>
			<div>
				<h2 class="posts-opinion-card-titulo"><?php echo $curauth->first_name;?>&nbsp;<?php echo $curauth->last_name;?></h2>
			</div>
			<div class="posts-opinion-card-descricion">
				<?php echo $curauth->user_description;?>
			</div>		
		</div>
		<div class="clearfix">
			<div class="posts-opinion-card-redes-sociales">
				<?php if (strlen($curauth->user_url)> 1){
					echo '<a href="'. $curauth->user_url .'" target="_blank" rel="noopener" title="WEB"><img src ="'.plugin_dir_url( __FILE__ ).'assets/img/card-web-25-finanzasdigital.jpeg"  alt="WEB" width="25" height="25"/></a>&nbsp;';
				}
				?>
				<?php if (strlen($curauth->facebook)> 1){
					echo '<a href="https://facebook.com/'. $curauth->facebook .'" target="_blank" rel="noopener" title="Facebook"><img src ="'.plugin_dir_url( __FILE__ ).'assets/img/card-facebook-25-finanzasdigital.jpeg"  alt="Facebook" width="25" height="25"/></a>&nbsp;';
				}
				?>
				<?php if (strlen($curauth->instagram)> 1){
					echo '<a href="https://instagram.com/'. $curauth->instagram .'" target="_blank" rel="noopener" title="Instagram"><img src ="'.plugin_dir_url( __FILE__ ).'assets/img/card-instagram-25-finanzasdigital.jpeg"  alt="Instagram" width="25" height="25"/></a>&nbsp;';
				}
				?>
				<?php if (strlen($curauth->linkedin)> 1){
					echo '<a href="https://www.linkedin.com/in/'. $curauth->linkedin .'" target="_blank" rel="noopener" title="Linkedin"><img src ="'.plugin_dir_url( __FILE__ ).'assets/img/card-linkedin-25-finanzasdigital.jpeg"  alt="Linkedin" width="25" height="25"/></a>&nbsp;';
				}
				?>
				<?php if (strlen($curauth->myspace)> 1){
					echo '<a href="https://myspace.com/'. $curauth->myspace .'" target="_blank" rel="noopener" title="MySpace"><img src ="'.plugin_dir_url( __FILE__ ).'assets/img/card-myspace-25-finanzasdigital.jpeg"  alt="MySpace" width="25" height="25"/></a>&nbsp;';
				}
				?>
				<?php if (strlen($curauth->pinterest)> 1){
					echo '<a href="https://www.pinterest.com/'. $curauth->pinterest .'" target="_blank" rel="noopener" title="Pinterest"><img src ="'.plugin_dir_url( __FILE__ ).'assets/img/card-pinterest-25-finanzasdigital.jpeg"  alt="Pinterest" width="25" height="25"/></a>&nbsp;';
				}
				?>
				<?php if (strlen($curauth->soundcloud)> 1){
					echo '<a href="https://soundcloud.com/'. $curauth->soundcloud .'" target="_blank" rel="noopener" title="SoundCloud"><img src ="'.plugin_dir_url( __FILE__ ).'assets/img/card-soundcloud-25-finanzasdigital.jpeg"  alt="SoundCloud" width="25" height="25"/></a>&nbsp;';
				}
				?>
				<?php if (strlen($curauth->tumblr)> 1){
					echo '<a href="https://www.tumblr.com/blog/view/'. $curauth->tumblr .'" target="_blank" rel="noopener" title="Tumblr"><img src ="'.plugin_dir_url( __FILE__ ).'assets/img/card-tumblr-25-finanzasdigital.jpeg"  alt="Tumblr" width="25" height="25"/></a>&nbsp;';
				}
				?>
				<?php if (strlen($curauth->twitter)> 1){
					echo '<a href="https://twitter.com/'. $curauth->twitter .'" target="_blank" rel="noopener" title="Twitter"><img src ="'.plugin_dir_url( __FILE__ ).'assets/img/card-twitter-25-finanzasdigital.jpeg"  alt="Twitter" width="25" height="25"/></a>&nbsp;';
				}
				?>
				<?php if (strlen($curauth->youtube)> 1){
					echo '<a href="https://www.youtube.com/'. $curauth->youtube .'" target="_blank" rel="noopener" title="YouTube"><img src ="'.plugin_dir_url( __FILE__ ).'assets/img/card-youtube-25-finanzasdigital.jpeg"  alt="YouTube" width="25" height="25"/></a>&nbsp;';
				}
				?>
				<?php if (strlen($curauth->wikipedia)> 1){
					echo '<a href="'. $curauth->wikipedia .'" target="_blank" rel="noopener" title="Wikipedia"><img src ="'.plugin_dir_url( __FILE__ ).'assets/img/card-wikipedia-25-finanzasdigital.jpeg"  alt="Wikipedia" width="25" height="25"/></a>&nbsp;';
				}
				?>
			</div>
		</div>
	</div>	
</div>
<br>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<div class="post-opinion-grid">
              <h2 class="posts-opinion-titulo">
                      <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
                              <?php the_title(); ?>
                      </a>
              </h2>
              <!-- Fecha de la publicacion -->
              <p class="posts-opinion-fecha"><?php the_time('d M Y'); ?></p>
              <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>" class="posts-opinion-enlace">
                      Leer más »
              </a>
              <!-- Resumen de la publicacion -->
              <?php //the_excerpt(); ?>
		
	</div>
<?php endwhile; ?>
<br> 
<?php
// Paginacion.
the_posts_pagination();?>
<br>
<?php
// Si no hay publicaciones
 else: ?>
<p>
	<?php _e('Este autor no tiene posts.'); ?>
</p>
<br>
<?php endif; ?>
	
	
	
	</main>
    <?php
    //if (is_author()):
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
    //endif;
    ?>
  </div>
<?php get_sidebar(); ?>
</div>
<?php

get_footer();
?>