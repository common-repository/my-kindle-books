<?php
/*
Plugin Name: My Kindle Books
Plugin URI: http://www.tail-fme.co.uk/my-kindle-books-plugin/
Description: Create a recommended reading list for your blog from your Kindle, just plug in your Kindle and choose the books you want to show off. Add Amazon affiliate tags to start making money when someone takes up your recommendations.
Version: 1.0
Author: Adam Groom
Author URI: http://www.tail-fme.co.uk
License: GPLv2
*/

include 'install_uninstall.php';

/* Runs when plugin is activated */
register_activation_hook( __FILE__,'my_kindle_books_install' );

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__,'my_kindle_books_remove' );

include 'js_css.php';

include 'mkb_page.php';

function my_kindle_books_table( $my_kindle_books ){

	$tld = getTld();
    $users_affiliate_tag = getAffiliateTag( $tld );

    $table_of_books = '<table class="widefat">';

    foreach( $my_kindle_books as $book ){

        $link = updateLink( $book['link'],$tld,$users_affiliate_tag );

        if (is_array( $book['author'] )) {

            $book['author'] = implode( ', ',$book['author'] );
        }

        $table_of_books .= '<tr><td style="vertical-align:top;width:80px;"><img class="wp-caption" width="'. $book['thumb_width'] . '" height="'. $book['thumb_height'] . '" src="' . $book['thumb'] . '" /></td>';
        $table_of_books .= '<td><a href="' . $link . '"><strong>' . $book['title'] . '</strong></a><br />' . $book['author'] . '</td></tr>';
    }

    $table_of_books .= '</table><p style="margin-top:40px;">Add this plugin to your blog <a href="http://www.tail-fme.co.uk/my-kindle-books-plugin/">My Kindle Books</a></p>';

    return $table_of_books;
}

function my_kindle_books_List( $my_kindle_books ){

    $list_of_books = '<div id="book_list"><ul id="sortable">';

    foreach( $my_kindle_books as $isbn=>$book ){

        if (is_array( $book['author'] )) {

            $book['author'] = implode( ', ',$book['author'] );
        }

        $list_of_books .= '<li data-isbn="' . $isbn . '">';

        $list_of_books .= '<span class="book-image"><img class="wp-caption" width="'. $book['thumb_width'] . '" height="'. $book['thumb_height'] . '" src="' . $book['thumb'] . '" /></span>';
        $list_of_books .= '<div class="book-info"><strong>' . $book['title'] . '</strong><br />' . $book['author'] . ' <br />' . $book['publisher'] . '</div>';
        $list_of_books .= '<button class="delete">Delete</button>';
        $list_of_books .= '<div class="clearfix"></div>';
        $list_of_books .= '</li>';
    }

    $list_of_books .= '</ul>';

    return $list_of_books;
}

/**
* Add affiliate links
* update amazon store relevant to the country of this user
*/
function updateLink( $link,$tld,$users_affiliate_tag ){

    $link_elements = parse_url( urldecode( $link ) );

    //if the user has an affiliate code for this tld add it
    if (!empty( $users_affiliate_tag )) {

        $query_elements = explode( '&',$link_elements['query'] );
        //find the tag element
        foreach( $query_elements as $k=>$_query_element ){

            if (strstr( $_query_element,'tag=' )) {

                $query_elements[$k] = "tag=$users_affiliate_tag";
            }
        }

        $link_elements['query'] = implode( '&',$query_elements );
    }

    //update the link to have the appropriate tld
    $link_elements['host'] = 'amazon.' . $tld;

    $link = $link_elements['scheme'] . '://www.' . $link_elements['host'] . $link_elements['path'] . '?' . $link_elements['query'];

    return $link;
}

function getTld(){

    $amazon_tld = 'com';

    $ip = $_SERVER['REMOTE_ADDR'];

    if (!empty( $ip )) {

        $response = wp_remote_post( 'http://www.tail-fme.co.uk/country_code.php',array( 'body'=>array( 'ip'=>$ip ),'timeout'=>300 ));
		$country_code = $response['body'];
    }

    if (empty( $country_code )) {
        $country_code = 'GB';
    }

    switch ($country_code) {
        case 'GB':
        case 'JE':
        case 'GG':
        case 'IM':
        case 'IE':
            $amazon_tld = 'co.uk';
        break;
        case 'CH':
        case 'AT':
            $amazon_tld = 'de';
        break;
        case 'FR':
            $amazon_tld = 'fr';
        break;
        case 'PT':
            $amazon_tld = 'es';
        break;
        case 'CA':
            $amazon_tld = 'ca';
        break;
        case 'IT':
            $amazon_tld = 'it';
        break;
        case 'US':
            $amazon_tld = 'com';
        break;
    }

    return $amazon_tld;
}

function getAffiliateTag( $tld ){

    $affiliate_tags = get_option( 'my_kindle_books_affiliate_tags' );

    if (!empty( $affiliate_tags )) {

        $affiliate_tags = unserialize( $affiliate_tags );
    }

    if (empty( $affiliate_tags[$tld] )) {

		switch ( $tld ) {
		    case 'de':
				$tag = 'mykinboo00-21';
		    break;
		    case 'fr':
				$tag = 'mykinboo07-21';
		    break;
		    case 'es':
				$tag = 'mykinboo04-21';
		    break;
		    case 'ca':
				$tag = 'mykinboo03-20';
		    break;
		    case 'it':
				$tag = 'mykinboo0a-21';
		    break;
		    case 'com':
				$tag = 'mykinboo-20';
		    break;
			default:
			case 'co.uk':
				$tag = 'mykinboo-21';
		    break;
		}

		return $tag;
	} else {

		return $affiliate_tags[$tld];
	}
}

include 'admin_menu.php';

include 'save_books.php';

include 'save_new_book_order.php';

include 'delete_book.php';