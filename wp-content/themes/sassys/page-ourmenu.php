<?php
	/**
	 * Template Name: Our Menu template
	 */

	// Exit if accessed directly.
	defined( 'ABSPATH' ) || exit;

  global $wp;

  $builders_page = home_url( $wp -> request );
  $builder_terms = get_terms( array(
    'taxonomy'       => 'menucategory',
    'posts_per_page' => -1,
  ) );

  $all_active       = 'active';
  $menucategory = '';
  $operator_builder = 'NOT_EXISTS';

  if( isset( $_GET[ 'menucategory' ] ) && !empty( $_GET[ 'menucategory' ] ) ) {
    $menucategory = $_GET[ 'menucategory' ];
    $operator_builder = 'IN';
    $all_active = '';
  }

  $posts_per_page = 12;
  $builder_posts  = new WP_Query( array(
    'post_type'      => 'our-menu',
    'posts_per_page' => $posts_per_page,
    'tax_query'      => array(
      array(
        'taxonomy' => 'menucategory',
        'field'    => 'slug',
        'terms'    => $menucategory,  
        'operator' => $operator_builder
      ),
    )
  ) );      

  $builder_posts_count = $builder_posts -> found_posts;      
  $max_builder_pages   = $builder_posts -> max_num_pages - 1;

  $builders_ad = get_post_meta( get_the_ID(), 'builders_shortcode_ad', true );

	get_header();  
?>


<div class="reno-our-menus-page-container">
  <?php include( 'components/page-header.php' ); ?>

  <div class="reno-builder-category-container">
    <div class="container">
      <div class="reno-builder-category-row row">
        <div class="col" id="totop">     

          <div class="reno-category-container" style="width: fit-content; text-align: center; margin: 0 auto;">
            <ul>            
              <li class="">
                <a onclick="tabmenu('tab1');" value="tab1"><?= __( 'Pizza', 'sassys' ); ?></a>
              </li>            
              <li class="">
                <a onclick="tabmenu('tab2');" value="tab2"><?= __( 'Southern Fried Chicken', 'sassys' ); ?></a>
              </li>
              <li class="">
                <a onclick="tabmenu('tab3');" value="tab3"><?= __( 'Appetizers and Sides', 'sassys' ); ?></a>
              </li>
              <li class="">
                <a onclick="tabmenu('tab4');" value="tab4"><?= __( 'Hamburgers', 'sassys' ); ?></a>
              </li>
              <li class="">
                <a onclick="tabmenu('tab5');" value="tab5"><?= __( 'Breakfast', 'sassys' ); ?></a>
              </li>
              <li class="">
                <a onclick="tabmenu('tab5');" value="tab5"><?= __( 'Beverages', 'sassys' ); ?></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="menulistmain-container" id="tab1">
      <div class="container">
        <div class="row our-menu-list-container">              
            <div class="col-12 col-lg-8  menuslist-col-12">
              
                <div class="welcometext" id="11">
                  <img src="/wp-content/themes/sassys/images/08.png" alt="" width="" height="">
                  <h2>Pizza</h2>              
                </div>

                <div class="menuslist-row">    

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Cheese</p>
                      <span>Sauce, Mozzaralla Cheese & Chedar Cheese</span>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $8.5</p>
                      <p><span>Medium:</span> $12.50</p>
                      <p><span>Large:</span> $15.50</p>
                    </div>
                  </div>


                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Pepperoni</p>
                      <span>Sauce, Mozzarella Cheese & Pepperoni</span>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $9.99</p>
                      <p><span>Medium:</span> $15.50</p>
                      <p><span>Large:</span> $17.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Delux</p>
                      <span>Sauce, Mozzarella Cheese, Pepperoni, Mushrooms, Bacon & Green Peppers</span>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $12.50</p>
                      <p><span>Medium:</span> $18.50</p>
                      <p><span>Large:</span> $23.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Vegetarian</p>
                      <span>Sauce, Mozzarella Cheese, Mushrooms, Green Peppers, Onion, Green Olives & Tomatoes</span>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $12.50</p>
                      <p><span>Medium:</span> $18.50</p>
                      <p><span>Large:</span> $23.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Mega Meat</p>
                      <span>Sauce, Mozzarella Cheese, Pepperoni, Bacon,Beef & Sausage</span>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $12.50</p>
                      <p><span>Medium:</span> $18.50</p>
                      <p><span>Large:</span> $23.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Garlic Chicken</p>
                      <span>Garlic Spread, Mozzarella Cheese, Cheddar Cheese, Bacon, Chicken & Onion</span>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $12.50</p>
                      <p><span>Medium:</span> $18.50</p>
                      <p><span>Large:</span> $23.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Tropical Pulled Pork</p>
                      <span>Sauce, Mozzarella Cheese, Pulled Pork, Pineple, Hot Peppers and BBQ Sause</span>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $13.50</p>
                      <p><span>Medium:</span> $19.99</p>
                      <p><span>Large:</span> $25.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Hawaiian</p>
                      <span>Sauce, Mozzarealla Cheese, Ham & Pineple</span>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $12.50</p>
                      <p><span>Medium:</span> $18.50</p>
                      <p><span>Large:</span> $23.50</p>
                    </div>
                  </div>
                  

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Bacon Cheese Burger</p>
                      <span>Sauce, Mozzarella Cheese, Cheddar Cheese, Bacon & Beef</span>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $11.50</p>
                      <p><span>Medium:</span> $17.50</p>
                      <p><span>Large:</span> $21.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Sassy's Chicken</p>
                      <span>Garlic Spread,BBQ Sauce, Mozzarella Cheese, Mushrooms, Chicken, Onion & Tomatoes</span>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $12.50</p>
                      <p><span>Medium:</span> $18.50</p>
                      <p><span>Large:</span> $23.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Canadian</p>
                      <span>Sauce, Mozzarella Cheese, Pepperoni, Mushrooms & Bacon</span>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $11.99</p>
                      <p><span>Medium:</span> $17.99</p>
                      <p><span>Large:</span> $21.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Smoked Pulled Pork</p>
                      <span>Pizza & BBQ Sauce, Mozzarealla Cheese, Pulled Pork, Mushrooms, Green Peppers, Onions</span>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $14.50</p>
                      <p><span>Medium:</span> $20.99</p>
                      <p><span>Large:</span> $26.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Panzerottis</p>
                      <span>Cheese & Pepperoni</span>
                      <span>Additional Toppings $1.50 each</span>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Reguler:</span> $10.99</p>
                      <p>&nbsp;</p>
                      <p>&nbsp;</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Garlic Strips</p>
                      <span>A Medium Pizza crust topped with garlic spread, Mozzarella Cheese, & Cheddar Cheese</span>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $8.50</p>
                      <p><span>Medium:</span> $12.50</p>
                      <p><span>Large:</span> $15.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Loaded Nachos</p>
                      <span>Green Peppers, Beef,onion, Tomato, Mozzarella Cheese & Cheddar Cheese. Served with Salsa.</span>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $12.99</p>                      
                      <p><span>Large:</span> $17.50</p>
                      <p>&nbsp;</p>
                    </div>
                  </div>

                </div>
            </div>
          </div>
          </div>
        </div>

        <div class="menulistmain-container grey-bg" id="tab2">
          <div class="container">      
            <div class="row our-menu-list-container">              
              <div class="col-12 col-lg-8  menuslist-col-12">

                <div class="menuslist-row"> 

                <div class="welcometext" id="22">
                  <img src="/wp-content/themes/sassys/images/08.png" alt="" width="" height="">
                  <h2>Chicken</h2>  
                  <h3>Southern Fried Chicken</h3>     
                </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Snack</p>
                      <span>2 Piece Chicken with Fries & Can Pop</span>
                    </div>
                    <div class="menuslist-right">
                      <p>$8.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Lunch</p>
                      <span>2 Piece Chicken with Fries, Salad, Roll & Can Pop</span>
                    </div>
                    <div class="menuslist-right">
                      <p>$10.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Dinner</p>
                      <span>3 Piece Chicken with Fries, Salad, Roll & Can Pop</span>
                    </div>
                    <div class="menuslist-right">
                      <p>$12.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Hungry Man</p>
                      <span>4 Piece Chicken with Fries, Salad, Roll & Can Pop</span>
                    </div>
                    <div class="menuslist-right">
                      <p>$12.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>10 PC Bucket Deal</p>
                      <span>Medium Fry & 2 Medium Salads</span>
                    </div>
                    <div class="menuslist-right">
                      <p>$31.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>15 PC Bucket Deal</p>
                      <span>Large Fry & 2 Large Salads</span>
                    </div>
                    <div class="menuslist-right">
                      <p>$39.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>20 PC Bucket Deal</p>
                      <span>Large Fry & 3 Large Salad</span>
                    </div>
                    <div class="menuslist-right">
                      <p>$49.99</p>
                    </div>
                  </div>


                  <div class="welcometext" style="margin-bottom: 0; margin-top: 20px;">   
                    <h3>Just the Chicken</h3>     
                  </div>

                  <div class="menuslists nodots">
                    <div class="menuslist-left">
                      <p>10 Piece</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$24.99</p>
                    </div>
                  </div>

                  <div class="menuslists nodots">
                    <div class="menuslist-left">
                      <p>15 Piece</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$33.99</p>
                    </div>
                  </div>

                  <div class="menuslists nodots">
                    <div class="menuslist-left">
                      <p>20 Piece</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$41.99</p>
                    </div>
                  </div>


                  <div class="welcometext" style="margin-bottom: 0; margin-top: 20px;">   
                    <h3>Chicken Wings</h3> 
                    <p class="titleinfo">Mild, Medum, Hot, Honey Garlic or BBQ</p>
                  </div>

                  <div class="menuslists nodots">
                    <div class="menuslist-left">
                      <p>10 Wings</p>
                    </div>
                    <div class="menuslist-right">    
                      <p style="padding-top: 10px;">$12.99</p>
                    </div>
                  </div>

                  <div class="menuslists nodots">
                    <div class="menuslist-left">
                      <p>20 Wings</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$21.99</p>
                    </div>
                  </div>

                  </div>
                </div>
              </div>
            </div>
          </div>


          <div class="menulistmain-container white-bg" id="tab3">
            <div class="container">      
              <div class="row our-menu-list-container">              
                <div class="col-12 col-lg-8  menuslist-col-12">

                  <div class="menuslist-row"> 
                    

                  <div class="welcometext" id="33">
                  <img src="/wp-content/themes/sassys/images/08.png" alt="" width="" height="">
                  <h2>Appetizers and Sides</h2>     
                </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>French Fries</p>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $4.99</p>
                      <p><span>Medium:</span> $5.99</p>
                      <p><span>Large:</span> $6.99</p>
                    </div>
                  </div>


                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Chilli Cheese Fries</p>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $6.99</p>
                      <p><span>Medium:</span> $9.99</p>
                      <p><span>Large:</span> $11.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Poutine</p>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $5.99</p>
                      <p><span>Medium:</span> $7.99   </p>
                      <p><span>Large:</span> $9.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Onion Rings</p>
                    </div>
                    <div class="menuslist-right">
                      <p>$5.75</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Pickle Spears</p>
                      <span>4 Pieces Served with Ranch</span>                      
                    </div>
                    <div class="menuslist-right">
                      <p>$5.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Battered Mushrooms</p>
                      <span>Served with Ranch</span>                
                    </div>
                    <div class="menuslist-right">
                      <p>$6.49</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Mozzarella Sticks</p>
                      <span>6 Pieces Served with Marinara Sauce</span>             
                    </div>
                    <div class="menuslist-right">
                      <p>$6.49</p>
                    </div>
                  </div>


                  </div>
                </div>
              </div>
            </div>
          </div>


          <div class="menulistmain-container grey-bg" id="tab4">
            <div class="container">      
              <div class="row our-menu-list-container">              
                <div class="col-12 col-lg-8  menuslist-col-12">

                  <div class="menuslist-row">                     

                  <div class="welcometext" id="44">
                    <img src="/wp-content/themes/sassys/images/08.png" alt="" width="" height="">
                    <h2>Hamburgers</h2>     
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Jr Hamburger with Fries & Can of Pop</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$9.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Hamburger with Fries & Can of Pop</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$11.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Cheese Burger with Fries & Can of Pop</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$12.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Bacon Cheese Burger with Fries & Can of Pop</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$12.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Peameal Cheddar Burger with Fries & Can of Pop</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$12.99</p>
                    </div>
                  </div>




                  </div>
                </div>
              </div>
            </div>
          </div>


          <div class="menulistmain-container white-bg" id="tab5">
            <div class="container">      
              <div class="row our-menu-list-container">              
                <div class="col-12 col-lg-8  menuslist-col-12">

                  <div class="menuslist-row">                     

                  <div class="welcometext" id="55">
                    <img src="/wp-content/themes/sassys/images/08.png" alt="" width="" height="">
                    <h2>Breakfast</h2>    
                    <p>Make it a combo with Hashbrown & Large Coffee for $3.50</p> 
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Bagel with Butter</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$2.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Bagel with Cream Cheese</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$3.25</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Bacon & Egg Breakfast Bagel</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$4.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Sausae & Egg Breakfast Bagel</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$4.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Ham & Egg Breakfast Bagel</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$4.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>BLT Bagel</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$4.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Peameal Breakfast Sandwich</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$4.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Breakfast Wrap</p>
                      <span>egg, Ham, bacon, onion& Green peppers</span>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$4.50</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Farmer's Breakfast Wrap</p>
                      <span>Cheddar Cheese, Egg, Hashbrown, Bacon </span>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$5.25</p>
                    </div>
                  </div>


                  <div class="welcometext" style="margin-bottom: 0; margin-top: 20px;">   
                    <h2>Breakfast Sides</h2>    
                  </div>


                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Hashbrown</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$1.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Muffin</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$1.99</p>
                    </div>
                  </div>

                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Donut</p>
                    </div>
                    <div class="menuslist-right">
                      <p style="padding-top: 10px;">$1.99</p>
                    </div>
                  </div>

                  <div class="welcometext" style="margin-bottom: 0; margin-top: 20px;">   
                    <h2>Breakfast Sides</h2>    
                  </div>

                  <div class="menuslists" id="66">
                    <div class="menuslist-left">
                      <p>Beverages</p>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $1.89</p>
                      <p><span>Medium:</span> $2.29</p>
                      <p><span>Large:</span> $2.55</p>
                    </div>
                  </div>
                  
                  <div class="menuslists">
                    <div class="menuslist-left">
                      <p>Lipton Tea</p>
                    </div>
                    <div class="menuslist-right">
                      <p><span>Small:</span> $1.89</p>
                      <p><span>Medium:</span> $2.29</p>
                      <p><span>Large:</span> $2.55</p>
                    </div>
                  </div>

                </div>

            </div>
        </div>
      </div>

    
    </div>
  </div>


  <a onclick="tabmenu('totop');" value="totop" id="mybtn" title="Go to top">Top</a>


</div>


<script type="application/javascript">
function tabmenu(buttonObject){ 
    var value = buttonObject;
    var target = document.getElementById(value);    
    target.scrollIntoView(); 
    /*
      if(target) {
        var siblings = target.parentNode.getElementsByTagName("DIV");
        
        for(i=0;i<siblings.length;i++){
            siblings[i].style.display = "none";
        }
        target.style.display = "block";
        
        
      }
    */
}
</script>

<?php
	get_footer();
