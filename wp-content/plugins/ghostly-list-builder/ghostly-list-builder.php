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
*/

// ===============================================================================
// 1. Hooks

// Registers custom shortcodes on init
add_action('glb_form', 'glb_register_shortcodes');

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
