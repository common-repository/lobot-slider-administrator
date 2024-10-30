=== Lobot Slider Administrator ===
Contributors: chuckmo
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YDRKMDX6YXLUW
Tags: slider, admin, meta
Requires at least: 2.9
Tested up to: 3.1
Stable tag: 0.6.0

Creates a slide management interface in the WordPress backend and provides template tags for fetching the slides' information.

== Description ==

**This plugin is ideal for theme developers who need to provide their clients an easy-to-use interface for managing slider content.**

To fetch the slides, use the following code:
`//returns an array of arrays filled with each slide's info.
$the_slides = fourty_slider_get_slides();

/*
$the_slides = Array
(
    [0] => Array
        (
            [title] => Slide 1 Title
            [caption] => Here is the caption for Slide 1
            [link] => http://your-site.net/2010/09/14/hello-world/
            [image] => http://your-site.net/files/2011/01/steddyp4.jpg
            [external] => 
        )
 
    [1] => Array
        (
            [title] => Slide 2 Title
            [caption] => And here's the caption for Slide 2
            [link] => http://google.com
            [image] => http://your-site.net/files/2011/01/Slide3.gif
            [external] => 1
        )
 
)
*/
`

###Planned Features:
-	add an infinite number of slides
-	various aesthetic improvements
-	internationalization

>*"Lobot's not the chatty type, but he sure is loyal. And great with computers!"*
>―Lando Calrissian


== Installation ==

1. Upload "slider_admin.php" to the "/wp-content/plugins/" directory
2. Activate the plugin through the "Plugins" menu in WordPress
3. Configure the plugin in the "Settings" menu.
4. Setup your slider by editing the page specified in in the plugin settings.
4. Implement the template tags. See example on the installation page.


###Front-end Implementation Example

`<?php

//returns an array of arrays filled with each slide's info.
$the_slides = fourty_slider_get_slides();

$slide_n=0;

//loop through each slide and echo it's info
foreach($the_slides as $cur_slide){

	//get the slide's image
	echo '<img src="' . $cur_slide['image'] . '" />';
	
	//get the slide's title
	echo $cur_slide['title'];
	
	//get the slide's caption
	echo $cur_slide['caption'];
	
	//get the slide's link
	echo $cur_slide['link'];
	
	//is the link an external link?
	$is_external_link = $cur_slide['external']
	
	//this echoes the indicators onto each slide with a class for the current slide's indicator
	for($n=0; $n<count($the_slides); $n++){
	
		if($n==$slide_n){
			echo '<div class="indicator-on"></div>';
		} else {
			echo '<div class="indicator-off"></div>';
		}
		
	}
	
	$slide_n++;
	
}
?>`


== Frequently Asked Questions ==

= How do I setup the slider animations? =

**There is no front-end slider component to this plugin**; it is simply the back-end interface for managing slide content. You'll need to implement your own jQuery slider. Find one [via Google](http://lmgtfy.com/?q=jQuery+slider).

== Screenshots ==

1. This meta box is added to the editor for the page containing the slider

== Changelog ==

= 0.6.0 =
* add infinite slides!
* various bug fixes for better compatibility with various PHP configuration

= 0.4.3 =
* fixed bug that was causing all information to be lost on autosave.
* updated description and implementation example to make the 'fourty_slider_get_slides()' function easier to understand.

= 0.4.2 =
* fixed code that was generating a PHP warning.

= 0.4.1 =
* Javascript reworked.

= 0.4 =
* Major revamp to fix countless bugs.

= 0.3 =
* initial release... needs some optimization.

== Upgrade Notice ==

= 0.4.3 =
* this upgrade fixes a pretty major autosave bug.

= 0.4.2 =
* this upgrade will fix the PHP warning you may be getting.

= 0.4 =
* the previous version was majorly broken.

= 0.3 =
* it won't work below this version.
