<?php

function aps_statistics(){
$c_id = get_current_user_id();
    $user = new WP_User($c_id);
    $u_role =  $user->roles[0];
	if(($u_role == "administrator") || ($u_role == "editor") || ($u_role == "author")){
add_submenu_page(
 'edit.php' ,
'Admin Statistics', 
 'Admin Statistics',
 $u_role, 
 'admin_statistics', 
  'my_admin_aps_statistics' 

);
	}
}


add_action('admin_menu', 'aps_statistics' );


function my_admin_aps_statistics()
{    

	if(is_admin() && !is_front_page() && !is_singular() && !is_archive()) {

	$args=array(
	'role__in'         => array('administrator','editor','author'),
	
	       
	
	'orderby'      => 'post_count',
	'order'        => 'DESC',
	
	
	'count_total'  => false
	);
	$Admins = get_users( $args );
// Array of WP_User objects.
foreach ( $Admins as $user )
{
	//echo $user -> display_name."<br/>";
	$admin_ids[]= $user -> ID;
	// echo $user -> ID;
	
	$errormessage=0;
}

	
?>

<form method="post" action="">
	
		    <div class="wrap" id="goleft">
			
      <h2>Pick a date</h2>
    <?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ((empty( $_POST['date_listed1'] ) && !empty( $_POST['date_listed2'])) ||(!empty( $_POST['date_listed1'] ) && empty( $_POST['date_listed2']) )) {
	  ?>
   <div id='message' class='updated error'><p><strong> Please fill both fields .</strong></p></div>
  <?php 
  }
  elseif ((empty( $_POST['date_listed1'] ) && empty( $_POST['date_listed2']))){
	   $_POST['date_listed1']='';
       $_POST['date_listed2']='';
  }
  else{
  
 
	
	}}
  ?>
     
         <input type="hidden" name="action" value="jk_save_youtube_option" />
        
 
         <?php wp_nonce_field( 'jk_op_verify' ); ?>
 
        
       
   
			<div  >
			<div >
				<label > between</label>
			</div>
			<div class="meta-td">
				<input type="text" class=" datepicker" name="date_listed1" id="date-listed1" value=""/>
			</div>
		    </div>
			
			<div >
			<div >
				<label >and </label>
			</div>
			<div class="meta-td">
				<input type="text" class=" datepicker" name="date_listed2" id="date-listed2" value=""/>
			</div>
		    </div>
			<br/>
			 <input type="submit" value="Show" class="button-primary"/>
      
			</div>
			<div  class="wrap ">
		
		
		<h2>Admin Post Count </h2> 
		</br>
		<table class="gridtable">
			<?php foreach ($admin_ids as $id){ ?>
				
				
 
  <tr>
    <td id="name"><?php 					
					$username = get_userdata($id);
					esc_html_e( $username -> display_name);
		 ?>
    </td>
    <td id="<?php esc_html__($id); ?>">
	published  -- <?php echo  count_user_posts( $id  );
						?>
						-- posts
    </td>
 
   
						<?php if ( empty( $_POST['date_listed1'] ) && empty( $_POST['date_listed2'] ) )
						{ } 
					else
						{
						
		                $datepicked1 = preg_replace("([^0-9/])", "", $_POST['date_listed1']);
		                $datepicked2 = preg_replace("([^0-9/])", "", $_POST['date_listed2']);	
							
						if (!empty( $datepicked1 ) && !empty( $datepicked2 ) )
							{
								
								$date1 = str_replace("/", "-", $datepicked1);
                                $d1 = explode("-"  , $date1);
					
				             	$date2 = str_replace("/", "-", $datepicked2);
                                $d2 = explode("-"  , $date2);
								
								  if(( !checkdate($d1[0], $d1[1], $d1[2])	|| !checkdate($d2[0], $d2[1], $d2[2]))&&($errormessage!=1))				
				            	     {
						             $errormessage+=1;
						             
				               	  ?>
                               <div id='message2' class='updated error'><p><strong> Please use the right date format such as : </br> mm/dd/yyyy</strong></p></div>
                  
			               	  <?php

				                  		}
  
		  
	   if (strtotime($datepicked1) > strtotime($datepicked2))
	   {
		   $temp=$datepicked2;
		   $datepicked2=$datepicked1;
		   $datepicked1=$temp;
	   }
	   
									 						 
                     else{  
					 if( checkdate($d1[0], $d1[1], $d1[2])	&& checkdate($d2[0], $d2[1], $d2[2]))
					 {
					 
					     ?>
	                      <td id="<?php esc_html__($id); ?>">
	   
	                     <?php
						echo " <div>-- ";
						echo aps_count_user_posts_by_date($id,$datepicked1,$datepicked2);
						echo ' -- ';
						echo " posts were published between ";
						esc_html_e($datepicked1);
						echo " and ";
						esc_html_e($datepicked2);
						echo "</div>";	
					 ?>
	   
	                  </td>
	                <?php    
					 }
					 }
	   
						}
						   }
			}
			
			?>
		
    

</table>
						
				

				
			
	</div>

	</form>	
		
		
	
		<?php
		
	


	}
}





function aps_count_user_posts_by_date( $userid, $date1, $date2 ) {
	$date = str_replace("/", "-", $date2);
$d = explode("-"  , $date);

	$args = array(
	    'author'         => $userid,
    'date_query' => array(
        array(
            'after'     => $date1,
            'before'     => array(
				'year'  => $d[2],
				'month' => $d[0],
				'day'   => $d[1],
				),
            'inclusive' => true,
        
        ),
       ));
      $query = new WP_Query( $args );

  $count = $query->post_count;
  return $count;
  }

function aps_process_jk_youtube_options()
{
   if ( !current_user_can( 'manage_options' ) )
   {
      wp_die( 'You are not allowed to be on this page.' );
   }
   // Check that nonce field
   check_admin_referer( 'jk_op_verify' );
 
   exit;
}

   add_action( 'admin_post_jk_save_youtube_option', 'aps_process_jk_youtube_options' );  



?>
