<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
?>
<div class="fd-lista-opinion-container">
          <?php
          foreach ($users as $user ) :
                  $ID = $user->data->ID;
                  //Verifico que el autor tenga articulos
              $my_posts = get_posts( array( 'author' => $ID ) );
                  //Si el autor tiene articulos lo muestro
                  if( ! empty( $my_posts ) ){ 
          ?>
          <div class="fd-lista-articulista">
                  <?php echo get_avatar($ID, '75', 'mystery', '', array('fd-lista-opinion-picture'));  ?>
                  <a href="<?php echo get_author_posts_url($ID); ?>" class="fd-articulista-nombre"><?php echo $user->data->display_name; ?></a>
                          <p><?php echo substr(get_the_author_meta( 'user_description', $ID ), 0,200); ?>...</p>
          </div>	
          <?php	
                  }
          endforeach;
          ?>
</div>