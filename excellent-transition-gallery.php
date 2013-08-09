<?php

/*
Plugin Name: Excellent transition gallery
Plugin URI: http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/
Description: Don't just display images, showcase them in style using this Excellent transition gallery plugin. Randomly chosen Transitional effects in IE browsers. For other browsers that don't support these built in effects, a custom fade transition is used instead.  
Author: Gopi.R
Version: 8.1
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
	?>
	<div class="wrap">
	  <div class="form-wrap">
		<div id="icon-edit" class="icon32 icon32-posts-post"><br>
		</div>
		<h2>Excellent transition gallery</h2>
		<?php
		$etgwtlt_title = get_option('etgwtlt_title');
		$etgwtlt_pluginurl = get_option('etgwtlt_pluginurl');
		$etgwtlt_xmllocation = get_option('etgwtlt_xmllocation');
		$etgwtlt_pause = get_option('etgwtlt_pause');
		$etgwtlt_transduration = get_option('etgwtlt_transduration');
	
		if (isset($_POST['etgwtlt_form_submit']) && $_POST['etgwtlt_form_submit'] == 'yes')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('etgwtlt_form_setting');
				
			$etgwtlt_title = stripslashes($_POST['etgwtlt_title']);
			$etgwtlt_pluginurl = stripslashes($_POST['etgwtlt_pluginurl']);
			$etgwtlt_xmllocation = stripslashes($_POST['etgwtlt_xmllocation']);
			$etgwtlt_pause = stripslashes($_POST['etgwtlt_pause']);
			$etgwtlt_transduration = stripslashes($_POST['etgwtlt_transduration']);
			
			
			update_option('etgwtlt_title', $etgwtlt_title );
			update_option('etgwtlt_pluginurl', $etgwtlt_pluginurl );
			update_option('etgwtlt_xmllocation', $etgwtlt_xmllocation );
			update_option('etgwtlt_pause', $etgwtlt_pause );
			update_option('etgwtlt_transduration', $etgwtlt_transduration );
			
			?>
			<div class="updated fade">
				<p><strong>Details successfully updated.</strong></p>
			</div>
			<?php
		}
		?>
		<h3>Plugin setting</h3>
		<form name="etgwtlt_form" method="post" action="#">
			
			<label for="tag-title">Title</label>
			<input name="etgwtlt_title" type="text" value="<?php echo $etgwtlt_title; ?>"  id="etgwtlt_title" size="40" maxlength="100">
			<p>Please enter your widget title.</p>
			
			<label for="tag-title">Pause</label>
			<input name="etgwtlt_pause" type="text" value="<?php echo $etgwtlt_pause; ?>"  id="etgwtlt_pause" maxlength="4">
			<p>Please enter pause between content change in millisec. (Example: 2000)</p>
			
			<label for="tag-title">Height</label>
			<input name="etgwtlt_transduration" type="text" value="<?php echo $etgwtlt_transduration; ?>"  id="etgwtlt_transduration" maxlength="4">
			<p>Please enter duration of transition. (Example: 200)</p>
			
			<label for="tag-title">Plugin URL</label>
			<input name="etgwtlt_pluginurl" type="text" value="<?php echo $etgwtlt_pluginurl; ?>"  id="etgwtlt_pluginurl"  size="100">
			<p>Please enter you plugin URL.</p>
			
			<label for="tag-title">XML file location</label>
			<input name="etgwtlt_xmllocation" type="text" value="<?php echo $etgwtlt_xmllocation; ?>" id="etgwtlt_xmllocation" size="130">
			<p>Please enter your XML file location.</p>
			
			<div style="height:10px;"></div>
			<input type="hidden" name="etgwtlt_form_submit" value="yes"/>
			<input name="etgwtlt_submit" id="etgwtlt_submit" class="button" value="Submit" type="submit" />
			<a class="button" target="_blank" href="http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/">Help</a>
			<?php wp_nonce_field('etgwtlt_form_setting'); ?>
		</form>
		</div>
	<h3>Plugin configuration option</h3>
	<ol>
		<li>Drag and drop the widget to your sidebar.</li>
		<li>Add directly in to the theme using PHP code.</li>
		<li>Add the plugin in the posts or pages using short code.</li>
	</ol>
	<p class="description">Check official website for more information <a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/">click here</a></p>
	</div>
	<?php
}

function etgwtlt_control()
{
	echo '<p>Excellent transition gallery.<br> To change the setting goto <b>Excellent transition gallery</b> link on Setting menu.';
	echo ' <a href="options-general.php?page=excellent-transition-gallery">';
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
	// No action required.
}

function etgwtlt_add_to_menu() 
{
	add_options_page('Excellent transition gallery', 'Excellent transition gallery', 'manage_options', 'excellent-transition-gallery', 'etgwtlt_admin_option' );
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