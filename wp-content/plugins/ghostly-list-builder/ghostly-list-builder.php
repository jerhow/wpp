<?php

/*
Plugin Name: Ghostly List Builder
Plugin URI: https://github.com/jerhow/wpp
Description: An email list building plugin for WordPress.
Version: 0.1
Author: Jerry Howard
Author URI: https://github.com/jerhow
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text-Domain: ghostly-list-builder
*/

/*
TOC:
1. Hooks
- Registers custom shortcodes on init with add_action()

2. Shortcodes
- glb_register_shortcodes()
- glb_form_shortcode()

3. Filters
4. External scripts
5. Actions
6. Helpers
7. Custom post types
8. Admin pages
9. Settings
10. Misc.
*/

// ===============================================================================
// 1. Hooks

// Registers custom shortcodes on init
add_action('init', 'glb_register_shortcodes');

// ===============================================================================
// 2. Shortcodes

// Register custom shortcodes with WordPress
function glb_register_shortcodes() {
  add_shortcode('glb_form', 'glb_form_shortcode'); // tag, callback function
}

// Return email capture form HTML
function glb_form_shortcode($args, $content="") {
  $output = '
    <div class="glb">

      <form id="glb_form" name="glb_form" class="glb-form" method="post">

        <p class="glb-input-container">
          <label>Your Name</label><br />
          <input type="text" name="glb_fname" placeholder="First Name" />
          <input type="text" name="glb_lname" placeholder="Last Name" />
        </p>

        <p class="glb-input-container">
          <label>Your Email</label><br />
          <input type="text" name="glb_email" placeholder="ex. you@email.com" />
        </p>';

  // Including content in our form if it is passed in, wrapping it in a div that we can target,
  // and running it through wpautop() to replace double line-breaks with paragraph elements.
  if (strlen($content) > 0) {
    $output .= '<div class="glb-content">' . wpautop($content) . '</div>';
  }

  $output .= '
        <p class="glb-input-container">
          <input type="submit" name="glb_submit" value="Sign Me Up!" />
        </p>

      </form>

    </div>
  ';

  return $output;
}

function glb_add_subscriber_metaboxes($post) {
  add_meta_box(
    'glb-subscriber-details',
    'Subscriber Details',
    'glb_subscriber_metabox',
    'glb_subscriber',
    'normal',
    'default'
  );
}

add_action('add_meta_boxes_glb_subscriber', 'glb_add_subscriber_metaboxes');

function glb_subscriber_metabox() {
  ?>

  <div class="glb-field-row">
    <div class="glb-field-container">
      <p>
        <label>First Name *</label><br />
        <input type="text" name="glb_first_name" required="required" class="widefat" />
      </p>
    </div>
  </div>

  <div class="glb-field-row">
    <div class="glb-field-container">
      <p>
        <label>Last Name *</label><br />
        <input type="text" name="glb_last_name" required="required" class="widefat" />
      </p>
    </div>
  </div>

  <div class="glb-field-row">
    <div class="glb-field-container">
      <p>
        <label>Email *</label><br />
        <input type="email" name="glb_email" required="required" class="widefat" />
      </p>
    </div>
  </div>

  <div class="glb-field-row">
    <div class="glb-field-container">
      <label>Lists</label><br />
      <ul>

        <?php
        global $wpdb;

        $list_query = $wpdb->get_results(
          "SELECT id, post_title FROM {$wpdb->posts} 
           WHERE post_type = 'glb_list' 
           AND post_status IN ('draft', 'publish')"
        );

        if( !is_null($list_query) ) {
          foreach ($list_query as $list) {
            echo '<li><label><input name="glb_list[]" type="checkbox" 
                  value="' . $list->id . '" />' . $list->post_title . '</label></li>';
          }
        }
        ?>

      </ul>
    </div>
  </div>

  <?php
}
