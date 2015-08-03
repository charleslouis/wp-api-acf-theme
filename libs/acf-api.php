<?php


function enrich_related_post_with_own_acf( $related_post_ID, $original_related_post ){
  
  // Check if get_fields() exists and if there are ACFs in this post
  if ( get_fields($related_post_ID) ) {
      
    // Get the related post own ACFs
    $related_post_acf_fields = get_fields($related_post_ID);

    foreach ($related_post_acf_fields as $key => $value) {      
      // Prefix with ACF to avoid conflict with core wordpress
      $key = 'acf_' . $key;
      $related_post_acf_part[$key] = $value;
    }

    //then merge the core fields ($value)
    // and the ACFs ($related_post_acf_fields) into this post object
    $tmp_value = (object) array_merge((array) $original_related_post, (array) $related_post_acf_part);
    $original_related_post = $tmp_value;

  }

  // This is the original_related_post enriched with its own ACFs
  return $original_related_post;
}






// Inject Advance Custom Fields into the API
function json_api_prepare_post( $post_response, $post, $context ) {


  // we don't want to show twitter API credentials on the site
  // This code could be adjusted to "hide" any page from the API
  
  // $tweeter_feed_params_page = get_page_by_path( 'twitter-feed-parameters' );
  // if ( $tweeter_feed_params_page->ID === $post['ID']) {
  //     return $post_response;
  // }

  // Check if get_fields() exists and if there are ACFs in this post
  if( get_fields($post['ID'] ) ){

    // if so get ACFs
    $acf_fields = get_fields($post['ID']);


    // then loop on every ACF of the current post
    foreach ($acf_fields as $key => $value) {

      // Prefix with ACF to avoid conflict with core wordpress
      $key = 'acf_' . $key;

      // As ACF related Post Object are return without their own ACFs, we need to recursively find for them

      ////////////////////////////////////////
      // SINGLE POST OBJECT
      ////////////////////////////////////////      
      if( null != $value->ID ){
        // there are godd chances we are returning a single post object
        // so let's get this posts own ACFs
        
        // temporary store $value
        $related_post = $value;
        
        $related_post_ID = $value->ID;
        $related_post = enrich_related_post_with_own_acf( $related_post_ID, $related_post );
        
        //put it back, once enriched
        $value = $related_post;
      }
      ////////////////////////////////////////
      // END of SINGLE POST OBJECT
      ////////////////////////////////////////      




      ////////////////////////////////////////
      // REPEATER of POST OBJECTS
      // Works with a Post Object returned format
      //
      // 
      //  
      // TO_DO : 
      // Make it work with a Post ID returned format
      ////////////////////////////////////////
      if( is_array($value) ){
        if ( null != $value[0]['post'] ) {        
          // there are godd chances we are returning a post object repeater
          // so let's get each posts own ACFs
          
          // temporary store $value
          $post_repeater = $value;

          // loop in the repeater
          foreach ($post_repeater as $post_key => $post_value) {
            
            // Get the post and it's ID
            $repeated_post = $post_repeater[$post_key]['post'];
            $repeated_post_ID = $repeated_post->ID;

            // Get repeated post own ACFs
            $repeated_post = enrich_related_post_with_own_acf( $repeated_post_ID, $repeated_post );
            $post_repeater[$post_key]['post'] = $repeated_post;
          }

          //put it back, once enriched
          $value = $post_repeater;
        }

      }


      ////////////////////////////////////////
      // END OF REPEATER of POST OBJECTS
      ////////////////////////////////////////

      ////////////////////////////////////////
      // CUSTOM POST TYPE : get a list of CPT posts enriched with their own ACFs(gmap, adress)
      // How doeas it work ?
      // Imagine you have a page ALBUMS, in which you have a repeater (CPT_ordner) of post objects references
      // This code will get every post of the repeater, and get the ACF of these posts
      ////////////////////////////////////////
      if( $key === 'acf_CPT_page' ){
        // if so get main CPT page ACF
        $CPT_acf = get_fields($value);
        $CPT_acf = $CPT_acf['CPT_ordner'];
        
          // loop in the repeater
          foreach ($CPT_acf as $post_key => $value) {
            
            // Get the post and it's ID
            $repeated_post = $CPT_acf[$post_key]['post'];
            $repeated_post_ID = $repeated_post->ID;

            // Get repeated post own ACFs
            $repeated_post = enrich_related_post_with_own_acf( $repeated_post_ID, $repeated_post );
            $CPT_acf[$post_key]['post'] = $repeated_post;
          }

          //put it back, once enriched
          $value = $CPT_acf;
      }
      ////////////////////////////////////////
      // END OF CUSTOM POST TYPE
      ////////////////////////////////////////

      // Add this ACF untouched or enriched with Post Object ACFs if needed
      $post_response[$key] = $value;

    }
    return $post_response;
  }
}
add_filter( 'json_prepare_post', 'json_api_prepare_post', 10, 3 );