<?php
/*
Plugin Name: My Greatest Posts
Plugin URI: http://www.alexanderhetzel.de
Description: Zählt die Pageviews jedes Blogbeitrags und gibt die meistbesuchten aus
Version: 1.0
Author: Alexander Hetzel
Author URI: http://www.alexanderhetzel.de
*/
?>
<?php

add_action( 'widgets_init', 'mgp_widget_load' );
add_action( 'wp_footer', 'ah_mgp_counter' );
register_activation_hook( __FILE__, 'ah_mgp_install' );

// Wie viele Beiträge sollen angezeigt werden?
$default_number = 5;

function ah_mgp_install () {

    global $wpdb;

    // Tabellennamen bestimmen
    $table_name = $wpdb->prefix . "my_greatest_posts";
    
    // Existiert die Tabelle bereits?
    if( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {

        // Wenn nein, Tabelle erstellen
        $sql = "CREATE TABLE " . $table_name . " (
                post_id bigint(11) NOT NULL,
                post_views bigint(11) NOT NULL,
                UNIQUE KEY post_id (post_id)
                );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

}

function ah_mgp_counter () {
    
    // Nur bei Posts aufrufen
    if ( is_single() ) {

        global $wpdb;
        global $post;
        
        $post_id = $wpdb->escape( $post->ID );
        
        $table_name = $wpdb->prefix . "my_greatest_posts";
        
        // Folgenden Code nur ausführen, wenn die Tabelle auch existiert
        if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name ) {
        
            // Existiert die POST-ID bereits in der Datenbank?
            $result = $wpdb->query( "SELECT * FROM " . $table_name . " WHERE post_id = '" . $post_id . "'" );
            
            // Wenn ja, bestehenden Eintrag updaten und post_views +1 setzen
            if ( $result ) { 
                $insert = $wpdb->query( "UPDATE " . $table_name . " SET post_views = post_views + 1 WHERE post_id = '" . $post_id . "'" );
            }
            
            // Wenn nein, Eintrag erstellen und auf 1 setzen
            else {
                $insert = $wpdb->query( "INSERT INTO " . $table_name . " SET post_id = '" . $post_id . "', post_views = 1" );
            }
        
        }
        
    }
    
}

function ah_mgp_get_top_posts ( $number = false ) {

    global $wpdb;
    
    
    // Wenn das Widget nicht genutzt wird, o.a. Standard-Wert setzen
    if ( $number == false ) {

        global $default_number;
        $number = $default_number;
    
    }
    
    $table_name = $wpdb->prefix . "my_greatest_posts";
    
    // Folgenden Code nur ausführen, wenn die Tabelle auch existiert
    if( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) == $table_name ) {
    
        // Die x ($number) Posts mit den meisten Pageviews aus der Datenbank holen
        $posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " ORDER BY post_views DESC LIMIT " . $number, ARRAY_A );
        
        
        // Die Posts als Liste zurückgeben
        $output = "<ul>";
        foreach ( $posts as $entry ) {
            $the_post = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "posts WHERE ID = '" . $entry["post_id"] . "'" );
            $content = substr(htmlentities($the_post->post_content), 0, 100);
            $content .= "...";
            $output .= "<li><a href='" . get_permalink( $entry["post_id"] ) . "'><span class='pop-title'>" . $the_post->post_title  . "</span> | <span class='pop-excerpt'>" . $content . "</span></a></li>";
        }
        $output .= "</ul>";
        
        return $output;
    
    }
    
}


function mgp_widget_load () {

    register_widget( 'MGP_Widget' );
    
}

class MGP_Widget extends WP_Widget
{

function MGP_Widget () {
    
    $widget_options = array( 'classname' => 'mgpwidget', 'description' => 'Ausgabe der beliebtesten Blog Posts' );
    
    $this->WP_Widget( 'my-greatest-posts', 'My Greatest Posts', $widget_options );
    
}

function widget ( $args, $instance ) {
    extract( $args );
    
    // Optionen, die der User festlegt
    $title = apply_filters( 'widget_title', $instance['title'] );
    $number = $instance['number'];
    
    // Ausgabe vor dem Widget
    echo $before_widget;
    
    // Titel des Widgets ausgeben
    if ( $title ) {
        echo $before_title . $title . $after_title;
    }
    
    // Liste der Beiträge ausgeben
    echo ah_mgp_get_top_posts( $number );
    
    //  Ausgabe nach dem Widget
    echo $after_widget;
    
}

function update ( $new_instance, $old_instance ) {

    $instance = $old_instance;
    
    // User-Eingaben säubern
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['number'] = strip_tags( $new_instance['number'] );
    
    return $instance;

}

function form ( $instance ) {

    // Standard-Werte festlegen
    $defaults = array( 'title' => 'My Greatest Posts', 'number' => '5' );
    
    $instance = wp_parse_args( (array) $instance, $defaults );
    
    ?>
    
    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Titel:</label>
        <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
    </p>
    
    <p>
        <label for="<?php echo $this->get_field_id( 'number' ); ?>">Anzahl der Artikel:</label>
        <input type="text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" class="widefat" style="width:20%;" />
    </p>
    
    
    <?php
}
    
}

?>