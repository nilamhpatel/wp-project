<?php
/**
 * TODO: if front page, point search to find-a-reno page!
 */

  $cat_prov = get_terms( array( 'taxonomy' => 'province', ) );
  $cat_city = get_terms( array( 'taxonomy' => 'city', ) );
  $cat_type = get_terms( array( 'taxonomy' => 'contractor-type', ) );
?>

<form class="search-renovator-form-container" action="#search-renovator-anchor">
  <div class="column-container">
    <div class="search-field-container ginput_container_select">
      <select id="province" name="province">
        <option value=""><?= __( 'Province', 'sassys' ); ?></option>
        <?php 
          foreach( $cat_prov as $province ) {
            $label    = $province -> name;
            $selected = '';

            if( $query_province == $label ) $selected = 'selected';

            echo '<option value="' . $label . '" ' . $selected . '>' . $label . '</option>';
          } 
        ?>
      </select>
    </div>

    <div class="search-field-container ginput_container_select">
      <select id="city" name="city">
        <option value=""><?= __( 'City', 'sassys' ); ?></option>
        <?php 
          foreach( $cat_city as $city ) {
            $label    = $city -> name;
            $selected = '';

            if( $query_city == $label ) $selected = 'selected';

            echo '<option value="' . $label . '" ' . $selected . '>' . $label . '</option>';
          } 
        ?>
      </select>
    </div>
  </div>

  <div class="column-container">
    <div class="search-field-container ginput_container_select">
      <select id="type" name="type">
        <option value=""><?= __( 'Contractor Type', 'sassys' ); ?></option>
        <?php 
          foreach( $cat_type as $type ) {
            $label    = $type -> name;
            $selected = '';

            if( $query_type == $label ) $selected = 'selected';

            echo '<option value="' . $label . '" ' . $selected . '>' . $label . '</option>';
          } 
        ?>
      </select>
    </div>

    <div class="input-field-container gfield text-field">
      <label for="name"><?= __( 'Company Name', 'sassys' ); ?></label>

      <input placeholder="Company Name" id="name" value="<?= $reno_contains; ?>" 
             type="text" name="contains" class="" />
    </div>
  </div>

  <input type="submit" name="renovator-search" value="<?= __( 'search', 'sassys' ); ?>" />
</form>
