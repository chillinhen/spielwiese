<?php

/*
  Plugin Name: DSH Anmeldungen "Hack" für spraachen.org
  Description: Plugin, welches die Daten der DSH-Anmeldeformulare in eine extra Datenbank ablegt und gesondert verarbeitet
 */
//Custom Post Type erstellen
function my_custom_post_dsh() {
  $labels = array(
    'name'               => _x( 'DSH-Anmeldungen', 'post type general name' ),
    'singular_name'      => _x( 'DSH-Anmeldung', 'post type singular name' ),
    'all_items'          => __( 'All DSH-Anmeldungen' ),
    'view_item'          => __( 'View DSH-Anmeldung' ),
    'search_items'       => __( 'Search DSH-Anmeldungen' ),
    'not_found'          => __( 'No dsh-anmeldungs found' ),
    'not_found_in_trash' => __( 'No dsh-anmeldungs found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => 'DSH-Anmeldungen'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our dsh-anmeldungs and dsh-anmeldung specific data',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title', 'thumbnail', 'custom-fields' ),
    'has_archive'   => true,
  );
  register_post_type( 'dsh-anmeldung', $args ); 
}
add_action( 'init', 'my_custom_post_dsh' );


// Contact Form 7 - Datenmanipulation vor Mailversand
function wpcf7_to_post($cfdata) {
	// Daten aus der Anfrage auslesen
	$submission = WPCF7_Submission::get_instance();
	if ( $submission ) {
		$formdata = $submission->get_posted_data();
	}
    
    // Post ID des Formulares angeben, welches "abgefangen" werden soll
    if ( $cfdata->id() == '1739') {
        $newDSHAnmeldung = array(
            'post_title'=> $formdata['your-name'],
            'post_status' => 'publish', // Status
            'post_type' => 'dsh-anmeldung', // Custom Post Type 'DSH Anmeldung'
		);

        // Eintrag als Custom Post Type anlegen
		$newpostid = wp_insert_post($newDSHAnmeldung);

        // ggf. weitere Custom Fields befüllen, evtl später
		add_post_meta($newpostid, 'customfield01', $formdata['your-subject']);//customfields müssen so heißen
		add_post_meta($newpostid, 'customfield02', $formdata['your-email']);
		add_post_meta($newpostid, 'customfield03', $formdata['your-message']);
                
        $number = count_dsh();
	
        if($number >= 15){
            $wpcf7_data->skip_mail = true;  
        }
    }
    
    
    
}
add_action('wpcf7_before_send_mail', 'wpcf7_to_post',1);


//Anzahl der Anmeldungen ausgeben
function count_dsh(){
    $args = array(
        'post_type'=>'dsh-anmeldung',
        'post_status' => 'publish'
        );
    $query = new WP_Query($args);
    $number = $query->found_posts; 
    
    return $number;
}




function message_sidebar(){
    $anmeldungen = count_dsh();
    $limit = 5;
    if($anmeldungen >= $limit){
        echo 'Tut uns leid! das Limit der Anmeldungen ist erreicht';
        
    }
    else {
        echo $anmeldungen . ' Anmeldungen von ' . $limit;
    }
    
}


//Wiget laden
function dsh_widget_load () {

    register_widget( 'DSH_Anmeldungen' );
    
}
add_action( 'widgets_init', 'dsh_widget_load' );

class DSH_Anmeldungen extends WP_Widget
{
    function DSH_Anmeldungen() {

        $widget_options = array('classname' => 'dshwidget', 'description' => 'Anzahl der DSH Anmeldungen');

        $this->WP_Widget('new-dsh-registration', 'DSH Anmeldungen', $widget_options);
    }
    function widget($args, $instance) {
        extract($args);

        // Optionen, die der User festlegt
        $title = apply_filters('widget_title', $instance['title']);
        //$number = $instance['number'];
        // Ausgabe vor dem Widget
        echo $before_widget;

        // Titel des Widgets ausgeben
        if ($title) {
            echo $before_title . $title . $after_title;
        }

        // Liste der Beiträge ausgeben
        echo message_sidebar();
        
        //echo 'hier die Anzahl der Anmeldungen';

        //  Ausgabe nach dem Widget
        echo $after_widget;
    }
    function update($new_instance, $old_instance) {

        $instance = $old_instance;

        // User-Eingaben säubern
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['limit_number'] = strip_tags($new_instance['limit_number']);

        return $instance;
    }
    
    function form ( $instance ) {

    // Standard-Werte festlegen
    $defaults = array( 'title' => 'DSH Anmeldungen', 'limit_number','20');
    
    $instance = wp_parse_args( (array) $instance, $defaults );
    
    ?>
    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Prüfung:</label>
        <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'limit_number' ); ?>">Max. Anmeldungen:</label>
        <input type="text" id="<?php echo $this->get_field_id( 'limit_number' ); ?>" name="<?php echo $this->get_field_name( 'limit_number' ); ?>" value="<?php echo $instance['limit_number']; ?>" class="widefat" />
    </p>

    <?php }

}

?>