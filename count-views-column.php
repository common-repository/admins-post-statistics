<?php
function aps_getmyPostViews($postID){

		
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
	
    return $count.' Views';
	}

add_action('init','aps_getmyPostViews');


function aps_setmyPostViews($postID) {
     
	
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
	}


add_action('init','aps_setmyPostViews');




// Remove issues with prefetching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);


add_filter('manage_posts_columns', 'aps_posts_column_views');
 add_action('manage_posts_custom_column', 'aps_posts_custom_column_views',5,2); 
 function aps_posts_column_views($defaults)
     { $defaults['post_views'] = __('Views');
     	 return $defaults; }
    function aps_posts_custom_column_views($column_name, $id)
      { 
         if($column_name === 'post_views')
              { echo aps_getmyPostViews(get_the_ID()); }

      }
	
function aps_filter_myposts_list(&$query)
{
	if(is_admin() && !is_front_page() && !is_singular() && !is_archive()) 
	{
		
		$args=array(
	'role__in'         => array('administrator','editor','author'),
	
	       
	
	'orderby'      => 'post_count',
	'order'        => 'ASC',
	
	
	'count_total'  => false
	);
	$Admins = get_users( $args );
// Array of WP_User objects.
          foreach ( $Admins as $user )
               {
	
        	$admin_ids[]= $user -> ID;
	
                  }

	if ( $query->is_main_query() )
	{    
       $query->set( 'author__in' ,$admin_ids ); 
    }
      
	}
}
add_action('pre_get_posts', 'aps_filter_myposts_list');
 

function aps_loop_start( $query ){
	
     if( $query->is_main_query() ){

          aps_setmyPostViews(get_the_ID());
 
          aps_getmyPostViews(get_the_ID());


     }
}
// within the loop in single.php/index.php 

 add_action( 'loop_start', 'aps_loop_start' );
	  ?>