<?php
/**
 * Template Name:Contact Page template
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();  
?>

<div class="reno-contact-page-container">
<?php include( 'components/page-header.php' ); ?>
    <div class="reno-contact-container">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-6 col-xl-7">
                    <div class="contact-green-check">                       
                        <div class="left-contact-contact">
                            <div class="green-check-contact">
                                <img src="<?php echo get_stylesheet_directory_uri() . '/images/logo-green-check.svg'; ?>" class="green-check-img" alt="sassys Renovator" width="" height="" />
                            </div>       
                            <?php 
                                $contact_left_text = get_post_meta(get_the_ID(), "contact_left_text", true);
                                $contact_button_text = get_post_meta(get_the_ID(), "contact_button_text", true);
                                $contact_button_link = get_post_meta(get_the_ID(), "contact_button_link", true);     
                                if($contact_left_text != ""){                           
                            ?>
                            <div class="green-box-contact">                    
                                <p class="find-renovator-txt"><?php echo $contact_left_text; ?></p>
                                <a href="<?php echo $contact_button_link; ?>" class="green-button"><?php echo $contact_button_text; ?></a>
                            </div>     
                            <hr />
                            <?php } ?>  
                        </div>

                        <?php 
                           while ( have_posts() ) {
                            echo '<div class="contact-container">';
                                the_post();
                                the_content();
                            echo '</div>';
                            }
                        ?>   
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-6 col-xl-5">
                <?php 
                $contactUsFormId = (int) get_post_meta(get_the_ID(), "contact_form_id", true);
                if($contactUsFormId > 0){ ?>
                        <div class="gformright">
                            <div class="gforminquiries">                            
                                <?php gravity_form($contactUsFormId, true, true, false, null, true); ?>                           
                            </div>
                        </div>
                <?php } ?>  
                </div>
            </div>
                     
        </div>
    </div>

    <?php 
        include( 'components/advertisement.php' ); 
    ?>
</div>

 

<?php
get_footer();
