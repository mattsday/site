<?php
/*****************************************************************************
 * Copying and distribution of this file, with or without modification,
 * are permitted in any medium without royalty provided the copyright
 * notice and this notice are preserved.  This file is offered as-is,
 * without any warranty.
 *****************************************************************************/

// If this file is requested, just print its source and exit
if ($_SERVER['SCRIPT_FILENAME'] == '/srv/http/matt/site.php') {
	print_source();
	exit();
}

function page_header ($title, $id, $context = 'none') {
	// Manually set HTTP headers to be sure of content type:
	header('Content-Type: text/html; charset=UTF-8');
	header('Content-Language: en-gb');

	// Check if we want source code:
	if (@$_GET['show'] == 'src') {
		print_source();
		exit;
	}
	$top = implode('', file('templates/top.tpl'));
	// Replace page title in [[title]] tags:
	$top = str_replace('[[title]]', $title, $top);
	// Find the page ID and make it an active tab:
	$top = str_replace('id="'.$id.'"', 'id="'.$id.'" class="active"', $top);
	if ($context == 'code') {
		$top = str_replace('id="code_sublink_hidden"', 'id="code_sublink"', $top);
	}
	// Support "context" for tabs too:
	$top = str_replace('class="nav-item" id="nav_'.$id.'"','class="nav-item active" id="nav_'.$id.'"', $top);

	print $top;
}

function page_footer () {
	// Put in page modified details:
	$bottom = implode('', file('templates/bottom.tpl'));
	$modified = date('r', filemtime($_SERVER['SCRIPT_FILENAME']));
	$bottom = str_replace('[[mod]]',$modified, $bottom);
	print $bottom;
}

function parse_blog ($file, $single = false) {
	$blog_date = $file;
	$contents = implode('', file('blog-entries/'.$file));
	$title = preg_match_all('/<h\d style="blog_title">(.*?)<\/h\d>/', $contents, $match);
	$title = $match[0][0];
	if ($single == true) {
		page_header($match[1][0], 'blog');
	}
	$date = preg_replace('/\.html?/', '', $file);
	$new_title = $title."\n<p class='blog_sub'>Posted: ".$date.". Modified: ".date('Y-m-d', filemtime('blog-entries/'.$file)).". <a href=\"blog?entry=".$date."\">Link</a>.</p>";
	$contents = preg_replace('/<h\d style="blog_title">(.*?)<\/h\d>/', $new_title, $contents);
	print $contents;
}

function print_source () {
	$src = highlight_file($_SERVER['SCRIPT_FILENAME'], true);
	// Make this file a clickable hyperlink:
	$src = str_replace('site.php', '<a href="site">site.php</a>', $src);
	print $src;
}
?>
