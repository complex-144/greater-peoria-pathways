<?php
/*
   Plugin Name: Greater Peoria Pathways
   Description: Greater Peoria EDC highschool pathways plugin.
   Version: 1.3.1
   Author: ICC Web Services
   Author URI: icc.edu
   License: GPL2
*/

/*Need to remove the default values and just use the input name [0] for first input in each section the order is off when returning values from the databases because the alphabetical order doesnt know about the default value for each seaction EX: default(1st) value = b, 2nd value = c 3rd value = a returns as b -> a-> c  */

?>
<?php 

    defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
* Register and Enqueue Frontend CSS
* gp-pathways-css Styles greater peoria pathway display
*
*/
add_action( 'wp_enqueue_scripts', 'gp_pathways_styles' );

function gp_pathways_styles() {
    wp_register_style( 'gp_pathways_css', plugins_url('css/gp-pathways.css', __FILE__ ));
    wp_enqueue_style( 'gp_pathways_css');
}


/**
* Register and Enqueue backend JS & CSS
* Enqueue Wordpress jQuery
* Localize Wordpress admin-ajax.php
*
*/
add_action( 'admin_enqueue_scripts', 'gp_pathways_scripts_styles' );

function gp_pathways_scripts_styles() {
    wp_enqueue_script('jquery');
    wp_register_style( 'gp_admin_pathways_css', plugins_url('css/gp-admin-pathways.css', __FILE__ ),time(), true);
    wp_enqueue_style( 'gp_admin_pathways_css');
    wp_register_script( 'gp_pathways_javascript', plugins_url('js/gp-pathways.js', __FILE__ ),array('jquery'),time(), true);
    wp_enqueue_script( 'gp_pathways_javascript' );
    wp_localize_script( 'gp_pathways_javascript',
        'ajax_object_meta',
        array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'unique_id_nonce' ),
        )
    );
}


/**
 * Register gp-pathway custom post type
 *
 * @see register_post_type Wordpress
 */
add_action('init', 'pathway_labels');

function pathway_labels() {
	$args = array(
		'labels' => array(	'name' => 'GP Pathways', 
							'singular_label' => 'GP Pathway',
							'add_new' => 'Add New Pathway',
							'add_new_item' => 'Add New Pathway',
							'search_items' => 'Search GP Pathways',
							'edit_item' => 'Edit GP Pathway'
							),
		'public' => true,
		'show_ui' => true,
		'show_in_nav_menus' => false,
		'capability_type' => 'post',
		'hierarchical' => true,
		'rewrite' => false,
		'supports' => array('title', 'author'),
		'taxonomies' => array('gp_career_clusters'),
		'menu_position' => 3
		);
    
	register_post_type( 'gp_pathway' , $args );
}


/**
* Register schools Taxonomy for post type gp-pathway 
*
* @see register_taxonomy Wordpress
*/
add_action( 'init', 'gp_schools_taxonomy');

function gp_schools_taxonomy(){
    global $wpdb;
	$args = array(
		'labels' => array(	'name' => 'Schools',
							'singular_name' => 'School',
							'search_items' => 'Search Schools',
							'all_items' => 'All Schools',
							'parent_item' => 'Parent Category',
							'parent_item_colon' => 'Parent Category:',
							'edit_item' => 'Edit School', 
							'update_item' => 'Update School',
							'add_new_item' => 'Add New',
							'new_item_name' => 'New School',
							'menu_name' => 'Schools',
							),
        'capabilities'      => array(
					'assign_terms' => 'edit_posts',
					'edit_terms'   => 'manage_options',
					'delete_terms' => 'manage_options',
					'manage_terms' => 'manage_options',
				),
		'show_ui' => true,
		'show_in_nav_menus' => false,
		'show_admin_column' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => false,
		'hierarchical' => true,
    );

    register_taxonomy('gp_schools','gp_pathway', $args);  

}


/**
* Register Career_cluster Taxonomy for post type gp-pathway 
*
* @see register_taxonomy Wordpress
*/
add_action( 'init', 'gp_pathway_career_taxonomy');

function gp_pathway_career_taxonomy(){
    global $wpdb;
	$args = array(
		'labels' => array(	'name' => 'Career Clusters',
							'singular_name' => 'Career Cluster',
							'search_items' => 'Search Career Clusters',
							'all_items' => 'All Career Clusters',
							'parent_item' => 'Parent Category',
							'parent_item_colon' => 'Parent Category:',
							'edit_item' => 'Edit Career Cluster', 
							'update_item' => 'Update Career Cluster',
							'add_new_item' => 'Add New',
							'new_item_name' => 'New Career Cluster',
							'menu_name' => 'Career Clusters',
							),
        'capabilities'      => array(
					'assign_terms' => 'edit_posts',
					'edit_terms'   => 'manage_options',
					'delete_terms' => 'manage_options',
					'manage_terms' => 'manage_options',
				),
		'show_ui' => true,
		'show_in_nav_menus' => false,
		'show_admin_column' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => false,
		'hierarchical' => true,
    );

    register_taxonomy('gp_career_clusters','gp_pathway', $args);  

}


/**
* Function to add the federal career clusters and pathways to taxonomy be default
* list as arrays in file career_clusters.php
* 
* @func add_default_taxonomies()
* 
* @see register_activation_hook wordpress
* @see wp_insert_term wordpress
* @see term_exists wordpress
*/

register_activation_hook( __FILE__, 'add_default_taxonomies' );

function add_default_taxonomies(){
    
    gp_pathway_career_taxonomy(); // call to function to register taxonomy gp_career_clusters
    
    include( plugin_dir_path( __FILE__ ) . 'career_clusters.php'); // File with arrays that have Federal career cluster and pathway names
    
    $taxon = 'gp_career_clusters'; // taxonomy
    
    foreach($parent_career_clusters_list as $parent_cluster){
        if(term_exists($parent_cluster, $taxon)) continue;
           $parents = wp_insert_term($parent_cluster, $taxon , array('slug'=>strtolower($parent_cluster))); //insert all clusters
    }
    
    foreach ($parent_career_clusters_list as $cluster) {
        Switch ($cluster){
            case 'Agriculture, Food & Natural Resources':
             $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['Agriculture'] as $ag_pathways){
                    if(term_exists($ag_pathways, $taxon)) continue; //check if cluster exists
                    wp_insert_term($ag_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($ag_pathways))); //insert pathways under cluster. all below the same
                }    
                break;
            case 'Architecture & Construction':
            $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['Architecture'] as $arch_pathways){
                    if(term_exists($arch_pathways, $taxon)) continue;
                    wp_insert_term($arch_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($arch_pathways)));
                }    
                break;
            case 'Arts, A/V Technology & Communications':
            $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['ArtsTechnology'] as $art_pathways){
                    if(term_exists($art_pathways, $taxon)) continue;
                    wp_insert_term($art_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($art_pathways)));
                }    
                break;
            case 'Business Management & Administration':
            $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['Business'] as $bus_pathways){
                    if(term_exists($bus_pathways, $taxon)) continue;
                    wp_insert_term($bus_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($bus_pathways)));
                }    
                break;
            case 'Education & Training':
            $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['Education'] as $edu_pathways){
                    if(term_exists($edu_pathways, $taxon)) continue;
                    wp_insert_term($edu_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($edu_pathways)));
                }    
                break;
            case 'Finance':
            $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['Finance'] as $fin_pathways){
                    if(term_exists($fin_pathways, $taxon)) continue;
                    wp_insert_term($fin_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($fin_pathways)));
                }    
                break;
            case 'Government & Public Administration':
            $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['Government'] as $gov_pathways){
                    if(term_exists($gov_pathways, $taxon)) continue;
                    wp_insert_term($gov_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($gov_pathways)));
                }    
                break;
            case 'Health Science':
            $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['Health'] as $health_pathways){
                    if(term_exists($health_pathways, $taxon)) continue;
                    wp_insert_term($health_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($health_pathways)));
                }    
                break;
            case 'Hospitality & Tourism':
            $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['Hospitality'] as $hospitality_pathways){
                    if(term_exists($hospitality_pathways, $taxon)) continue;
                    wp_insert_term($hospitality_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($hospitality_pathways)));
                }    
                break;
            case 'Human Services':
            $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['HumanServices'] as $human_pathways){
                    if(term_exists($human_pathways, $taxon)) continue;
                    wp_insert_term($human_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($human_pathways)));
                }    
                break;
            case 'Information Technology':
            $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['InformationTechnology'] as $technology_pathways){
                    if(term_exists($technology_pathways, $taxon)) continue;
                    wp_insert_term($technology_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($technology_pathways)));
                }    
                break;
            case 'Law, Public Safety, Corrections & Security':
            $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['Law'] as $law_pathways){
                    if(term_exists($law_pathways, $taxon)) continue;
                    wp_insert_term($law_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($law_pathways)));
                }    
                break;
            case 'Manufacturing':
            $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['Manufacturing'] as $manufacturing_pathways){
                    if(term_exists($manufacturing_pathways, $taxon)) continue;
                    wp_insert_term($manufacturing_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($manufacturing_pathways)));
                }    
                break;
            case 'Marketing':
            $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['Marketing'] as $marketing_pathways){
                    if(term_exists($marketing_pathways, $taxon)) continue;
                    wp_insert_term($marketing_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($marketing_pathways)));
                }    
                break;
            case 'Science, Technology, Engineering & Mathematics':
            $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['STEM'] as $stem_pathways){
                    if(term_exists($stem_pathways, $taxon)) continue;
                    wp_insert_term($stem_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($stem_pathways)));
                }    
                break;
            case 'Transportation, Distribution & Logistics':
            $exist = term_exists($cluster, $taxon);
                  $term_id = $exist['term_id'];
                foreach($child_pathways_list['Transportation'] as $transportation_pathways){
                    if(term_exists($transportation_pathways, $taxon)) continue;
                    wp_insert_term($transportation_pathways, $taxon ,array('parent' => $term_id, 'slug'=>strtolower($transportation_pathways)));
                }    
                break;
            default: return;
        }
    }
}


/**
 * Sets check_ontop in wp_terms_checklist_args to false
 * helps fix order problem with categories not displaying under parent if they arent checked
 *
 * @return $args
 * @see wp_terms_checklist_args Wordpress
 */
add_filter('wp_terms_checklist_args','taxonomy_checklist_checked_ontop_filter');

function taxonomy_checklist_checked_ontop_filter ($args) {
        if($args['taxonomy'] == 'gp_career_clusters'){
            $args['checked_ontop'] = false;
        return $args;
    }
    return $args;
}


/**
 * Set the meta_boxes to hide by default
 * Only on gp_pathway post_type
 * 
 * @see default_hidden_meta_boxes Wordpress
 */
add_action( 'default_hidden_meta_boxes', 'default_hide_meta_boxes', 10, 2 );

function default_hide_meta_boxes( $hidden, $screen ) {
    
	$post_type = $screen->post_type;// Get post type
	
    if ( $post_type == 'gp_pathway' ) {
		
        $hidden = array( // array of meta_boxes to hide
			'slugdiv',
            'authordiv'
		);
		return $hidden; // Pass new defaults
	}
	// If we are not on a 'pathway', pass the
	// original Wordpress defaults
	return $hidden;
}


/**
 * Display the extra fields that were added with the javascipt button on the form
 * 
 * 
 * @func form_meta_data()
 */
function form_meta_data($post_id, $meta_key) {
    
    global $wpdb;    

    //checks the $meta_key attribute of function to set input label name
    (strpos($meta_key,'CFIS') !== false) ? $label = 'CFIS': ''; 
    (strpos($meta_key,'science') !== false) ? $label = 'Science': '';
    (strpos($meta_key,'social') !== false) ? $label = 'Social': '';
    (strpos($meta_key,'math') !== false) ? $label = 'Math' : '';
    (strpos($meta_key,'english') !== false) ? $label = 'English' : '';
    (strpos($meta_key,'early') !== false) ? $label = 'Early college' : '';
    (strpos($meta_key,'professional') !== false) ? $label = 'Professional learning' : '';
    (strpos($meta_key,'industry') !== false) ? $label = 'Industry Credential' : '';
    
    $query = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE '{$meta_key}' ORDER BY meta_value ASC", ARRAY_A ); // Wordpress query on postmeta
            
    $meta_key = str_replace('%','',$meta_key); // Strip $meta_key string of % to use html ID and NAME
                
    foreach ($query as $x) {
        echo '<div>
                <label>'.$label.'</label>
                <input name="'.$x['meta_key'].'" value="'.$x['meta_value'].'">
                <button data-post="'.$post_id.'" data-keys="'.$x['meta_key'].'" class="button secondary-button button-small gp-delete-meta">Delete</button>
            </div>';
    } //END foreach loop
} // END form_meta_data()


/**
 * Display the extra ICC program link fields that were added with the javascipt button on the form
 * 
 *
 * @func form_meta_icc_links
 */
function form_meta_icc_links($post_id, $meta_keys){
    
    global $wpdb;
    
    if(strpos($meta_keys,'certificate') !== false){
        $label = 'Certificate Title'; $label2 = 'Certificate Link';
    }
    
    if(strpos($meta_keys,'program') !== false){
        $label = 'Program Title'; $label2 = 'Program Link';
    }
    
    $meta_keys = explode(',',$meta_keys);
    
    $query = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE '{$meta_keys[0]}' ORDER BY meta_value ASC", ARRAY_A );
    
    $query2 = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE '{$meta_keys[1]}' ORDER BY meta_value ASC", ARRAY_A );
    
    $meta_keys = str_replace('%','',$meta_keys); // Strip $meta_key string of % to use html ID and NAME
    
    for ($v= 0; $v < count($query); ++$v){
       echo '<div><label>'.$label.'</label>
                <input name="'.$query[$v]['meta_key'].'" value="'.$query[$v]['meta_value'].'"><br>
            <label>'.$label2.'</label>
                <input name="'.$query2[$v]['meta_key'].'" value="'.$query2[$v]['meta_value'].'">
       <button data-post="'.$post_id.'" data-keys="'.$query[$v]['meta_key'].','.$query2[$v]['meta_key'].'" class="button secondary-button gp-delete-meta">Delete</button>
       </div>';
    }
}



/*
* Gets the count of the last entry in a specfic meta_key to set to the button for the javascipt to know
* Number used to increment the name_[number] for unique name for form
*
* @func get_count
*/

function get_count($post_id, $meta_keys){
    
    global $wpdb;
    
 
    if (is_array($meta_keys)){ // check if array
        
        $meta_keys = explode(',',$meta_keys[0]);
        
        $query = $wpdb->get_results("SELECT meta_key FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE '{$meta_keys[0]}' ORDER BY LENGTH(meta_key) DESC, meta_key DESC LIMIT 1", ARRAY_A );
        
        $query2 = $wpdb->get_results("SELECT meta_key FROM $wpdb->postmeta WHERE post_id =  $post_id AND meta_key LIKE '{$meta_keys[1]}' ORDER BY LENGTH(meta_key) DESC, meta_key DESC LIMIT 1", ARRAY_A );
        
        if (!empty($query)){
            preg_match("/\[(.*?)\]/",$query[0]['meta_key'], $last_num);
            echo $last_num[1];
        }
        else {echo 0;}
    }
    
    else { 
        
        $query = $wpdb->get_results("SELECT meta_key FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE '{$meta_keys}' ORDER BY LENGTH(meta_key) DESC, meta_key DESC LIMIT 1", ARRAY_A );
        
    
    if (!empty($query)){
        preg_match("/\[(.*?)\]/",$query[0]['meta_key'], $last_num);
        echo $last_num[1];
    }
        
    else {echo 0;}
    }
}


/**
 * Get all values that were entered in the form to add to display_gp_pathways() function
 * 
 * @func display_meta_data()
 */
function display_meta_data($post_id, $meta_key) {
    
    global $wpdb;    

    
    if(is_array($meta_key)){
        
        $meta_key = explode(',',$meta_key[0]);
        
        $title = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE '{$meta_key[0]}' ORDER BY meta_value ASC", ARRAY_A ); // Wordpress query on postmeta
        
        $link = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE '{$meta_key[1]}' ORDER BY meta_value ASC", ARRAY_A ); // Wordpress query on postmeta
        
        for ($v= 0; $v < count($title); ++$v){
            echo '<li><a href="http://'.$link[$v]['meta_value'].'">'.$title[$v]['meta_value'].'</a></li>';
        }
    }
    
    else {
        
        $query = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE '{$meta_key}' ORDER BY meta_value ASC", ARRAY_A ); // Wordpress query on postmeta

        foreach ($query as $x) {
            if(!empty($x['meta_value'])){
                echo '<li>'.$x['meta_value'].'</li>';
            }   
        } //END foreach loop
    } // END display_meta_data()
}


/**
 * Function to Add meta box
 * Callback Function to display content in gp_pathway_id_meta_box
 *
 * @func pathway_id_meta_box 
 * @func pathway_id 
 * @see add_meta_box Wordpress
 */
add_action( 'add_meta_boxes_gp_pathway', 'gp_pathway_id_meta_box');

/* Metabox*/
function gp_pathway_id_meta_box(){
	add_meta_box('id', 'Display Pathway With Shortcode', 'gp_pathway_id', null, 'advanced', 'high');
}
/* Metabox pathway_id_meta_box callback */
function gp_pathway_id($post) { ?>
    <h2 data-id="<?php echo $post->ID ?>">[gp-pathway id="<?php echo $post->ID; ?>"]</h2>
<?php 
}


/**
 * Function to add meta box for form 
 * Callback function to display the form in the Pathway meta_box
 * 
 * @func gp_pathway_info_meta
 * @func gp_pathway_info
 * 
 * @see add_meta_box Wordpress
 */
add_action( 'add_meta_boxes_gp_pathway', 'gp_pathway_info_meta');
/* Metabox */
function gp_pathway_info_meta(){
	add_meta_box('gp_pathway', 'Pathway Form', 'gp_pathway_info', null, 'advanced', 'default');
}
/* Metabox pathway_info_meta callback */
function gp_pathway_info($post, $mb){
	$custom = get_post_custom($post->ID); //Get custom fields using Post ID
    wp_nonce_field( 'gp_form', 'meta_box_nonce' );
    /**
    * Variables for the default fields that are in the form
    * values are from post meta
    *
    */
    $pathway_desc              = (isset($custom['pathway_description'][0])) ? $custom['pathway_description'][0]:'';
	$CFIS_9th_def              = (isset($custom['df_9th_CFIS'][0])) ? $custom['df_9th_CFIS'][0]:'';
    $science_9th_def           = (isset($custom['df_9th_science'][0])) ? $custom['df_9th_science'][0]:'';
    $social_9th_def            = (isset($custom['df_9th_social'][0])) ? $custom['df_9th_social'][0]:'';
    $math_9th_def              = (isset($custom['df_9th_math'][0])) ? $custom['df_9th_math'][0]:'';
    $english_9th_def           = (isset($custom['df_9th_english'][0])) ? $custom['df_9th_english'][0]:'';
    $CFIS_10th_def             = (isset($custom['df_10th_CFIS'][0])) ? $custom['df_10th_CFIS'][0]:'';
    $science_10th_def          = (isset($custom['df_10th_science'][0])) ? $custom['df_10th_science'][0]:'';
    $social_10th_def           = (isset($custom['df_10th_social'][0])) ? $custom['df_10th_social'][0]:'';
    $math_10th_def             = (isset($custom['df_10th_math'][0])) ? $custom['df_10th_math'][0]:'';
    $english_10th_def          = (isset($custom['df_10th_english'][0])) ? $custom['df_10th_english'][0]:'';
    $CFIS_11th_def             = (isset($custom['df_11th_CFIS'][0])) ? $custom['df_11th_CFIS'][0]:'';
    $science_11th_def          = (isset($custom['df_11th_science'][0])) ? $custom['df_11th_science'][0]:'';
    $social_11th_def           = (isset($custom['df_11th_social'][0])) ? $custom['df_11th_social'][0]:'';
    $math_11th_def             = (isset($custom['df_11th_math'][0])) ? $custom['df_11th_math'][0]:'';
    $english_11th_def          = (isset($custom['df_11th_english'][0])) ? $custom['df_11th_english'][0]:'';
    $CFIS_12th_def             = (isset($custom['df_12th_CFIS'][0])) ? $custom['df_12th_CFIS'][0]:'';
    $science_12th_def          = (isset($custom['df_12th_science'][0])) ? $custom['df_12th_science'][0]:'';
    $social_12th_def           = (isset($custom['df_12th_social'][0])) ? $custom['df_12th_social'][0]:'';
    $math_12th_def             = (isset($custom['df_12th_math'][0])) ? $custom['df_12th_math'][0]:'';
    $english_12th_def          = (isset($custom['df_12th_english'][0])) ? $custom['df_12th_english'][0]:'';
    $early_college_def         = (isset($custom['df_early_college'][0])) ? $custom['df_early_college'][0]:'';
    $professinal_learning_def  = (isset($custom['df_professional_learning'][0])) ? $custom['df_professional_learning'][0]:'';
    $industry_creds_def        = (isset($custom['df_industry_creds'][0])) ? $custom['df_industry_creds'][0]:'';
    $icc_certificate_title_def = (isset($custom['df_icc_certificate_title'][0])) ? $custom['df_icc_certificate_title'][0]:'';
    $icc_certificate_url_def   = (isset($custom['df_icc_certificate_url'][0])) ? $custom['df_icc_certificate_url'][0]:'';
    $icc_porgram_title_def     = (isset($custom['df_icc_program_title'][0])) ? $custom['df_icc_program_title'][0]:'';
    $icc_porgram_url_def       = (isset($custom['df_icc_program_url'][0])) ? $custom['df_icc_program_url'][0]:'';
        
?>
   
    <div class="gp-pathway-form">
        
        <h3>Pathway Description</h3>
        <br>
        <textarea name="pathway_description"><?php echo $pathway_desc;?></textarea>
        
        <h3 id="expand-9th">9th Grade <span>Expand Section</span></h3>

        <div id="grade-9th-group">
            <h4>Career Focused Instructional Sequence</h4>
            <div id="9th-CFIS">
                <div>
                    <label>CFIS</label>
                    <input name="df_9th_CFIS" value="<?php echo $CFIS_9th_def;?>">
                </div>
            <?php echo form_meta_data( $post->ID, '9th_CFIS_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'9th_CFIS_%'); ?>" id="9th-add-CFIS" class="button button-primary button-small">Add CFIS</div>
                
            <h4>Science classes</h4>          
            <div id="9th-science">    
                <div>
                    <label>Science</label>
                    <input name="df_9th_science" value="<?php echo $science_9th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '9th_science_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'9th_science_%'); ?>" id="9th-add-science" class="button button-primary button-small">Add science class</div>
            
            <h4>Social Studies classes</h4>          
            <div id="9th-social">    
                <div>
                    <label>Social studies</label>
                    <input name="df_9th_social" value="<?php echo $social_9th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '9th_social_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'9th_social_%'); ?>" id="9th-add-social" class="button button-primary button-small">Add social studies class</div>
            
            <h4>Math classes</h4>          
            <div id="9th-math">    
                <div>
                    <label>Math</label>
                    <input name="df_9th_math" value="<?php echo $math_9th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '9th_math_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'9th_math_%'); ?>" id="9th-add-math" class="button button-primary button-small">Add math class</div>
            
            <h4>English classes</h4>          
            <div id="9th-english">    
                <div>
                    <label>English</label>
                    <input name="df_9th_english" value="<?php echo $english_9th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '9th_english_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'9th_english_%'); ?>" id="9th-add-english" class="button button-primary button-small">Add english class</div>
        </div>
        
        <br>
        
        <h3 id="expand-10th">10th Grade <span>Expand Section</span></h3>
        <div id="grade-10th-group">
        <h4>Career Focused Instructional Sequence</h4>
            <div id="10th-CFIS">
                <div>
                    <label>CFIS</label>
                    <input name="df_10th_CFIS" value="<?php echo $CFIS_10th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '10th_CFIS_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'10th_CFIS_%'); ?>" id="10th-add-CFIS" class="button button-primary button-small">Add CFIS</div>
                
            <h4>Science classes</h4>          
            <div id="10th-science">    
                <div>
                    <label>Science</label>
                    <input name="df_10th_science" value="<?php echo $science_10th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '10th_science_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'10th_science_%'); ?>" id="10th-add-science" class="button button-primary button-small">Add science class</div>
            
            <h4>Social Studies classes</h4>          
            <div id="10th-social">    
                <div>
                    <label>Social studies</label>
                    <input name="df_10th_social" value="<?php echo $social_10th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '10th_social_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'10th_social_%'); ?>" id="10th-add-social" class="button button-primary button-small">Add social studies class</div>
            
            <h4>Math classes</h4>          
            <div id="10th-math">    
                <div>
                    <label>Math</label>
                    <input name="df_10th_math" value="<?php echo $math_10th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '10th_math_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'10th_math_%'); ?>" id="10th-add-math" class="button button-primary button-small">Add math class</div>
            
            <h4>English classes</h4>          
            <div id="10th-english">    
                <div>
                    <label>English</label>
                    <input name="df_10th_english" value="<?php echo $english_10th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '10th_english_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'10th_english_%'); ?>" id="10th-add-english" class="button button-primary button-small">Add english class</div>
        </div>
        
        <br>  
        
        <h3 id="expand-11th">11th Grade <span>Expand Section</span></h3>
         <div id="grade-11th-group">
            <h4>Career Focused Instructional Sequence</h4>
            <div id="11th-CFIS">
                <div>
                    <label>CFIS</label>
                    <input name="df_11th_CFIS" value="<?php echo $CFIS_11th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '11th_CFIS_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'11th_CFIS_%'); ?>" id="11th-add-CFIS" class="button button-primary button-small">Add CFIS</div>
                
            <h4>Science classes</h4>          
            <div id="11th-science">    
                <div>
                    <label>Science</label>
                    <input name="df_11th_science" value="<?php echo $science_11th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '11th_science_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'11th_science_%'); ?>" id="11th-add-science" class="button button-primary button-small">Add science class</div>
            
            <h4>Social Studies classes</h4>          
            <div id="11th-social">    
                <div>
                    <label>Social studies</label>
                    <input name="df_11th_social" value="<?php echo $social_11th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '11th_social_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'11th_social_%'); ?>" id="11th-add-social" class="button button-primary button-small">Add social studies class</div>
            
            <h4>Math classes</h4>          
            <div id="11th-math">    
                <div>
                    <label>Math</label>
                    <input name="df_11th_math" value="<?php echo $math_11th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '11th_math_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'11th_math_%'); ?>" id="11th-add-math" class="button button-primary button-small">Add math class</div>
            
            <h4>English classes</h4>          
            <div id="11th-english">    
                <div>
                    <label>English</label>
                    <input name="df_11th_english" value="<?php echo $english_11th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '11th_english_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'11th_english_%'); ?>" id="11th-add-english" class="button button-primary button-small">Add english class</div>
        </div>
        
        <br>
        <h3 id="expand-12th">12th Grade <span>Expand Section</span></h3>
            <div id="grade-12th-group">
            <h4>Career Focused Instructional Sequence</h4>
            <div id="12th-CFIS">
                <div>
                    <label>CFIS</label>
                    <input name="df_12th_CFIS" value="<?php echo $CFIS_12th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '12th_CFIS_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'12th_CFIS_%'); ?>" id="12th-add-CFIS" class="button button-primary button-small">Add CFIS</div>
                
            <h4>Science classes</h4>          
            <div id="12th-science">    
                <div>
                    <label>Science</label>
                    <input name="df_12th_science" value="<?php echo $science_12th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '12th_science_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'12th_science_%'); ?>" id="12th-add-science" class="button button-primary button-small">Add science class</div>
            
            <h4>Social Studies classes</h4>          
            <div id="12th-social">    
                <div>
                    <label>Social studies</label>
                    <input name="df_12th_social" value="<?php echo $social_12th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '12th_social_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'12th_social_%'); ?>" id="12th-add-social" class="button button-primary button-small">Add social studies class</div>
            
            <h4>Math classes</h4>          
            <div id="12th-math">    
                <div>
                    <label>Math</label>
                    <input name="df_12th_math" value="<?php echo $math_12th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '12th_math_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'12th_math_%'); ?>" id="12th-add-math" class="button button-primary button-small">Add math class</div>
            
            <h4>English classes</h4>          
            <div id="12th-english">    
                <div>
                    <label>English</label>
                    <input name="df_12th_english" value="<?php echo $english_12th_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, '12th_english_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'12th_english_%'); ?>" id="12th-add-english" class="button button-primary button-small">Add english class</div>
        </div>
        <br>
        
        <h3 id="expand-early-college">Early College Credit<span>Expand Section</span></h3>
        
        <div id="early-college-group">
        <h4>Early College Credit Options</h4>
            <div id="early-college">
                <div>
                    <label>Early College</label>
                    <input name="df_early_college" value="<?php echo $early_college_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, 'early_college_%' ); ?>
            </div>
            
            <div data-count="<?php get_count($post->ID,'early_college_%'); ?>" id="early-college-add" class="button button-primary button-small">Add early college class</div>
        </div>
        <br>
       
        <h3 id="expand-professional-learning">Professional Learning<span>Expand Section</span></h3>
        
        <div id="professional-learning-group">  
        <h4>Professional Learning Oppurtunities</h4>
            <div id="professional-learning">
                <div>
                    <label>Professionl Learning</label>
                    <input name="df_professional_learning" value="<?php echo $professinal_learning_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, 'professional_learning_%' ); ?>         
            </div>
            <div data-count="<?php get_count($post->ID,'professional_learning_%'); ?>" id="professional-learning-add" class="button button-primary button-small">Add professional learning</div>
        </div>
        <br>
        
        <h3 id="expand-industry-credentials">Industry Credentials<span>Expand Section</span></h3>
        
        <div id="industry-credentials-group">  
        <h4>Industry Credentials</h4>
            <div id="industry-credentials">
                <div>
                    <label>Industry Credential</label>
                    <input name="df_industry_creds" value="<?php echo $industry_creds_def;?>">
                </div>
                <?php echo form_meta_data( $post->ID, 'industry_creds_%' ); ?>         
            </div>
            <div data-count="<?php get_count($post->ID,'industry_creds_%'); ?>" id="industry-creds-add" class="button button-primary button-small">Add industry credential</div>
        </div>
        <br>
        
        <h3 id="expand-icc-certificates-programs">Illinois Central College Certificates &amp; Programs<span>Expand Section</span></h3>
        <div id="icc-certificates-programs-group">  
            <h4>ICC Certificates</h4>
            <div id="icc-certificates">
                <div>
                    <label>Certificate Title</label>
                    <input name="df_icc_certificate_title" value="<?php echo $icc_certificate_title_def;?>">
                    <br>
                    <label>Certificate link</label>
                    <input name="df_icc_certificate_url" value="<?php echo $icc_certificate_url_def;?>">
                </div>
                <?php echo form_meta_icc_links( $post->ID, 'icc_certificate_title_%,icc_certificate_url_%' ); ?>         
            </div>
            <div data-count="<?php get_count($post->ID,array('icc_certificate_title_%,icc_certificate_url_%')); ?>" id="icc-certificate-add" class="button button-primary button-small">Add ICC Certificate</div>
            
            <h4>ICC Programs</h4>
            <div id="icc-programs">
                <div>
                    <label>Program Title</label>
                    <input name="df_icc_program_title" value="<?php echo $icc_porgram_title_def;?>">
                    <br>
                    <label>Program link</label>
                    <input name="df_icc_program_url" value="<?php echo $icc_porgram_url_def;?>">
                </div>
                <?php echo form_meta_icc_links( $post->ID, 'icc_program_title_%,icc_program_url_%' ); ?>         
            </div>
            <div data-count="<?php get_count($post->ID,array('icc_program_title_%,icc_program_url_%')); ?>" id="icc-program-add" class="button button-primary button-small">Add ICC Program</div>
        </div>
        <br>
    </div>     
<?php }


/**
* Function to save form $_REQUEST data to post meta
*
* @func pathway_save()
* 
* @hook save_post
* @see update_post_meta Wordpress
*/

//add_action('pre_post_update', 'gp_pathway_save', 10, 3);
add_action('save_post', 'gp_pathway_save', 100, 3);

function gp_pathway_save( $post_id ){ 
    
    global $wpdb;
    
    if(get_post_type($post_id) == "gp_pathway") { // update_post_meta only for post type pathways
        
        if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'gp_form' ) ) return;
        if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE){return;} //Bail out if DOING_AUTOSAVE
        if (!current_user_can( 'edit_post', $post_id )){return;} //Bail out if user can't edit post
        
        //$blogid = get_current_blog_id();
        //$table = 'wp_'.$blogid.'_postmeta';  
        //$wpdb->delete($wpdb->postmeta,array('post_id' => $post_id));
        //$wpdb->query($wpdb->prepare("DELETE * FROM $wpdb->postmeta WHERE post_id = $post_id"));

        // default inputs that show up in the form save
        // always saved & can be empty
        //
        if (isset($_REQUEST['pathway_description'])) update_post_meta($post_id, 'pathway_description', strip_tags($_REQUEST['pathway_description']));
        
        if (isset($_REQUEST['df_9th_CFIS'])) update_post_meta($post_id, 'df_9th_CFIS', strip_tags($_REQUEST['df_9th_CFIS']));
        
        if (isset($_REQUEST['df_9th_science'])) update_post_meta($post_id, 'df_9th_science', strip_tags($_REQUEST['df_9th_science']));
        
        if (isset($_REQUEST['df_9th_social'])) update_post_meta($post_id, 'df_9th_social', strip_tags($_REQUEST['df_9th_social']));
        
        if (isset($_REQUEST['df_9th_math'])) update_post_meta($post_id, 'df_9th_math', strip_tags($_REQUEST['df_9th_math']));
        
        if (isset($_REQUEST['df_9th_english'])) update_post_meta($post_id, 'df_9th_english', strip_tags($_REQUEST['df_9th_english']));
        
        if (isset($_REQUEST['df_10th_CFIS'])) update_post_meta($post_id, 'df_10th_CFIS', strip_tags($_REQUEST['df_10th_CFIS']));
        
        if (isset($_REQUEST['df_10th_science'])) update_post_meta($post_id, 'df_10th_science', strip_tags($_REQUEST['df_10th_science']));
        
        if (isset($_REQUEST['df_10th_social'])) update_post_meta($post_id, 'df_10th_social', strip_tags($_REQUEST['df_10th_social']));
        
        if (isset($_REQUEST['df_10th_math'])) update_post_meta($post_id, 'df_10th_math', strip_tags($_REQUEST['df_10th_math']));
        
        if (isset($_REQUEST['df_10th_english'])) update_post_meta($post_id, 'df_10th_english', strip_tags($_REQUEST['df_10th_english']));
        
        if (isset($_REQUEST['df_11th_CFIS'])) update_post_meta($post_id, 'df_11th_CFIS', strip_tags($_REQUEST['df_11th_CFIS']));
        
        if (isset($_REQUEST['df_11th_science'])) update_post_meta($post_id, 'df_11th_science', strip_tags($_REQUEST['df_11th_science']));
        
        if (isset($_REQUEST['df_11th_social'])) update_post_meta($post_id, 'df_11th_social', strip_tags($_REQUEST['df_11th_social']));
        
        if (isset($_REQUEST['df_11th_math'])) update_post_meta($post_id, 'df_11th_math', strip_tags($_REQUEST['df_11th_math']));
        
        if (isset($_REQUEST['df_11th_english'])) update_post_meta($post_id, 'df_11th_english', strip_tags($_REQUEST['df_11th_english']));
        
        if (isset($_REQUEST['df_12th_CFIS'])) update_post_meta($post_id, 'df_12th_CFIS', strip_tags($_REQUEST['df_12th_CFIS']));
        
        if (isset($_REQUEST['df_12th_science'])) update_post_meta($post_id, 'df_12th_science', strip_tags($_REQUEST['df_12th_science']));
        
        if (isset($_REQUEST['df_12th_social'])) update_post_meta($post_id, 'df_12th_social', strip_tags($_REQUEST['df_12th_social']));
        
        if (isset($_REQUEST['df_12th_math'])) update_post_meta($post_id, 'df_12th_math', strip_tags($_REQUEST['df_12th_math']));
        
        if (isset($_REQUEST['df_12th_english'])) update_post_meta($post_id, 'df_12th_english', strip_tags($_REQUEST['df_12th_english']));
        
        if (isset($_REQUEST['df_early_college'])) update_post_meta($post_id, 'df_early_college', strip_tags($_REQUEST['df_early_college']));
        
        if (isset($_REQUEST['df_professional_learning'])) update_post_meta($post_id, 'df_professional_learning', strip_tags($_REQUEST['df_professional_learning']));
        
        if (isset($_REQUEST['df_industry_creds'])) update_post_meta($post_id, 'df_industry_creds', strip_tags($_REQUEST['df_industry_creds']));
        
        if (isset($_REQUEST['df_icc_certificate_title'])) update_post_meta($post_id, 'df_icc_certificate_title', strip_tags($_REQUEST['df_icc_certificate_title']));
        
        if (isset($_REQUEST['df_icc_certificate_url'])) update_post_meta($post_id, 'df_icc_certificate_url', strip_tags(preg_replace('#^https?://#', '', $_REQUEST['df_icc_certificate_url'])));
        
        if (isset($_REQUEST['df_icc_program_title'])) update_post_meta($post_id, 'df_icc_program_title', strip_tags($_REQUEST['df_icc_program_title']));
        
        if (isset($_REQUEST['df_icc_program_url'])) update_post_meta($post_id, 'df_icc_program_url', strip_tags(preg_replace('#^https?://#', '', $_REQUEST['df_icc_program_url'])));
        
        
        //JS added inputs
        //Save through foreach loops
        //Append number to end of meta_key to make each different
        //not save if empty
        //
        if (!empty($_POST['9th_CFIS_'])){
            
            foreach ($_POST['9th_CFIS_'] as $key => $CFIS_9th) {$CFIS_9th = strip_tags($CFIS_9th);
            if(!empty($CFIS_9th)){update_post_meta( $post_id, '9th_CFIS_['.$key.']', $CFIS_9th);}}
        }
        
        if (isset($_REQUEST['9th_science_'])){
            
            foreach ($_REQUEST['9th_science_'] as $key => $science_9th) {$science_9th = strip_tags($science_9th);if(!empty($science_9th)){update_post_meta( $post_id, '9th_science_['.$key.']', $science_9th);}}
        }
        
        if (isset($_REQUEST['9th_social_'])){
            
            foreach ($_REQUEST['9th_social_'] as $key => $social_9th) {$social_9th = strip_tags($social_9th);if(!empty($social_9th)){update_post_meta( $post_id, '9th_social_['.$key.']', $social_9th);}}
        }
        
        if (isset($_REQUEST['9th_math_'])){
            
            foreach ($_REQUEST['9th_math_'] as $key => $math_9th) {$math_9th = strip_tags($math_9th);if(!empty($math_9th)){update_post_meta( $post_id, '9th_math_['.$key.']', $math_9th);}}
        }
        
        if (isset($_REQUEST['9th_english_'])){
            
            foreach ($_REQUEST['9th_english_'] as $key => $english_9th) {$english_9th = strip_tags($english_9th);if(!empty($english_9th)){update_post_meta( $post_id, '9th_english_['.$key.']', $english_9th);}}
        }
        
        if (isset($_REQUEST['10th_CFIS_'])){
            
            foreach ($_REQUEST['10th_CFIS_'] as $key => $CFIS_10th) {$CFIS_10th = strip_tags($CFIS_10th);if(!empty($CFIS_10th)){update_post_meta( $post_id, '10th_CFIS_['.$key.']', $CFIS_10th);}}
        }
        if (isset($_REQUEST['10th_science_'])){
            
            foreach ($_REQUEST['10th_science_'] as $key => $science_10th) {$science_10th = strip_tags($science_10th);if(!empty($science_10th)){update_post_meta( $post_id, '10th_science_['.$key.']', $science_10th);}}
        }
        
        if (isset($_REQUEST['10th_social_'])){
            
            foreach ($_REQUEST['10th_social_'] as $key => $social_10th) {$social_10th = strip_tags($social_10th);if(!empty($social_10th)){update_post_meta( $post_id, '10th_social_['.$key.']', $social_10th);}}
        }
        
        if (isset($_REQUEST['10th_math_'])){
            
            foreach ($_REQUEST['10th_math_'] as $key => $math_10th) {$math_10th = strip_tags($math_10th);if(!empty($math_10th)){update_post_meta( $post_id, '10th_math_['.$key.']', $math_10th);}}
        }
        
        if (isset($_REQUEST['10th_english_'])){
            
            foreach ($_REQUEST['10th_english_'] as $key => $english_10th) {$english_10th = strip_tags($english_10th);if(!empty($english_10th)){update_post_meta( $post_id, '10th_english_['.$key.']', $english_10th);}}
        }
        
        if (isset($_REQUEST['11th_CFIS_'])){
            
            foreach ($_REQUEST['11th_CFIS_'] as $key => $CFIS_11th) {$CFIS_11th = strip_tags($CFIS_11th);if(!empty($CFIS_11th)){update_post_meta( $post_id, '11th_CFIS_['.$key.']', $CFIS_11th);}}
        }
            
        if (isset($_REQUEST['11th_science_'])){
            
            foreach ($_REQUEST['11th_science_'] as $key => $science_11th) {$science_11th = strip_tags($science_11th);if(!empty($science_11th)){update_post_meta( $post_id, '11th_science_['.$key.']', $science_11th);}}
        }
        
        if (isset($_REQUEST['11th_social_'])){
            
            foreach ($_REQUEST['11th_social_'] as $key => $social_11th) {$social_11th = strip_tags($social_11th);if(!empty($social_11th)){update_post_meta( $post_id, '11th_social_['.$key.']', $social_11th);}}
        }
        
        if (isset($_REQUEST['11th_math_'])){
            
            foreach ($_REQUEST['11th_math_'] as $key => $math_11th) {$math_11th = strip_tags($math_11th);if(!empty($math_11th)){update_post_meta( $post_id, '11th_math_['.$key.']', $math_11th);}}
        }
        
        if (isset($_REQUEST['11th_english_'])){
            
            foreach ($_REQUEST['11th_english_'] as $key => $english_11th) {$english_11th = strip_tags($english_11th);if(!empty($english_11th)){update_post_meta( $post_id, '11th_english_['.$key.']', $english_11th);}}
        }
        if (isset($_REQUEST['12th_CFIS_'])){
            
            foreach ($_REQUEST['12th_CFIS_'] as $key => $CFIS_12th) {$CFIS_12th = strip_tags($CFIS_12th);if(!empty($CFIS_12th)){update_post_meta( $post_id, '12th_CFIS_['.$key.']', $CFIS_12th);}}
        }
            
        if (isset($_REQUEST['12th_science_'])){
            
            foreach ($_REQUEST['12th_science_'] as $key => $science_12th) {$science_12th = strip_tags($science_12th);if(!empty($science_12th)){update_post_meta( $post_id, '12th_science_['.$key.']', $science_12th);}}
        }
        
        if (isset($_REQUEST['12th_social_'])){
            
            foreach ($_REQUEST['12th_social_'] as $key => $social_12th) {$social_12th = strip_tags($social_12th);if(!empty($social_12th)){update_post_meta( $post_id, '12th_social_['.$key.']', $social_12th);}}
        }
        
        if (isset($_REQUEST['12th_math_'])){
            
            foreach ($_REQUEST['12th_math_'] as $key => $math_12th) {$math_12th = strip_tags($math_12th);if(!empty($math_12th)){update_post_meta( $post_id, '12th_math_['.$key.']', $math_12th);}}
        }
        
        if (isset($_REQUEST['12th_english_'])){
            
            foreach ($_REQUEST['12th_english_'] as $key => $english_12th) {$english_12th = strip_tags($english_12th);if(!empty($english_12th)){update_post_meta( $post_id, '12th_english_['.$key.']', $english_12th);}}
        }
        
        if (isset($_REQUEST['early_college_'])){
            
            foreach ($_REQUEST['early_college_'] as $key => $earlycollege) {$earlycollege = strip_tags($earlycollege);if(!empty($earlycollege)){update_post_meta( $post_id, 'early_college_['.$key.']', $earlycollege);}}
        }
        
        if (isset($_REQUEST['professional_learning_'])){
            
            foreach ($_REQUEST['professional_learning_'] as $key => $professionallearning) {$professionallearning = strip_tags($professionallearning);if(!empty($professionallearning)){update_post_meta( $post_id, 'professional_learning_['.$key.']', $professionallearning);}}
        }
        
        if (isset($_REQUEST['industry_creds_'])){
            
            foreach ($_REQUEST['industry_creds_'] as $key => $industrycreds) {$industrycreds = strip_tags($industrycreds);if(!empty($industrycreds)){update_post_meta( $post_id, 'industry_creds_['.$key.']', $industrycreds);}}
        }
        
        if (isset($_REQUEST['icc_certificate_title_'])){
            
            foreach ($_REQUEST['icc_certificate_title_'] as $key => $icccertificateTitle) {$icccertificateTitle = strip_tags($icccertificateTitle); update_post_meta( $post_id, 'icc_certificate_title_['.$key.']', $icccertificateTitle);}
        }
        
        if (isset($_REQUEST['icc_certificate_url_'])){
            
            foreach ($_REQUEST['icc_certificate_url_'] as $key => $icccertificateUrl) {$icccertificateUrl = strip_tags(preg_replace('#^https?://#', '', $icccertificateUrl)); update_post_meta( $post_id, 'icc_certificate_url_['.$key.']', $icccertificateUrl);}
        }
        
        if (isset($_REQUEST['icc_program_title_'])){
            
            foreach ($_REQUEST['icc_program_title_'] as $key => $iccprogramTitle) {$iccprogramTitle = strip_tags($iccprogramTitle);update_post_meta( $post_id, 'icc_program_title_['.$key.']', $iccprogramTitle);}
        }
        
        if (isset($_REQUEST['icc_program_url_'])){
            
            foreach ($_REQUEST['icc_program_url_'] as $key => $iccprogramUrl) {$iccprogramUrl = strip_tags(preg_replace('#^https?://#', '', $iccprogramUrl)); update_post_meta( $post_id, 'icc_program_url_['.$key.']', $iccprogramUrl);}
        }
            
 } // End if post_type == gp_pathway
} // End function gp_pathway_save()


/**
* Ajax action
* Gets post_id and array of meta_keys as $_REQUEST[delete_id]
*
* @see wp_ajax_ Wordpress
* @see delete_post_meta Wordpress
*/
add_action('wp_ajax_ajax_action', 'ajax_action', 4,1);

function ajax_action($post_id){
    
    $post_id = $_REQUEST['id_post']; //From ajax, data attribute data-post
    
    if ( !current_user_can( 'delete_posts', $post_id ) ) //Bail if user can't edit post
			return;
    if(!empty($_REQUEST['delete_id'])) {
        $key = $_REQUEST['delete_id']; //String of meta_key values from delete button data-id attribute
        
        $key = explode(',',$key); //sperate meta_keys at the comma
        
            foreach($key as $keys) {//loop to delete post meta of each of the meta_keys passed from the delete button
                delete_post_meta($post_id,$keys);
            }
        echo 'finished';
    }
    else { echo false;}

    die(); //end ajax request 
}


/**
* Displays the information from the pathway form
* 
* 
* @shortcode [gp-pathway id="$post_id"]
* @func display_gp_pathways
*
*/
add_shortcode( 'gp-pathway', 'display_gp_pathways' ); // [gp-pathway id="4201"] id=Post ID
function display_gp_pathways($atts) {
    ob_start();
    
    global $wpdb; // global wordpress database class
    
    //  ---------------  Attributes  --------------- //
	$default_atts = array(
						'id' => '',// can be multiple post id's but probably wont be
					);
	extract( shortcode_atts( $default_atts, $atts )	);

	$ids = explode( ',', str_replace( ' ', '', $id) ); // list out all id's
	
    $gp_pathways = array();

    if(!empty($id)) {
        foreach( $ids as $i ) { 
            if(!get_post_status($i)){
                echo "<p style='color:red;'>Post $i was deleted or doesn't exist</p>";
                continue;
            }
            $gp_pathways[$i]['post'] = get_post( $i ); // get post data
            $m = get_post_meta( $i ); // get post meta
            $gp_pathways[$i]['meta'] = $m;
        }

        foreach( $gp_pathways as $c ) { // loop through number posts returned from $id loop
            $post = $c['post']; // post information array
            $meta = $c['meta']; // post meta array
            $clus_paths = get_the_terms( $post->ID , 'gp_career_clusters'); // get career cluster and pathway categories for post
            $school = get_the_terms( $post->ID , 'gp_schools'); // get schools category for post
    ?>

<?php //<h3><?php echo get_the_title($post); //Display post title </h3> ?>

<div id="gp-pathway">

<div <?php if(!empty($school[0]->name)) echo "id=".str_replace(' ', '', strtolower($school[0]->name)); ?>>
<?php 
    $clustercats = '';
    $pathwaycats = '';
    if(!empty($clus_paths)){
        foreach($clus_paths as $term) { // add categories that are parent categories
            if ($term->parent == 0) { 
                $clustercats .= $term->name.'; ';
            }
            if ($term->parent != 0) {
                $pathwaycats .= ' '.$term->name.', '; // add categories that aren't parent categories
            } 
        }
if(!empty($clustercats) || !empty($pathwaycats)){echo $clustercats.' '.rtrim($pathwaycats, ', ').' - ';} // echo cluster and pathway remove last comma from pathway
    }
    ?>
<?php echo get_the_title($post); if(isset($school[0]->name)){echo '('.$school[0]->name.')';} // post title and school name?>
</div>
   
    <div><?php echo $meta['pathway_description'][0];?></div>

        <div id="high-school">

            <section>
                <div>Academic Grade</div>
                <div>Career Focused Instructional Sequence</div>
                <div>Science</div>
                <div>Social Studies</div>
                <div>Math</div>
                <div>English</div>
            </section>

            <section>
                <div data-label="Academic Grade"><strong>9<sup>th</sup></strong> Grade</div>
                <div data-label="Career Focused Instructional Sequence">
                <ul>
                    <?php
                    if(!empty($meta['df_9th_CFIS'][0])) {echo '<li>'.$meta['df_9th_CFIS'][0].'</li>';}
                    display_meta_data($post->ID, '9th_CFIS_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="Science">
                <ul>
                   <?php
                    if(!empty($meta['df_9th_science'][0])) {echo '<li>'.$meta['df_9th_science'][0].'</li>';}
                    display_meta_data($post->ID, '9th_science_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="Social Studies">
                <ul>
                    <?php
                    if(!empty($meta['df_9th_social'][0])) {echo '<li>'.$meta['df_9th_social'][0].'</li>';}
                    display_meta_data($post->ID, '9th_social_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="Math">
                <ul>
                    <?php
                    if(!empty($meta['df_9th_math'][0])) {echo '<li>'.$meta['df_9th_math'][0].'</li>';}
                    display_meta_data($post->ID, '9th_math_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="English">
                <ul>
                    <?php
                    if(!empty($meta['df_9th_english'][0])) {echo '<li>'.$meta['df_9th_english'][0].'</li>';}
                    display_meta_data($post->ID, '9th_english_%' ) ;
                    ?>
                </ul>
                </div>
            </section>

            <section>
                <div data-label="Academic Grade"><strong>10<sup>th</sup></strong> Grade</div>
                <div data-label="Career Focused Instructional Sequence">
                <ul>
                    <?php
                    if(!empty($meta['df_10th_CFIS'][0])) {echo '<li>'.$meta['df_10th_CFIS'][0].'</li>';}
                    display_meta_data($post->ID, '10th_CFIS_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="Science">
                <ul>
                    <?php
                    if(!empty($meta['df_10th_science'][0])) {echo '<li>'.$meta['df_10th_science'][0].'</li>';}
                    display_meta_data($post->ID, '10th_science_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="Social Studies">
                <ul>
                    <?php
                    if(!empty($meta['df_10th_social'][0])) {echo '<li>'.$meta['df_10th_social'][0].'</li>';}
                    display_meta_data($post->ID, '10th_social_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="Math">
                <ul>
                    <?php
                    if(!empty($meta['df_10th_math'][0])) {echo '<li>'.$meta['df_10th_math'][0].'</li>';}
                    display_meta_data($post->ID, '10th_math_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="English">
                <ul>
                    <?php
                    if(!empty($meta['df_10th_english'][0])) {echo '<li>'.$meta['df_10th_english'][0].'</li>';}
                    display_meta_data($post->ID, '10th_english_%' ) ;
                    ?>
                </ul>

                </div>
            </section>

            <section>
                <div data-label="Academic Grade"><strong>11<sup>th</sup></strong> Grade</div>
                <div data-label="Career Focused Instructional Sequence">
                <ul>
                    <?php
                    if(!empty($meta['df_11th_CFIS'][0])) {echo '<li>'.$meta['df_11th_CFIS'][0].'</li>';}
                    display_meta_data($post->ID, '11th_CFIS_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="Science">
                <ul>
                    <?php
                    if(!empty($meta['df_11th_science'][0])) {echo '<li>'.$meta['df_11th_science'][0].'</li>';}
                    display_meta_data($post->ID, '11th_science_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="Social Studies">
                <ul>
                    <?php
                    if(!empty($meta['df_11th_social'][0])) {echo '<li>'.$meta['df_11th_social'][0].'</li>';}
                    display_meta_data($post->ID, '11th_social_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="Math">
                <ul>
                    <?php
                    if(!empty($meta['df_11th_math'][0])) {echo '<li>'.$meta['df_11th_math'][0].'</li>';}
                    display_meta_data($post->ID, '11th_math_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="English">
                <ul>
                    <?php
                    if(!empty($meta['df_11th_english'][0])) {echo '<li>'.$meta['df_11th_english'][0].'</li>';}
                    display_meta_data($post->ID, '11th_english_%' ) ;
                    ?>
                </ul>
                </div>
            </section>

            <section>
                <div data-label="Academic Grade"><strong>12<sup>th</sup></strong> Grade</div>
                <div data-label="Career Focused Instructional Sequence">
                <ul>
                    <?php
                    if(!empty($meta['df_12th_CFIS'][0])) {echo '<li>'.$meta['df_12th_CFIS'][0].'</li>';}
                    display_meta_data($post->ID, '12th_CFIS_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="Science">
                <ul>
                    <?php
                    if(!empty($meta['df_12th_science'][0])) {echo '<li>'.$meta['df_12th_science'][0].'</li>';}
                    display_meta_data($post->ID, '12th_science_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="Social Studies">
                <ul>
                    <?php
                    if(!empty($meta['df_12th_social'][0])) {echo '<li>'.$meta['df_12th_social'][0].'</li>';}
                    display_meta_data($post->ID, '12th_social_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="Math">
                <ul>
                    <?php
                    if(!empty($meta['df_12th_math'][0])) {echo '<li>'.$meta['df_12th_math'][0].'</li>';}
                    display_meta_data($post->ID, '12th_math_%' ) ;
                    ?>
                </ul>
                </div>
                <div data-label="English">
                <ul>
                    <?php
                    if(!empty($meta['df_12th_english'][0])) {echo '<li>'.$meta['df_12th_english'][0].'</li>';}
                    display_meta_data($post->ID, '12th_english_%' ) ;
                    ?>
                </ul>
                </div>
            </section>

        </div>

        <div id="early-college-credit">

            <section>
                <div>Early College Credit</div>
                <div>
                <div>Students have several options to earn college credit during high school including Advanced Placement and Dual Credit</div>
                <ul>
                    <?php
                    if(!empty($meta['df_early_college'][0])) {echo '<li>'.$meta['df_early_college'][0].'</li>';}
                    display_meta_data($post->ID, 'early_college_%' ) ;
                    ?>
                </ul>
                </div>
            </section>
        </div>

        <div id="professional-learning">

            <section>
                <div>Professional Learning</div>
                <div>
                <div>Career Development Experiences/Career Exploration/Work-based Learning/Team-based Challenges</div>
                <ul>
                    <?php
                    if(!empty($meta['df_professional_learning'][0])) {echo '<li>'.$meta['df_professional_learning'][0].'</li>';}
                    display_meta_data($post->ID, 'professional_learning_%' ) ;
                    ?>
                </ul>
                </div>
            </section>

        </div>

        <div id="industry-credentials">

            <section>
                <div>Industry Credentials</div>
                <div>
                <div>Industry Credentials that can be earned while in high school</div>
                <ul>
                    <?php
                    if(!empty($meta['df_industry_creds'][0])) {echo '<li>'.$meta['df_industry_creds'][0].'</li>';}
                    display_meta_data($post->ID, 'industry_creds_%' ) ;
                    ?> 
                </ul>
                </div>
            </section>

        </div>

        <div id="icc-programs">

            <section>
                <div>ICC Programs</div>
                <div>
                <div>Certificates</div>
                <ul>
                    <?php
                    if(!empty($meta['df_icc_certificate_title'][0]) && !empty($meta['df_icc_certificate_url'])) {echo '<li><a href="http://'.$meta['df_icc_certificate_url'][0].'">'.$meta['df_icc_certificate_title'][0].'</a></li>';}
                    display_meta_data($post->ID, array('icc_certificate_title_%,icc_certificate_url_%'));
                    ?>
                </ul>
                </div>
                <div>
                <div>Applied Science Degrees</div>
                <ul>
                    <?php
                    if(!empty($meta['df_icc_program_title'][0]) && !empty($meta['df_icc_program_url'])) {echo '<li><a href="http://'.$meta['df_icc_program_url'][0].'">'.$meta['df_icc_program_title'][0].'</a></li>';}
                    display_meta_data($post->ID, array('icc_program_title_%,icc_program_url_%'));
                    ?>
                </ul>
                </div>
            </section>

        </div>

</div>

<?php 
         }// End pathways foreach
    }// End if $id empty
    return ob_get_clean();
}// End of Function display_gp_pathways