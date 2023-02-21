<?php
  $project_member  = get_post_meta( get_the_ID(), 'project_member', true );
  $project_city    = get_post_meta( get_the_ID(), 'project_city', true );
  $project_year    = get_post_meta( get_the_ID(), 'project_year', true );
  $project_budget  = get_post_meta( get_the_ID(), 'project_budget', true );
  $project_logo_id = get_post_meta( get_the_ID(), 'project_logo_id', true );
  $project_title   = get_the_title();
  $project_image   = get_the_post_thumbnail( get_the_ID(), array( 570, 400 ) );

  $project_excerpt = wp_trim_words( get_the_content(), 30, '...' );
?>
  <div class="row">
    <div class="col">
      <a href="<?= get_the_permalink(); ?>" class="card-contanier">
        <div class="row">
          <div class="col-md-6">
            <div class="project-image-container">
              <?= $project_image; ?>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="card-content-container">
              <h2><?= $project_title; ?></h2>

              <div class="data-container">
                <div class="extra-padding">
                  <?php 
                    if( !empty( $project_budget ) ) {
                      echo '<p><span>' . __( 'Budget:', 'sassys' ) . '</span> ' . $project_budget . '</p>';
                    }

                    if( !empty( $project_city ) ) {
                      echo '<p><span>' . __( 'City:', 'sassys' ) . '</span> ' . 
                      $project_city . '</p>';
                    }

                    if( !empty( $project_year ) ) {
                      echo '<p><span>' . __( 'Year:', 'sassys' ) . '</span> ' . 
                      $project_year . '</p>';
                    }

                    if( !empty( $project_member ) ) {
                      echo '<p><span>' . __( 'sassys Member:', 'sassys' ) . '</span> ' . 
                      $project_member . '</p>';
                    }
                  ?>
                </div>

                <?php if( !empty( $project_logo_id ) ) { ?>
                  <div class="extra-padding">
                    <?php echo wp_get_attachment_image( $project_logo_id, array( 220, 220 ) ); ?>
                  </div>
                <?php } ?>
              </div>
            
              <div class="info-container">
                <?= wpautop( $project_excerpt ); ?>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div> 
  </div>
