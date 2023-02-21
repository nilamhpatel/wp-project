<?php
  $twitter_url   = sassys_theme_site_option( 'twitter_url', false );
  $instagram_url = sassys_theme_site_option( 'instagram_url', false );
  $linkedin_url  = sassys_theme_site_option( 'linkedin_url', false );
  $facebook_url  = sassys_theme_site_option( 'facebook_url', false );
  $youtube_url   = sassys_theme_site_option( 'youtube_url', false );
?>

<div class="reno-social-links-container">
  <?php
    if( !empty( $twitter_url ) ) {
      echo '<a target="_blank" aria-label="' . __( 'Follow Us On Twitter', 'sassys' ) . 
           '" rel="nofollow" class="twitter-brand" href="' . $twitter_url .
           '"><i class="fab fa-twitter"></i></a>';
    }

    if( !empty( $instagram_url ) ) {
      echo '<a target="_blank" aria-label="' . __( 'Follow Us On Instagram', 'sassys' ) . 
           '" rel="nofollow" class="instagram-brand" href="' . $instagram_url .
           '"><i class="fab fa-instagram"></i></a>';
    }

    if( !empty( $linkedin_url ) ) {
      echo '<a target="_blank" aria-label="' . __( 'Follow Us On LinkedIn', 'sassys' ) . 
           '" rel="nofollow" class="linkedin-brand" href="' . $linkedin_url .
           '"><i class="fab fa-linkedin-in"></i></a>';
    }

    if( !empty( $facebook_url ) ) {
      echo '<a target="_blank" aria-label="' . __( 'Follow Us On Facebook', 'sassys' ) . 
           '" rel="nofollow" class="facebook-brand" href="' . $facebook_url .
           '"><i class="fab fa-facebook"></i></a>';
    }

    if( !empty( $youtube_url ) ) {
      echo '<a target="_blank" aria-label="' . __( 'Follow Us On YouTube', 'sassys' ) . 
           '" rel="nofollow" class="youtube-brand" href="' . $youtube_url . 
           '"><i class="fab fa-youtube"></i></a>';
    }
  ?> 
</div>
