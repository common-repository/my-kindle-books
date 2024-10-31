<?php

function my_kindle_books_parser( $q ) {

    $the_page_name = get_option( "my_kindle_books_name" );
    $the_page_id = get_option( 'my_kindle_books_id' );

    $qv = $q->query_vars;

    // have we NOT used permalinks...?
    if( !$q->did_permalink AND ( isset( $q->query_vars['page_id'] ) ) AND ( intval($q->query_vars['page_id']) == $the_page_id ) ) {

        $q->set('my_kindle_books_is_called', TRUE );
        return $q;

        }
        elseif( isset( $q->query_vars['pagename'] ) AND ( ($q->query_vars['pagename'] == $the_page_name) OR ($_pos_found = strpos($q->query_vars['pagename'],$the_page_name.'/') === 0) ) ) {

        $q->set('my_kindle_books_is_called', TRUE );
        return $q;

        }
        else {

        $q->set('my_kindle_books_is_called', FALSE );
        return $q;

    }
}

add_filter( 'parse_query', 'my_kindle_books_parser' );

function my_kindle_books_filter( $posts ) {

    global $wp_query;

    if( $wp_query->get('my_kindle_books_is_called') ) {

        $my_kindle_books_data = get_option( 'my_kindle_books_data' );

        if (!empty( $my_kindle_books_data )) {

            $my_kindle_books = unserialize( $my_kindle_books_data );

            if (is_array( $my_kindle_books  )) {

                $table_of_books = my_kindle_books_table( $my_kindle_books );
                $posts[0]->post_content = $table_of_books;

            } else {

                delete_option( 'my_kindle_books_data' );
            }
        }
    }

    return $posts;

}

add_filter( 'the_posts', 'my_kindle_books_filter' );