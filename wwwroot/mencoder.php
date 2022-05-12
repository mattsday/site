<?php
    include('site.php');
    page_header('Mencoder Settings', 'mencoder', 'code');
?>
<h1>Mencoder on OS X</h1>
<p>Hi there, I wrote this brief guide in case you have the same problem as me - wanting to convert a load of videos you have in the VP7 (or some other weird format) to XViD.</p>

<h2>Step 1 - Setting it all up</h2>
<p>You need to install <a href="http://www.macports.org/">MacPorts</a> and have it correctly setup.</p>
<p>Update MacPorts:</p>
<pre>sudo port selfupdate</pre>
<h2>Step 2 - Installing System Codecs</h2>
<p>I had issues just installing mplayer, so I just installed XViD, Mad, x264 and some other useful libraries this way:</p>
<pre>sudo port install xvid +universal x264 +universal libmad +universal yasm +universal git</pre>
<p>Note the +universal part - this is imperative.</p>
<h2>Step 3 - Installing Binary Codecs</h2>
<p>Download and install the <a href="http://www.mplayerhq.hu/design7/dload.html#binary_codecs">binary codecs</a> from MPlayer's website.</p>
<h2>Step 4 - Installing MPlayer</h2>
<p>Download the <a href="http://www.mplayerhq.hu/design7/dload.html#source">MPlayer Source Code</a> and put it in a folder. For example, "src" in your home directory.</p>
<p>Open a terminal and change to it:</p>
<pre>cd src</pre>
<p>Now we need to configure the package, but passing special commands so it doesn't try and compile for 64-bit</p>
<pre>./configure --extra-cflags=-m32 --extra-ldflags=-m32</pre>
<p>This will take a minute, so grab a beer. Once it's done, check that win32 and xvid are in the supported codecs list. They should be, so type:</p>
<pre>make</pre>
<p>This will compile MPlayer which will take a while, so grab a couple of beers...</p>
<p>Finally, install it:</p>
<pre>sudo make install</pre>
<h2>Step 5 - Testing mencoder</h2>
<p>Test mencoder:</p>
<pre>mencoder -ovc help</pre>
<p>You should get an output like this:</p>
<pre>MEncoder 1.0rc4-4.2.1 (C) 2000-2010 MPlayer Team

Available codecs:
   copy     - frame copy, without re-encoding. Doesn't work with filters.
   frameno  - special audio-only file for 3-pass encoding, see DOCS.
   raw      - uncompressed video. Use fourcc option to set format explicitly.
   nuv      - nuppel video
   lavc     - libavcodec codecs - best quality!
   vfw      - VfW DLLs, read DOCS/HTML/en/encoding-guide.html.
   qtvideo  - QuickTime DLLs, currently only SVQ1/3 are supported.
   xvid     - XviD encoding
   x264     - H.264 encoding</pre>

<p>Make sure xvid and vfw are there.</p>
<h2>Step 6 - Converting video</h2>
<p>Finally you want to use mencoder to convert your video:</p>
<pre>mencoder "input.avi" -oac copy -ovc xvid -xvidencopts pass=1 \
	-o /dev/null &amp;&amp; mencoder "input.avi" -oac copy -ovc xvid \
	-xvidencopts pass=2:bitrate=950 -o "output.avi"</pre>
<p>The way I did it is to have a folder called "conv", so to convert a Simpsons video I did:</p>
<pre>mencoder "The Simpsons S08E01 Treehouse of Horror VII.avi" \
	-oac copy -ovc xvid -xvidencopts pass=1 -o /dev/null &amp;&amp; \
	mencoder "The Simpsons S08E01 Treehouse of Horror VII.avi" -oac copy -ovc xvid \
	-xvidencopts pass=2:bitrate=950 -o "conv/The Simpsons S08E01 Treehouse of Horror VII.avi"</pre>
<h2>Step 7 - Automating</h2>
<p>I wrote a small perl script to automate converting a folder:</p>
<pre>opendir(DIR, '.');

print 'mkdir conv'."\n";

foreach (readdir(DIR)) {
        if (/\.avi$/) {
                $out = "Conv/".$_;
                if (!-e $out) {
                        print 'mencoder "'.$_.'" -oac copy -ovc xvid -xvidencopts pass=1 ';
			print '-o /dev/null &amp;&amp; mencoder "'.$_.'" -oac copy -ovc xvid ';
			print '-xvidencopts pass=2:bitrate=950 -o "conv/'.$_.'"'."\n";
                }
        }
}

closedir(DIR);</pre>
<p>To use it:</p>
<pre>perl conv.pl&gt;conv.sh
sh conv.sh</pre>

<p>Alternatively, use a shell script like this:</p>
<pre>mkdir conv
for i in *.avi; do mencoder "$i" -oac copy -ovc xvid -xvidencopts pass=1 \
	-o /dev/null &amp;&amp; mencoder "input.avi" -oac copy -ovc xvid \
	-xvidencopts pass=2:bitrate=950 -o "conv/$i"; done</pre>
<p>Just paste the above in to a shell and it should work.</p>

<?php
    page_footer();
?>

