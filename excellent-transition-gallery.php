<?php

/*
Plugin Name: Excellent transition gallery
Plugin URI: http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/
Description: Don't just display images, showcase them in style using this Excellent transition gallery plugin. Randomly chosen Transitional effects in IE browsers.  
Author: Gopi.R
Version: 2.0
Author URI: http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/
Donate link: http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/
*/

function etgwtlt_show() 
{
	$etgwtlt_pluginurl = get_option('etgwtlt_pluginurl');
	
	$doc = new DOMDocument();
	$doc->load( $etgwtlt_pluginurl . 'gallery/widget.xml' );
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
    <script type="text/javascript" src="<?php echo $etgwtlt_pluginurl; ?>javascript.js"></script>
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


add_filter('the_content','etgwtlt_show_filter');

function etgwtlt_show_filter($content){
	return 	preg_replace_callback('/\[excellent-transition-gallery=(.*?)\]/sim','etgwtlt_show_filter_Callback',$content);
}

function etgwtlt_show_filter_Callback($matches) 
{
	$etgwtlt_pluginurl = get_option('etgwtlt_pluginurl');
	
	$doc = new DOMDocument();
	$doc->load( $etgwtlt_pluginurl . 'gallery/' . $matches[1] );
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
    $etgwtlt_xml = $etgwtlt_xml .'<script type="text/javascript" src="'.$etgwtlt_pluginurl.'javascript.js"></script>';
    $etgwtlt_xml = $etgwtlt_xml .'<script type="text/javascript">';
	$etgwtlt_xml = $etgwtlt_xml .'var flashyshow=new excellent_transition_gallery_with_title_link_target({ wrapperid: "'.$newwrapperid.'", wrapperclass: "etgwtlt_'.$newwrapperid.'", imagearray: ['.$etgwtlt_package.'],pause: '. get_option('etgwtlt_pause').',transduration: '. get_option('etgwtlt_transduration').' })';
	$etgwtlt_xml = $etgwtlt_xml .'</script>';
    
    //$etgwtlt_xml = '---------'. $matches[1];
    
	return $etgwtlt_xml;
}

function etgwtlt_install() 
{
	add_option('etgwtlt_title', "Slideshow");
	$siteurl = get_option('siteurl');
	add_option('etgwtlt_pluginurl', $siteurl ."/wp-content/plugins/excellent-transition-gallery/");
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
	echo "<h2>"; 
	echo wp_specialchars( "Excellent transition gallery" ) ;
	echo "</h2>";
    
	$etgwtlt_title = get_option('etgwtlt_title');
	$etgwtlt_pluginurl = get_option('etgwtlt_pluginurl');
	$etgwtlt_pause = get_option('etgwtlt_pause');
	$etgwtlt_transduration = get_option('etgwtlt_transduration');
	
	
	if ($_POST['etgwtlt_submit']) 
	{
		$etgwtlt_title = stripslashes($_POST['etgwtlt_title']);
		$etgwtlt_pluginurl = stripslashes($_POST['etgwtlt_pluginurl']);
		$etgwtlt_title_yes = stripslashes($_POST['etgwtlt_title_yes']);
		$etgwtlt_transduration = stripslashes($_POST['etgwtlt_transduration']);
		
		
		update_option('etgwtlt_title', $etgwtlt_title );
		update_option('etgwtlt_pluginurl', $etgwtlt_pluginurl );
		update_option('etgwtlt_pause', $etgwtlt_pause );
		update_option('etgwtlt_transduration', $etgwtlt_transduration );
	}
	?><form name="form_etgwtlt" method="post" action="">
	<table width="100%" border="0" cellspacing="0" cellpadding="3"><tr><td width="69%" align="left">
	<?php
	echo '<p>Title:<br><input  style="width: 450px;" maxlength="200" type="text" value="';
	echo $etgwtlt_title . '" name="etgwtlt_title" id="etgwtlt_title" /></p>';
	echo '<p>Pause:<br><input  style="width: 100px;" maxlength="4" type="text" value="';
	echo $etgwtlt_pause . '" name="etgwtlt_pause" id="etgwtlt_pause" />Only Number / Pause between content change (millisec)</p>';
	echo '<p>Transduration:<br><input  style="width: 100px;" maxlength="4" type="text" value="';
	echo $etgwtlt_transduration . '" name="etgwtlt_transduration" id="etgwtlt_transduration" />Only Number / Duration of transition (affects only IE users)</p>';
	echo '<p>Plugin URL:<br><input  style="width: 450px;" type="text" value="';
	echo $etgwtlt_pluginurl . '" name="etgwtlt_pluginurl" id="etgwtlt_pluginurl" /></p>';

	echo '<input name="etgwtlt_submit" id="etgwtlt_submit" class="button-primary" value="Submit" type="submit" />';
	?>
	</td>
	<td width="31%" align="center" valign="middle"></td></tr></table>
	</form>
    <hr />
    <a href='http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/' target="_blank"><strong>All help & more Information available in this link and see demo in IE broswer.</strong></a><br /><br />
    We can use this plug-in in Three different way.<br />
	
    <h2><?php echo wp_specialchars( '1.Drag and drop the widget!' ); ?></h2>
    1.	Go to widget menu and drag and drop the "Excellent transition gallery" widget to your sidebar location. or <br />

    <h2><?php echo wp_specialchars( '2.Paste the below code to your desired template location!' ); ?></h2>
    2.	Copy and past the below mentioned code to your desired template location.
    <div style="padding-top:7px;padding-bottom:7px;">
    <code style="padding:7px;">
    &lt;?php if (function_exists (etgwtlt_show)) etgwtlt_show(); ?&gt;
    </code></div>
    
    <h2><?php echo wp_specialchars( '3.Use below code in post or page!' ); ?></h2>
     3. Use below code in post or page.
    <div style="padding-top:7px;padding-bottom:7px;">
    <input name="" style="width:400px;height:25px;" value="[excellent-transition-gallery=sample.xml]" type="text" />
    </div>
    
    <br />In above code "sample.xml" is your gallery XML file, the XML file should be available in the gallery folder
    
    <h2><?php echo wp_specialchars( 'About Plugin' ); ?></h2>
    Plug-in created by <a target="_blank" href='http://www.gopiplus.com/work/'>Gopi</a>.<br> 
    <a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/'>Click here</a> to post suggestion or comments or feedback. <br> 
    <a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/'>Click here</a> to see live demo & more info. <br> 
    <a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/'>Click here</a> to download my other plugins.  
    
    <h2><?php echo wp_specialchars( 'Help' ); ?></h2>
    <p style="color:#990000;">
	1. This plug-in will not create any thumbnail of the image.<br>
	2. To change or use the fixed width take "javascript.js" file from plug-in directory and go to line 63 and fix the width, see below.<br>
	<br><code>slideHTML+='&lt;img src=&quot;'+this.imagearray[index][0]+'&quot; /&gt;'<br>
	to<br>
	slideHTML+='&lt;img width=&quot;200&quot; HEIGHT=&quot;150&quot; src=&quot;'+this.imagearray[index][0]+'&quot; /&gt;'</code>
	
    <h2><?php echo wp_specialchars( 'Faq?' ); ?></h2>
    How to arrange the width & height of the slideshow? <br> 
    How to change the slide delay time?<br>
    Where to upload my image?<br>  
    how the slide show manages the order? <br> 
    Do you want to change the gallery font style?<br>  
    More doubt?<br>
	<a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/'>View Answer</a>
    <a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/'><h2><?php echo wp_specialchars( 'Live demo' ); ?></h2></a>
    <a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/'>Live demo</a>
    <br></p>
	<?php
	echo "</div>";
}

function etgwtlt_control()
{
	echo '<p>Excellent transition gallery.<br> To change the setting goto Excellent transition gallery link under SETTING tab.';
	echo ' <a href="options-general.php?page=excellent-transition-gallery/excellent-transition-gallery.php">';
	echo 'click here</a></p>';
	?>
	<h2><?php echo wp_specialchars( 'About Plugin' ); ?></h2>
    Plug-in created by <a target="_blank" href='http://www.gopiplus.com/work/'>Gopi</a>.<br> 
    <a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/'>Click here</a> to post suggestion or comments or feedback. <br> 
    <a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/'>Click here</a> to see live demo & more info. <br> 
    <a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/excellent-transition-gallery/'>Click here</a> to download my other plugins.  
	<?php
}

function etgwtlt_widget_init() 
{
  	register_sidebar_widget(__('Excellent transition gallery'), 'etgwtlt_widget');   
	
	if(function_exists('register_sidebar_widget')) 	
	{
		register_sidebar_widget('Excellent transition gallery', 'etgwtlt_widget');
	}
	
	if(function_exists('register_widget_control')) 	
	{
		register_widget_control(array('Excellent transition gallery', 'widgets'), 'etgwtlt_control');
	} 
}

function etgwtlt_deactivation() 
{
	delete_option('etgwtlt_title');
	delete_option('etgwtlt_pluginurl');
	delete_option('etgwtlt_pause');
	delete_option('etgwtlt_transduration');
}

function etgwtlt_add_to_menu() 
{
	add_options_page('Excellent transition gallery', 'Excellent transition gallery', 7, __FILE__, 'etgwtlt_admin_option' );
}

add_action('admin_menu', 'etgwtlt_add_to_menu');
add_action("plugins_loaded", "etgwtlt_widget_init");
register_activation_hook(__FILE__, 'etgwtlt_install');
register_deactivation_hook(__FILE__, 'etgwtlt_deactivation');
add_action('init', 'etgwtlt_widget_init');
?>
