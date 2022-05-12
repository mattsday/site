<?php
    include('site.php');
    page_header('Converting FLAC to AAC', 'convflac', 'code');
?>
<h1>Converting FLAC to AAC</h1>
<p>I wrote a script that converts a FLAC file to an AAC file, copying over all metadata. Why? Because iTunes doesn't support FLAC files (nor does anything else from Apple) but it's a good format to re-download tracks from.</p>

<p><a href="misc/convflac.zip">Download the script here</a><br /><a href="misc/convflac-src">Source Code</a></p>

<h2>Pre-requisites</h2>
<p>You will need <a href="http://sourceforge.net/projects/faac/">faac</a> and <a href="http://flac.sourceforge.net/">flac</a> installed. If you use a mac with MacPorts then you can install them by running:</p>
<pre>sudo port install faac flac</pre>
<p>For Debian users, you can install them by running:</p>
<pre>sudo apt-get install flac faac</pre>
<p>You also need a working copy of Perl (should be installed by default).</p>

<h2>Installing the script</h2>
<p><a href="misc/convflac.zip">Download the script</a> and copy it to an appropriate directory. In this example, I will use /usr/local/bin - but you may put it elsewhere (e.g. ~/bin):</p>
<pre>unzip convflac.zip
sudo mv convflac /usr/local/bin/
sudo chmod +x /usr/local/bin/convflac</pre>
<p>To test if it works, type the command <span style="font-family: monospace;">convflac</span>:</p>
<pre>$ convflac
No input flac file

Usage: convflac &lt;filein.flac&gt; [fileout.m4a]
If no fileout is specified, then filein.m4a will be created</pre>

<h2>Converting</h2>
<p>The flac converter is as easy as this:</p>
<pre>convflac "my song.flac"</pre>
<p>This will convert "my song.flac" in to "my song.m4a". If there is meta information in the flac file, it will also copy that. You can specify the output file as well:</p>
<pre>convflac "my song.flac" "aac/my song-converted.m4a"</pre>

<h2>Mass Converting</h2>
<p>To convert an entire directory, a simple shell script will suffice:</p>
<pre>for i in *.flac; do convflac "$i"; done;</pre>
<p>Voila! Your entire directory should get converted to aac.</p>

<h2>Advanced Tweaking</h2>
<p>By default, convflac will sort out most metadata for you. However, you can customise this by editing ~/.convflac.</p>

<p>For example, FLAC files have the metadata <span style="font-family: monospace;">tracknumber</span> whilst AAC files call this <span style="font-family: monospace;">track</span>. To facilitate this conversion, we use a mutation map:</p>
<pre>mutation_tracknumber = track</pre>
<p>You can customise any FLAC value in this way - by default convflac will create a couple for you though, so you don't need to worry about track numbers or date meta data.</p>
<p>You can also pass arguments to faac and flac using the <span style="font-family: monospace;">codec_faac</span> and <span style="font-family: monospace;">codec_flac</span> options respectively. These will overrwrite any used by the script, so take great care with them.</p>

<h2>Sample Configuration File</h2>
<pre># convflac config file
# lines starting with # are comments, everything else is key=value

# Codec options - uncomment to make changes to quality settings
# Default is -q 300 -w -s for faac, it's recommended you leave flac alone
# See man flac and man faac for more info
#codec_faac = -q 300 -w -s
#codec_flac = -cd

# Mutation options (for tags):
#mutation_flacname = faacname
mutation_tracknumber = track
mutation_notes = comment
mutation_date = year</pre>
<h2>Troubleshooting</h2>
<p>The most common problem is going to be the Perl directory. To fix this, type:</p>
<pre>which perl</pre>
<p>This should give you a directory - e.g. <span style="font-family: monospace;">/usr/bin/perl</span> - edit the convflac file to put it on the top line, prefixed with a "bang". For example:</p>
<pre>#!/usr/bin/perl</pre>
<p>This script almost certainly won't work on Windows and probably never will. I don't know enough about how Windows handles data pipes, but I'm going to assume it doesn't do it very well if at all.</p>

<?php
    page_footer();
?>
