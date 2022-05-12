<?php
    include('site.php');
    page_header('Public Key', 'pubkey');
?>
<div class="alert alert-danger" role="alert">
    <strong>Note</strong> I no longer sign my email - this is kept for nostalgic reasons.
</div>
<p>To install my key via the CLI, issue this command:</p>
<pre>gpg --recv-keys --keyserver keys.gnupg.net 649AB73C</pre>
<p>You should <b>always</b> verify the key next. To do this, first edit it:</p>
<pre>gpg --edit-key 0x649AB73C</pre>
<p>You will enter a mini CLI. First obtain the fingerprint:</p>
<pre>fpr</pre>
<p>This will provide an output such as this:</p>
<pre>pub   2048R/649AB73C 2010-11-30 Matt Day &lt;[REDACTED]&gt;
 Primary key fingerprint: <strong>1B69 7F7D 74DD FC84 9688  E2C1 AA81 3C67 649A B73C</strong></pre>
<p>You should telephone me, or email me with a challenge (to prove I am me) asking for the above fingerprint before assigning trust to my supposed key. Once you have ascertained that it's definitely me, sign the key:</p>
<pre>sign</pre>
<p>It may ask you to confirm your intentions, do so and quit:</p>
<pre>quit</pre>
<p>You can now be sure that PGP communication between us is secure.</p>
<h2>Other ways to get my key</h2>
<p>You can also <a href="matday.asc">view it in plain text</a>.</p>
<p>Here it is in full:</p>
<pre><?php print implode('', file('matday.asc'));  ?></pre>
<?php
    page_footer();
?>

