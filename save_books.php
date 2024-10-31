<?php

add_action( "wp_ajax_get_amazon_data","get_amazon_data" );
add_action( "wp_ajax_nopriv_get_amazon_data","my_must_login" );

function get_amazon_data() {

    $result = array();
    $result['type'] = 'error';

    if ( !wp_verify_nonce( $_REQUEST['nonce'], "my_kindle_book_nonce")) {
        exit("Something is not quite right, please try again...");
    }

    if (empty( $_POST['amazon_tld'] )) {

        $amazon_tld = 'co.uk';
    } else {

        $amazon_tld = $_POST['amazon_tld'];
        update_option( 'my_kindle_books_amazon_tld',$amazon_tld );
    }

    if (!empty( $_POST['my_books'] )) {

        $files_to_upload = $_POST['my_books'];

        $my_kindle_books_user_id = get_option( 'my_kindle_books_user_id' );

        if (empty( $my_kindle_books_user_id )) {

            $my_kindle_books_user_id = 0;
        }

        if(!empty( $files_to_upload )){

            preg_match_all( '/([A-Z0-9]{10,})(_sample|_EBOK)?\.azw/',$files_to_upload,$matches );

            $book_codes = array( 'book_codes'=>json_encode( $matches[1] ),
                                 'my_kindle_books_user_id'=>$my_kindle_books_user_id,
                                 'amazon_tld'=>$amazon_tld,
                                 'site_url'=>site_url() );
            $response = wp_remote_post( 'http://www.tail-fme.co.uk/Amazon/books/getbooks.php',array( 'body'=>$book_codes,'timeout'=>300 ));

            $my_kindle_books_data = get_option( 'my_kindle_books_data' );

            if (!empty( $my_kindle_books_data )) {

                $current_my_kindle_books = unserialize( $my_kindle_books_data );
                $new_my_kindle_books = unserialize( $response['body'] );

                $combined = array_merge( $current_my_kindle_books,$new_my_kindle_books['books'] );

                update_option( 'my_kindle_books_data',serialize( $combined ) );
                update_option( 'my_kindle_books_user_id',$new_my_kindle_books['id'] );

                $my_kindle_books = $combined;

            } else {

                $new_my_kindle_books = unserialize( $response['body'] );

                add_option( 'my_kindle_books_data',serialize( $new_my_kindle_books['books'] ),'','no' );
                add_option( 'my_kindle_books_user_id',$new_my_kindle_books['id'] );

                $my_kindle_books = $new_my_kindle_books['books'];
            }

            $my_kindle_book_table = my_kindle_books_list( $my_kindle_books );

            $result['type'] = 'success';
            $result['html'] = $my_kindle_book_table;
            $result['matches'] = $matches[1];

            echo json_encode( $result );
        }
    }

   die();
}

function my_must_login() {

   echo "You must be logged in to update your book list";
   die();
}
