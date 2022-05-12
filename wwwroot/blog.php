<?php
	include('site.php');

	if (@$_GET['entry']) {
		# only accept valid input (YYYY-MM-DD) - anything else is garbage
		# Allow -wip for work in progress entries
		if (preg_match('/^\d\d\d\d-\d\d-\d\d(-wip)?$/', $_GET['entry'])) {
			$tfile = 'blog-entries/'.$_GET['entry'];
			# Gross hack... Deffo a better way to do this; oh well.
			if (!file_exists($tfile)) {
				$tfile .= '.htm';
				if (!file_exists($tfile)) {
					$tfile .= 'l';
					if (file_exists($tfile)) {
						$_GET['entry'] .= '.html';
					}
				}
				else {
					$_GET['entry'] .= '.htm';
				}
			}
			# error already handled...
			if (file_exists($tfile)) {
				parse_blog($_GET['entry'], true);
			}
			else {
				page_header('Not Found', 'blog');
				print "<h1>Not found</h1><p>Blog entry not found</p>";
			}
		}
		else {
			page_header('Not Found', 'blog');
			print "<h1>Not found</h1><p>Blog entry not found</p>";
		}
	}
	else {
		page_header('Blog', 'blog');
		foreach (scandir('blog-entries/', 1) as $file) {
			# match yyyy-mm-dd files with an optional .htm(l) extension
			if (preg_match('/^\d\d\d\d-\d\d-\d\d(\.html?)?$/', $file)) {
				$entries[] = $file;
			}
		}
		# Kind of a gross kludge but want to avoid <hr /> on last entry and easiest way to do:
		# wish scandir had a filter/regexp option...
		foreach ($entries as $entry) {
			parse_blog($entry);
			if (end($entries) != $entry) {
				print "<hr />";
			}
		}
	}

?>

<?php
	page_footer();
?>

