<?php

/*
Plugin Name: Excellent transition gallery
Plugin URI: http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/
Description: Don't just display images, showcase them in style using this Excellent transition gallery plugin. Randomly chosen Transitional effects in IE browsers. For other browsers that don't support these built in effects, a custom fade transition is used instead.  
Author: Gopi.R
Version: 7.0
Author URI: http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/
Donate link: http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function etgwtlt_show() 
{
	$etgwtlt_package = "";
	$etgwtlt_pluginurl = get_option('etgwtlt_pluginurl');
	$etgwtlt_xmllocation = get_option('etgwtlt_xmllocation');
	
	$doc = new DOMDocument();
	$doc->load( $etgwtlt_xmllocation . 'widget.xml' );
	$images = $doc->getElementsByTagName( "image" );
	
	foreach( $images as $image )
	{
	  $paths = $image->getElementsByTagName( "path" );
	  $path = $paths->item(0)->nodeValue;
	  $targets = $image->getElementsByTagName( "target" );
	  $target = $targets->item(0)->nodeValue;
	  $titles = $image->getElementsByTagName( "title" );
	  $title = $titles->item(0)->nodeValue;
	  $links = $image->getElementsByTagName( "link" );
	  $link = $links->item(0)->nodeValue;
	  $etgwtlt_package = $etgwtlt_package .'["'.$path.'", "'.$link.'", "'.$target.'", "'.$title.'"],';
	}
	
	$etgwtlt_package = substr($etgwtlt_package,0,(strlen($etgwtlt_package)-1));
	?>
	<link rel='stylesheet' href='<?php echo $etgwtlt_pluginurl; ?>style.css' type='text/css' />
    <script type="text/javascript">

	var flashyshow=new excellent_transition_gallery_with_title_link_target({ 
		wrapperid: "widget_xml", 
		wrapperclass: "etgwtlt_widget_xml", 
		imagearray: [
			<?php echo $etgwtlt_package; ?>
		],
		pause: <?php echo get_option('etgwtlt_pause'); ?>, 
		transduration: <?php echo get_option('etgwtlt_transduration'); ?> 
	})
	
	</script>
    <?php
}

add_shortcode( 'excellent-transition-gallery', 'etgwtlt_shortcode' );

function etgwtlt_shortcode( $atts ) 
{
	$etgwtlt_xml = "";
	$etgwtlt_package = "";
	
	//[excellent-transition-gallery filename="sample.xml"]
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	$filename = $atts['filename'];
	
	$etgwtlt_pluginurl = get_option('etgwtlt_pluginurl');
	$etgwtlt_xmllocation = get_option('etgwtlt_xmllocation');
	
	$doc = new DOMDocument();
	$doc->load( $etgwtlt_xmllocation . $filename );
	$images = $doc->getElementsByTagName( "image" );
	
	foreach( $images as $image )
	{
	  $paths = $image->getElementsByTagName( "path" );
	  $path = $paths->item(0)->nodeValue;
	  $targets = $image->getElementsByTagName( "target" );
	  $target = $targets->item(0)->nodeValue;
	  $titles = $image->getElementsByTagName( "title" );
	  $title = $titles->item(0)->nodeValue;
	  $links = $image->getElementsByTagName( "link" );
	  $link = $links->item(0)->nodeValue;
	  $etgwtlt_package = $etgwtlt_package .'["'.$path.'", "'.$link.'", "'.$target.'", "'.$title.'"],';
	}
	
	$etgwtlt_package = substr($etgwtlt_package,0,(strlen($etgwtlt_package)-1));
	
	$newwrapperid = str_replace(".","_",$matches[1]);
	
	$etgwtlt_xml = $etgwtlt_xml .'<link rel="stylesheet" href="'.$etgwtlt_pluginurl.'style.css" type="text/css" />';
    $etgwtlt_xml = $etgwtlt_xml .'<script type="text/javascript">';
	$etgwtlt_xml = $etgwtlt_xml .'var flashyshow=new excellent_transition_gallery_with_title_link_target({ wrapperid: "'.$newwrapperid.'", wrapperclass: "etgwtlt_'.$newwrapperid.'", imagearray: ['.$etgwtlt_package.'],pause: '. get_option('etgwtlt_pause').',transduration: '. get_option('etgwtlt_transduration').' })';
	$etgwtlt_xml = $etgwtlt_xml .'</script>';
    
	return $etgwtlt_xml;
}

function etgwtlt_install() 
{
	add_option('etgwtlt_title', "Slideshow");
	$siteurl = get_option('siteurl');
	add_option('etgwtlt_pluginurl', $siteurl ."/wp-content/plugins/excellent-transition-gallery/");
	add_option('etgwtlt_xmllocation', $siteurl ."/wp-content/plugins/excellent-transition-gallery/gallery/");
	add_option('etgwtlt_pause', "2000");
	add_option('etgwtlt_transduration', "1000");
}

function etgwtlt_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('etgwtlt_title');
	echo $after_title;
	etgwtlt_show();
	echo $after_widget;
}

function etgwtlt_admin_option() 
{
	echo "<div class='wrap'>";
	echo "<h2>Excellent transition gallery</h2>"; 
    
	$etgwtlt_title = get_option('etgwtlt_title');
	$etgwtlt_pluginurl = get_option('etgwtlt_pluginurl');
	$etgwtlt_xmllocation = get_option('etgwtlt_xmllocation');
	$etgwtlt_pause = get_option('etgwtlt_pause');
	$etgwtlt_transduration = get_option('etgwtlt_transduration');
	
	
	if (@$_POST['etgwtlt_submit']) 
	{
		$etgwtlt_title = stripslashes($_POST['etgwtlt_title']);
		$etgwtlt_pluginurl = stripslashes($_POST['etgwtlt_pluginurl']);
		$etgwtlt_xmllocation = stripslashes($_POST['etgwtlt_xmllocation']);
		$etgwtlt_title_yes = stripslashes($_POST['etgwtlt_title_yes']);
		$etgwtlt_transduration = stripslashes($_POST['etgwtlt_transduration']);
		
		
		update_option('etgwtlt_title', $etgwtlt_title );
		update_option('etgwtlt_pluginurl', $etgwtlt_pluginurl );
		update_option('etgwtlt_xmllocation', $etgwtlt_xmllocation );
		update_option('etgwtlt_pause', $etgwtlt_pause );
		update_option('etgwtlt_transduration', $etgwtlt_transduration );
	}
	?><form name="form_etgwtlt" method="post" action="">
	<table width="100%" border="0" cellspacing="0" cellpadding="3"><tr><td width="69%" align="left">
	<?php
	echo '<p>Title:<br><input  style="width: 300px;" maxlength="200" type="text" value="';
	echo $etgwtlt_title . '" name="etgwtlt_title" id="etgwtlt_title" /></p>';
	echo '<p>Pause:<br><input  style="width: 100px;" maxlength="4" type="text" value="';
	echo $etgwtlt_pause . '" name="etgwtlt_pause" id="etgwtlt_pause" /> Only Number / Pause between content change (millisec)</p>';
	echo '<p>Transduration:<br><input  style="width: 100px;" maxlength="4" type="text" value="';
	echo $etgwtlt_transduration . '" name="etgwtlt_transduration" id="etgwtlt_transduration" /> Only Number / Duration of transition</p>';
	echo '<p>Plugin URL:<br><input  style="width: 650px;" type="text" value="';
	echo $etgwtlt_pluginurl . '" name="etgwtlt_pluginurl" id="etgwtlt_pluginurl" /></p>';
	echo '<p>Image XML Location:<br><input  style="width: 650px;" type="text" value="';
	echo $etgwtlt_xmllocation . '" name="etgwtlt_xmllocation" id="etgwtlt_xmllocation" /></p>';

	echo '<input name="etgwtlt_submit" id="etgwtlt_submit" class="button-primary" value="Submit" type="submit" />';
	?>
	</td>
	<td width="31%" align="center" valign="middle"></td></tr></table>
	</form>
	<br /><strong>Plugin configuration</strong>
	<ol>
		<li>Drag and drop the widget</li>
		<li>Short code for pages and posts</li>
		<li>Add directly in the theme (Copy and past the below mentioned code to your desired template location)</li>
	</ol>
	Check official website for live demo and more information <a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/'>click here</a><br />
	<?php
	echo "</div>";
}

function etgwtlt_control()
{
	echo '<p>Excellent transition gallery.<br> To change the setting goto Excellent transition gallery link on Setting menu.';
	echo ' <a href="options-general.php?page=excellent-transition-gallery/excellent-transition-gallery.php">';
	echo 'click here</a></p>';
}

function etgwtlt_widget_init() 
{
	if(function_exists('wp_register_sidebar_widget')) 	
	{
		wp_register_sidebar_widget('Excellent-transition-gallery', 'Excellent transition gallery', 'etgwtlt_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 	
	{
		wp_register_widget_control('Excellent-transition-gallery', array('Excellent transition gallery', 'widgets'), 'etgwtlt_control');
	} 
}

function etgwtlt_deactivation() 
{

}

function etgwtlt_add_to_menu() 
{
	add_options_page('Excellent transition gallery', 'Excellent transition gallery', 'manage_options', __FILE__, 'etgwtlt_admin_option' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'etgwtlt_add_to_menu');
}

function etgwtlt_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'etgwtlt-js', get_option('siteurl').'/wp-content/plugins/excellent-transition-gallery/javascript.js');
	}	
}

add_action('init', 'etgwtlt_add_javascript_files');
add_action("plugins_loaded", "etgwtlt_widget_init");
register_activation_hook(__FILE__, 'etgwtlt_install');
register_deactivation_hook(__FILE__, 'etgwtlt_deactivation');
add_action('init', 'etgwtlt_widget_init');
?>
