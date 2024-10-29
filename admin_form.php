<?php
$data = array();

if (empty(adb_installation_param())) return;

if (!empty($_POST)){
    foreach (adb_installation_param() as $param){
        $new_value = '';
        if (isset($_POST[$param])) $new_value = esc_sql($_POST[$param]);
        update_option($param, $new_value);
    }
    
}
foreach (adb_installation_param() as $param){
    $data[$param] = get_option($param);
}

?>

<div class="wrap">
<h1><?php _e('Application banner configration', 'app-download-banner'); ?></h1>

<form method="post" novalidate="novalidate">
<table class="form-table">
<tbody>

<tr>
<th scope="row"><label for="adb_is_active"><?php _e('Plugin Activation', 'app-download-banner'); ?></label></th>
<td><input name="adb_is_active" type="checkbox" id="adb_is_active" value="true"<?php echo (!empty($data['adb_is_active'])) ? ' checked' : ''; ?>>
<p class="description" id="adb_is_active-description"><?php _e('De/Active your Banner in front', 'app-download-banner'); ?></p></td>
</tr>

<tr>
<th scope="row"><label for="adb_link_title"><?php _e('Application title', 'app-download-banner'); ?></label></th>
<td><input name="adb_link_title" type="text" id="adb_link_title" value="<?php echo $data['adb_link_title']; ?>" class="regular-text"></td>
</tr>

<tr>
<th scope="row"><label for="adb_link_logo"><?php _e('Application logo', 'app-download-banner'); ?></label></th>
<td><input name="adb_link_logo" type="text" id="adb_link_logo" value="<?php echo $data['adb_link_logo']; ?>" class="regular-text" placeholder="http://">
<input id="adb_link_logo_upload" class="button" type="button" value="<?php _e('Upload', 'app-download-banner'); ?>" />
    <br />Enter a URL or upload an image
<p class="description" id="adb_link_logo-description"><?php _e('Upload your application logo', 'app-download-banner'); ?></p></td>
</tr>

<tr>
<th scope="row"><label for="adb_link_description"><?php _e('Description', 'app-download-banner'); ?></label></th>
<td><input name="adb_link_description" type="text" id="adb_link_description" aria-describedby="adb_link_description-description" value="<?php echo $data['adb_link_description']; ?>" class="regular-text">
<p class="description" id="adb_link_description-description"><?php _e('Describe your app in a few words..', 'app-download-banner'); ?></p></td>
</tr>

<tr>
<th scope="row"><label for="adb_link_googleplay"><?php _e('Google play link', 'app-download-banner'); ?></label></th>
<td><input name="adb_link_googleplay" type="text" id="adb_link_googleplay" aria-describedby="adb_link_googleplay-description" value="<?php echo $data['adb_link_googleplay']; ?>" class="regular-text">
<p class="description" id="adb_link_googleplay-description"><?php _e('Your google play app link', 'app-download-banner'); ?></p></td>
</tr>

<tr>
<th scope="row"><label for="adb_link_appstore"><?php _e('App store link', 'app-download-banner'); ?></label></th>
<td><input name="adb_link_appstore" type="text" id="adb_link_appstore" aria-describedby="adb_link_appstore-description" value="<?php echo $data['adb_link_appstore']; ?>" class="regular-text">
<p class="description" id="adb_link_appstore-description"><?php _e('Your app store app link', 'app-download-banner'); ?></p></td>
</tr>

<tr>
<th scope="row"><label for="adb_link_text_color"><?php _e('Text color', 'app-download-banner'); ?></label></th>
<td><input name="adb_link_text_color" type="text" id="adb_link_text_color" value="<?php echo adb_get_text_color(); ?>" data-default-color="<?php echo adb_get_text_color(); ?>" class="regular-text wp-color-picker">
<p class="description" id="adb_link_text_color-description"><?php _e('Select your text color', 'app-download-banner'); ?></p></td>
</tr>

<tr>
<th scope="row"><label for="adb_link_background_color"><?php _e('Background color', 'app-download-banner'); ?></label></th>
<td><input name="adb_link_background_color" type="text" id="adb_link_background_color" value="<?php echo adb_get_background_color(); ?>" data-default-color="<?php echo adb_get_background_color(); ?>" class="regular-text wp-color-picker">
<p class="description" id="adb_link_background_color-description"><?php _e('Select your banner background color', 'app-download-banner'); ?></p></td>
</tr>

</tbody></table>

<p class="submit">
<?php submit_button( __('Save changes', 'app-download-banner') ); ?>
</p></form>

</div>