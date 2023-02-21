<div class="reno-social-sharing-container sticky-top">
  <?php
    // echo '<span class="share-text">' . __( 'Share', 'sassys' ) . '</span>';

    echo '<a target="_blank" title="Share on Facebook" class="share-via-facebook"
            href="http://www.facebook.com/share.php?u=' . get_permalink() . '">
            <span class="sr-only">' . __( 'Share on Facebook', 'sassys' ) . '</span>
            <i class="fab fa-facebook-f"></i>
          </a>';
    
    echo '<a target="_blank" title="Share on Twitter" class="share-via-twitter"
             href="https://twitter.com/intent/tweet?url=' . get_permalink() . '">
            <span class="sr-only">' . __( 'Share on Twitter', 'sassys' ) . '</span>
            <i class="fab fa-twitter"></i>
          </a>';

    
    echo '<a target="_blank" title="Share on Pinterest" class="share-via-pinterest"
             href="https://www.pinterest.ca/pin/create/button/?url=' . get_permalink() . '">
            <span class="sr-only">' . __( 'Share on Pinterest', 'sassys' ) . '</span>
            <i class="fab fa-pinterest"></i>
          </a>';
    
    // echo '<a target="_blank" title="Share on LinkedIn" 
    //          href="https://www.linkedin.com/shareArticle?mini=true&url=' . get_permalink() . '">
    //         <span class="sr-only">' . __( 'Share on LinkedIn', 'sassys' ) . '</span>
    //         <i class="fab fa-linkedin-in"></i>
    //       </a>';

    
    echo '<a target="_blank" class="share-via-email" href="mailto:?subject=' . get_the_title() .
           '&body=' . get_the_title() . ': ' . get_permalink() . '" title="Share via Email">
            <span class="sr-only">' . __( 'Share via Email', 'sassys' ) . '</span>
            <i class="far fa-envelope"></i>        
          </a>';
  ?> 
</div>