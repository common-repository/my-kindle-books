<div class='wrap'>

	<?php screen_icon("plugins"); ?>

	<h2>My Kindle Books - Affiliate Tags</h2>

	<form id="affiliate_tag_form" method="POST" action="">

		<table id="affiliate_tag_form" class="form-table">

			<tr valign="top">
				<th scope="row"><label for="us_tag">USA:</label></th>
				<td><input type="text" name="us_tag" id="us_tag" value="<? if (!empty( $current_tags['com'] )) { echo $current_tags['com']; } ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="uk_tag">UK:</label></th>
				<td><input type="text" name="uk_tag" id="uk_tag" value="<? if (!empty( $current_tags['co.uk'] )) { echo $current_tags['co.uk']; } ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="files_to_upload">Canada:</label></th>
				<td><input type="text" name="ca_tag" value="<? if (!empty( $current_tags['ca'] )) { echo $current_tags['ca']; } ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="files_to_upload">France:</label></th>
				<td><input type="text" name="fr_tag" value="<? if (!empty( $current_tags['fr'] )) { echo $current_tags['fr']; } ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="files_to_upload">Germany:</label></th>
				<td><input type="text" name="de_tag" value="<? if (!empty( $current_tags['de'] )) { echo $current_tags['de']; } ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="files_to_upload">Italy:</label></th>
				<td><input type="text" name="it_tag" value="<? if (!empty( $current_tags['it'] )) { echo $current_tags['it']; } ?>" /></td>
			</tr>
		</table>

		 <?php submit_button(); ?>
	</form>
</div>