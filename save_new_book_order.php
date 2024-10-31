<?php

add_action( "wp_ajax_new_book_order","new_book_order" );
add_action( "wp_ajax_nopriv_new_book_order","my_must_login" );

function new_book_order() {

    $result = array();
    $result['type'] = 'error';

   if ( !wp_verify_nonce( $_REQUEST['nonce'], "my_kindle_book_nonce")) {
      exit("Something is not quite right, please try again...");
   }   

    if (!empty( $_POST['new_book_order'] )) {

        $my_kindle_books_data = get_option( 'my_kindle_books_data' );

        if (!empty( $my_kindle_books_data )) {

            $current_my_kindle_books = unserialize( $my_kindle_books_data );
            
            $ordered_kindle_books = array();

            foreach( $_POST['new_book_order'] as $isbn ){

            	$ordered_kindle_books[$isbn] = $current_my_kindle_books[$isbn];
            }

            update_option( 'my_kindle_books_data',serialize( $ordered_kindle_books ) );

            echo 'success';exit();
        }

        echo 'no_existing_data';exit();
    }
   	
   	echo 'nothing_submitted';exit();

   die();
}
