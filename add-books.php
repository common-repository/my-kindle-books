<div class='wrap'>

	<?php screen_icon("plugins"); ?>

	<h2>My Kindle Books</h2>

	<form id="book_form" method="POST" action="">

		<table id="book_form" class="form-table">
			<tr valign="top">
				<th scope="row"><label for="files_to_upload">Amazon Store:</label></th>
				<td>
					<select name="amazon_tld" id="amazon_tld">
						<option value="com" <?php if ($amazon_tld=='com') { echo 'SELECTED=SELECTED'; } ?>>USA</option>
						<option value="co.uk" <?php if ($amazon_tld=='co.uk') { echo 'SELECTED=SELECTED'; } ?>>UK</option>
						<option value="ca" <?php if ($amazon_tld=='ca') { echo 'SELECTED=SELECTED'; } ?>>Canada</option>
						<option value="cn" <?php if ($amazon_tld=='cn') { echo 'SELECTED=SELECTED'; } ?>>China</option>
						<option value="fr" <?php if ($amazon_tld=='fr') { echo 'SELECTED=SELECTED'; } ?>>France</option>
						<option value="de" <?php if ($amazon_tld=='de') { echo 'SELECTED=SELECTED'; } ?>>Germany</option>
						<option value="it" <?php if ($amazon_tld=='it') { echo 'SELECTED=SELECTED'; } ?>>Italy</option>
						<option value="jp" <?php if ($amazon_tld=='jp') { echo 'SELECTED=SELECTED'; } ?>>Japan</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="files_to_upload">Add Kindle Books (Kindle/documents/):</label></th>
				<td><input type="file" name="files_to_upload" id="files_to_upload" multiple="" onChange="makeFileList();" /></td>
			</tr>
		</table>
	</form>
</div>
<div id="loading_gif">
	<img src="<?= plugins_url( 'images/loading.gif', __FILE__ ); ?>" />
</div>


<?php
   $nonce = wp_create_nonce("my_kindle_book_nonce");
   $link = admin_url('admin-ajax.php?action=get_amazon_data&nonce='.$nonce);
?>

<script type="text/javascript">

	function makeFileList() {

		var input = document.getElementById( "files_to_upload" );
		var my_books = '';

		for (var i = 0; i < input.files.length; i++) {

			my_books += input.files[i].name + "\n";
		}

		jQuery('#book_list').remove();
		jQuery('#loading_gif').css( 'display','block' );
		jQuery('#message').css( 'display','none' );

		var amazon_tld = jQuery('#amazon_tld option:selected').val();

	    nonce = '<?= $nonce ?>';

	      jQuery.ajax({
	         type : "post",
	         dataType : "json",
	         async : true,
	         url : '<?= bloginfo( wpurl ); ?>/wp-admin/admin-ajax.php',
	         data : {action: "get_amazon_data", my_books : my_books, nonce: nonce,amazon_tld:amazon_tld  },
	         success: function(response) {

	            if(response.type == "success") {

	               jQuery('#loading_gif').css( 'display','none' );
	               jQuery('.wrap').after( response.html );
	               jQuery( "#sortable" ).sortable();
					jQuery( "#sortable" ).sortable({
						stop: function( event, ui ) {

							saveNewOrder();
						}
					});
					jQuery("#sortable .delete").click(function() {

					    deleteBook( jQuery(this).parent() );
					});

	            } else {

	            	jQuery('#loading_gif').css( 'display','none' );
					jQuery('.wrap').after( response.html );
	            }
	         }
	      })
	}

	jQuery(function() {

		jQuery( "#sortable" ).sortable();
		jQuery( "#sortable" ).disableSelection();
		jQuery( "#sortable" ).sortable({
			stop: function( event, ui ) {

					saveNewOrder();
				}
		});
		jQuery("#sortable .delete").click(function() {

			deleteBook( jQuery(this).parent() );
		});

		jQuery('#amazon_tld option[value="<?= get_option( "my_kindle_books_amazon_tld" ) ?>"]').attr( 'selected','selected' );
	});

	function deleteBook( book ){

		var isbn = book.attr( 'data-isbn' );
		var nonce = '<?= $nonce ?>';

		jQuery(book).children( ".delete" ).attr( 'disabled','disabled' );
		jQuery(book).children( ".delete" ).animate({ width:"100px" },500 );
		jQuery(book).children( "button" ).text( 'Deleting...' );

		jQuery.ajax({
		 	type : "post",
			url : '<?= bloginfo( wpurl ); ?>/wp-admin/admin-ajax.php',
			async : true,
			data : {action: "delete_book", isbn : isbn, nonce: nonce},
			success: function(response) {

				if (response!='success') {

					alert( 'Sorry something went wrong, please try again' );
				} else {

					book.remove();
					jQuery(book).children( "button" ).text( 'Delete' );
				}
			}
		});
	}

	function saveNewOrder(){

		var new_book_order = [];

		jQuery( "#book_list li" ).each(function( index ) {
			new_book_order[index] = jQuery(this).attr( 'data-isbn' );
		});

		nonce = '<?= $nonce ?>';

		 jQuery.ajax({
		 	type : "post",
			url : '<?= bloginfo( wpurl ); ?>/wp-admin/admin-ajax.php',
			data : {action: "new_book_order", new_book_order : new_book_order, nonce: nonce},
				success: function(response) {

				if (response!='success') {
					alert( 'Sorry something went wrong, please try again' );
				}
			}
		});
	}
</script>