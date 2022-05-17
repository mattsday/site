<?php
    include('site.php');
    page_header('About this site', 'about_site');
?>
<p>This site uses some custom hacks to quickly knock out vaguely <a href="http://validator.w3.org/check?uri=http%3A%2F%2Fmatt.fragilegeek.com%2F">html compliant</a> and <a href="http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fmatt.fragilegeek.com%2F">CSS compliant</a>* pages.</p>

<p>The whole thing was written in about 30 minutes in php using <a href="http://www.vim.org">vim</a> via ssh.</p>

<p>In 2019 I spruced it up with <a href="https://getbootstrap.com/">Bootstrap 4</a> but didn't change much of the content.</p>

<p>However, in 2022 I moved the whole thing from a VM host to <a href="https://cloud.google.com/run">Cloud Run</a> in Google Cloud. It's free for the usage I get, it gets automatically built via a pipeline, and <a href="https://github.com/mattsday/site">the code is on GitHub</a>.</p>

<h1>Source Code</h1>
<p>The whole site is powered by <a href="site">site.php</a> - enjoy. Of interest may be the <a href="templates/top.tpl">top template</a>, <a href="templates/bottom.tpl">bottom template</a> and <a href="about_site?show=src">this very page</a>.</p>

<small>* it isn't fully CSS compliant because I wanted rounded edges on the top elements :( - but close enough</small>

<?php
    page_footer();
?>

