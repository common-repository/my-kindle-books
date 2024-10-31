<?php

add_action( 'admin_menu', 'my_kindle_books_create_admin_menu' );

function my_kindle_books_create_admin_menu() {

    $page_hook_suffix = add_menu_page( 'My Kindle Books', 'My Kindle Books', 'manage_options','my_kindle_books', my_kindle_books );

    add_action('admin_print_scripts-' . $page_hook_suffix, 'my_kindle_books_admin_scripts');
    add_action( 'admin_print_styles-' . $page_hook_suffix, 'my_kindle_books_admin_styles' );

    add_submenu_page( 'my_kindle_books','Add Amazon Affiliate Tags','Affiliate Tags','manage_options','my_kindle_books_affiliate_tags', my_kindle_books_affiliate_tags );
}

function my_kindle_books(){

    $amazon_tld = get_option( 'my_kindle_books_amazon_tld' );

    if (empty( $amazon_tld )) {

        $amazon_tld = getTld();
        add_option( 'my_kindle_books_amazon_tld',$amazon_tld );
    }

    include 'add-books.php';

    $my_kindle_books_data = get_option( 'my_kindle_books_data' );

    if (!empty( $my_kindle_books_data )) {

        $my_kindle_books = unserialize( $my_kindle_books_data );
        if (is_array( $my_kindle_books )) {
            $my_kindle_book_list = my_kindle_books_list( $my_kindle_books );
        } else {

            delete_option( 'my_kindle_books_data' );
        }
    }

    if (!empty( $my_kindle_book_list )) {

        echo $my_kindle_book_list;
    } else {

        echo '<div id="message" class="updated fade">';
        echo "<p><strong>To add your books:</strong> <ul><li>Plug in your Kindle</li><li>Click the browse button below</li><li>Select your Kindle books (Kindle/documents/)</li></ul></p>";
        echo '</div>';
    }
}

function my_kindle_books_affiliate_tags(){

    if (!empty( $_POST )) {

        $users_affiliated_tags = array();

        if (!empty( $_POST['uk_tag'] )) {
            $users_affiliated_tags['co.uk'] = $_POST['uk_tag'];
        }

        if (!empty( $_POST['us_tag'] )) {
            $users_affiliated_tags['com'] = $_POST['us_tag'];
        }

        if (!empty( $_POST['uk_tag'] )) {
            $users_affiliated_tags['co.uk'] = $_POST['uk_tag'];
        }

        if (!empty( $_POST['ca_tag'] )) {
            $users_affiliated_tags['ca'] = $_POST['ca_tag'];
        }

        if (!empty( $_POST['cn_tag'] )) {
            $users_affiliated_tags['cn'] = $_POST['cn_tag'];
        }

        if (!empty( $_POST['fr_tag'] )) {
            $users_affiliated_tags['fr'] = $_POST['fr_tag'];
        }

        if (!empty( $_POST['de_tag'] )) {
            $users_affiliated_tags['de'] = $_POST['de_tag'];
        }

        if (!empty( $_POST['it_tag'] )) {
            $users_affiliated_tags['it'] = $_POST['it_tag'];
        }

        if (!empty( $_POST['jp_tag'] )) {
            $users_affiliated_tags['jp'] = $_POST['jp_tag'];
        }

        $serialized_tags = serialize( $users_affiliated_tags );

        update_option( 'my_kindle_books_affiliate_tags',$serialized_tags );
        $affiliate_tags = $serialized_tags;
    } else {

        $affiliate_tags = get_option( 'my_kindle_books_affiliate_tags' );
    }

    $current_tags = unserialize( $affiliate_tags );

    include 'affiliate-tags.php';
}