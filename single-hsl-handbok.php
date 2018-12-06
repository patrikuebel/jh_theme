<?php

//* Template Name: HSL-single

if ( current_user_can('subscriber') || current_user_can('administrator') )  {

  // remove the default genesis primary sidebar
  remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

  // add an action hook to call the function for your custom sidebar
  add_action( 'genesis_sidebar', 'child_do_hsl_sidebar' );

  genesis();
}
else {
   wp_redirect( wp_login_url() );
}
