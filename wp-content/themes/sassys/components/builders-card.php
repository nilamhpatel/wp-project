<?php
  $builder_terms = get_the_terms( get_the_ID(), 'menucategory' ); 

  $province = $builder_terms[0] -> name;
  $title    = get_the_title();

  $maplink = $address = "";
  $street  = get_post_meta( get_the_ID(), 'our_menu_street', true );
  $city    = get_post_meta( get_the_ID(), 'our_menu_city', true) ;
  $prov    = get_post_meta( get_the_ID(), 'our_menu_province', true );
  $postal  = get_post_meta( get_the_ID(), 'our_menu_zip', true );
  $phone   = get_post_meta( get_the_ID(), 'our_menu_phone', true );
  $website = get_post_meta( get_the_ID(), 'our_menu_website', true );
  $email   = get_post_meta( get_the_ID(), 'our_menu_email', true );

  if( !empty( $street ) ) {
    $maplink = $maplink . $street; 
    $address = $address . $street; 
  }

  if( !empty( $city ) ) {
    $maplink = $maplink . ",+" . $city;  
    $address = $address . ", " . $city;
  }

  if( !empty( $prov ) ) {
    $maplink = $maplink . ",+" . $prov;  
    $address = $address . ", " . $prov;
  }

  if( !empty( $postal ) ) {
    $maplink = $maplink . ",+" . $postal;  
    $address = $address . ", " . $postal;
  }  
?>
  
<div class="col builders-card-container">
  <?php
    if( has_post_thumbnail( get_the_ID() ) ) {
      echo '<div class="featured-image-container">';
        echo get_the_post_thumbnail( get_the_ID(), array( 452, 452 ) );
      echo '</div>';
    }            
            
    else {
      echo '<div class="default-image-container">';
        echo '<img src="' . get_stylesheet_directory_uri() .
             '/images/logo-green-check.svg" alt="" width="168" height="150" />';
      echo '</div>';
    } 
  ?>               

  <div class="builder-content-container">
    <p class="builder-location"><?php echo $province; ?></p>
              
    <h3 class="builder-title"><?php echo $title; ?></h3>

    <p class="builder-address"><?php echo $address; ?></p>
      
    <a class="builder-direction" target="_blank" rel="nofollow"
       href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $maplink; ?>"><?= __( 'Get Directions', 'sassys' ); ?></a>

    <?php
      if( !empty( $phone ) ) {
        echo '<p>';
          echo '<span>' . __( 'Telephone:', 'sassys' ) . 
                '</span> <a href="tel:' . $phone . '">' . $phone . '</a>';
        echo '</p>';
      }

      if( !empty( $website ) ) {
        echo '<p>';
          echo '<span>' . __( 'Website:', 'sassys' ) .
                '</span> <a href="' . $website . '" target="_blank" rel="nofollow">' . $website . '</a>';
        echo '</p>';
      }

      if( !empty( $email ) ) {
        echo '<p>';
          echo '<span>' . __( 'Email:', 'sassys' ) .
                '</span> <a href="mailto:' . $email . '">' . $email . '</a>';
        echo '</p>';
      }
    ?>
  </div>
</div>
