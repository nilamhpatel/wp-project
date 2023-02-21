<?php
  $company            = get_the_title();
  $imis_id            = get_post_meta( get_the_ID(), 'far_imis_id', true ); 
  $hba_prov           = get_post_meta( get_the_ID(), 'far_hba_prov', true ); 
  $hba                = get_post_meta( get_the_ID(), 'far_hba', true ); 
  $contact_first_name = get_post_meta( get_the_ID(), 'far_contact_first_name', true ); 
  $contact_last_name  = get_post_meta( get_the_ID(), 'far_contact_last_name', true ); 
  $service_area       = get_post_meta( get_the_ID(), 'far_hba', true ); 
  $address_one        = get_post_meta( get_the_ID(), 'far_address_one', true ); 
  $address_two        = get_post_meta( get_the_ID(), 'far_address_two', true ); 
  $address_three      = get_post_meta( get_the_ID(), 'far_address_three', true );
  $postal_code        = get_post_meta( get_the_ID(), 'far_postal_code', true ); 
  $country            = get_post_meta( get_the_ID(), 'far_country', true ); 
  $website            = get_post_meta( get_the_ID(), 'far_website', true ); 
  $email              = get_post_meta( get_the_ID(), 'far_contact_email', true ); 
  $phone              = get_post_meta( get_the_ID(), 'far_phone', true ); 
  $fax                = get_post_meta( get_the_ID(), 'far_fax', true ); 
  // $categories      = get_post_meta( get_the_ID(), 'far_categories', true ); 
  $sub_categories     = get_post_meta( get_the_ID(), 'far_sub_categories', true ); 
  $desc               = get_post_meta( get_the_ID(), 'far_company_description', true ); 
  $logo_id            = get_post_meta( get_the_ID(), 'far_logo_id', true ); 
  $province           = get_the_terms( get_the_ID(), 'province' )[0] -> name; 
  $city               = get_the_terms( get_the_ID(), 'city' )[0] -> name; 
  $contractor_type    = get_the_terms( get_the_ID(), 'contractor-type' )[0] -> name; 
?>
  <div class="row">
    <div class="col">
      <div class="find-a-renovator-card-container">
        <div class="d-flex">
          <div class="w-100">
            <h2 class="card-title"><?= $company; ?></h2>

            <?php 
              // if( !empty( $imis_id ) ) {
              //   echo '<p>' . __( 'iMIS ID:', 'sassys' ) . ' <span>' . $imis_id . '</span></p>';
              // } 

              // if( !empty( $hba_prov ) ) {
              //   echo '<p>' . __( 'HBA Provine:', 'sassys' ) . ' <span>' . $hba_prov . '</span></p>';
              // } 

              // if( !empty( $hba ) ) {
              //   echo '<p>' . __( 'HBA:', 'sassys' ) . ' <span>' . $hba . '</span></p>';
              // } 
              
              if( !empty( $contact_first_name ) ) {
                echo '<p>' . __( 'Contact Name:', 'sassys' ) . 
                     ' <span>' . $contact_first_name . ' ' . $contact_last_name . '</span></p>';
              } 

              if( !empty( $address_one ) ) {
                echo '<p>' . __( 'Address:', 'sassys' ) . ' <span>' . $address_one . '</span></p>';
              } 

              // if( !empty( $address_two ) ) {
              //   echo '<p>' . __( 'Address 2:', 'sassys' ) . ' <span>' . $address_two . '</span></p>';
              // } 

              // if( !empty( $address_three ) ) {
              //   echo '<p>' . __( 'Address 3:', 'sassys' ) . ' <span>' . $address_three . '</span></p>';
              // } 

              // if( !empty( $postal_code ) ) {
              //   echo '<p>' . __( 'Postal Code:', 'sassys' ) . ' <span>' . $postal_code . '</span></p>';
              // } 

              // if( !empty( $country ) ) {
              //   echo '<p>' . __( 'Country:', 'sassys' ) . ' <span>' . $country . '</span></p>';
              // }

              if( !empty( $province ) ) {
                echo '<p>' . __( 'Province:', 'sassys' ) . ' <span>' . $province . '</span></p>';
              } 

              if( !empty( $city ) ) {
                echo '<p>' . __( 'City:', 'sassys' ) . ' <span>' . $city . '</span></p>';
              } 

              if( !empty( $service_area ) ) {
                echo '<p>' . __( 'Service Region:', 'sassys' ) . ' <span>' . $service_area . '</span></p>';
              }

              if( !empty( $contractor_type ) ) {
                echo '<p>' . __( 'Contractor Type:', 'sassys' ) .  
                     ' <span>' . $contractor_type . '</span></p>';
              } 

              // if( !empty( $sub_categories ) ) {
              //   echo '<p>' . __( 'Sub Categories:', 'sassys' ) . 
              //        ' <span>' . $sub_categories . '</span></p>';
              // } 

              if( !empty( $phone ) ) {
                echo '<p>' . __( 'Phone:', 'sassys' ) . 
                     ' <a href="tel:' . $phone . '"><span>' . $phone . '</span></a></p>';
              } 

              // if( !empty( $fax ) ) {
              //   echo '<p>' . __( 'Fax:', 'sassys' ) . ' <span>' . $fax . '</span></p>';
              // }
        
              if( !empty( $email ) ) {
                echo '<p>' . __( 'Email:', 'sassys' ) . 
                     ' <a href="mailto:' . $email . '"><span>' . $email . '</span></a></p>';
              }
              
              if( !empty( $website ) ) {
                echo '<p>' . __( 'Website:', 'sassys' ) . 
                     ' <a href="mailto:' . $website . '" target="_blank"><span>' . $website .
                     '</span></a></p>';
              }
            ?>
          </div>

          <?php 
            if( !empty( $logo_id ) ) { 
              echo '<div class="image-container">';
                echo wp_get_attachment_image( $logo_id, array( 220, 220 ) );
              echo '</div>';
            }
            
            else if( !empty( $imis_id ) ) {
              echo '<div class="image-container">';
                echo '<img src="http://www.chba.ca/images/Corp_Logos/' . $imis_id . '.png" 
                      alt="" width="180" height="180" />';
              echo '</div>';
            }
          ?>
        </div>

        <?php 
          if( !empty( $desc ) ) echo '<div class="description-container">' . wpautop( $desc ) . '</div>';
        ?>
      </div>
    </div>
  </div>
