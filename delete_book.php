<?php

add_action( "wp_ajax_delete_book","delete_book" );
add_action( "wp_ajax_nopriv_delete_book","my_must_login" );

function delete_book() {

	$result = array();
	$result['type'] = 'error';

	if ( !wp_verify_nonce( $_REQUEST['nonce'], "my_kindle_book_nonce")) {
		
		exit("Something is not quite right, please try again...");
	}   

	if (!empty( $_POST['isbn'] )) {

		$my_kindle_books_data = get_option( 'my_kindle_books_data' );

		if (!empty( $my_kindle_books_data )) {

			$current_my_kindle_books = unserialize( $my_kindle_books_data );

			unset( $current_my_kindle_books[$_POST['isbn']] );

			update_option( 'my_kindle_books_data',serialize( $current_my_kindle_books ) );

			echo 'success';exit();
		}

		echo 'no_existing_data';exit();
	}

	echo 'nothing_submitted';exit();

	die();
}
