<?php
	include('site.php');
	page_header('About Matt', 'about');
	// Very crap and rough way to calculate my age to avoid needing to maintain this...
	function getAge() {
		$date = time();
		$mybday = 412473600;
		$diff = $date - $mybday;
		return (date('Y', $diff) - 1970);
	}
?>
<div style="float: right; text-align: center; margin-left: 1em; font-size: 0.8em !important; border: 1px solid #000; width: 250px; max-width: 50%">
<a href="images/matt"><img style="object-fit: cover; width: 100%; border: 0; height: 188px; text-decoration: none;" src="images/matt-thumb" alt="A picture of Matt" /><br />A picture of me at work</a></div>
<p>My name is Matt Day, I'm a <?php print getAge() ?> year old techie at <a href="https://cloud.google.com/">Google Cloud</a>. I currently lead the UK&amp;I's App and Infra Modernisation Technology Practice.</p>
<p>I love computers, apps and technology. Google is the perfect place for me! My favourite number is 93.</p>
<h2>Brief History</h2>
<ul>
    <li><strong>2002 - 2005</strong> - Graduated from Cardiff University with a BSc in Computer Science</li>
    <li><strong>2005 - 2017</strong> - Cisco
    <ul>
        <li>Joined as a grad, living in Amsterdam for a year</li>
        <li>Ended up in their "cloud" team</li>
    </ul></li>
    <li><strong>2017 - 2018</strong> - Pivotal
    <ul>
        <li>Joined as a Platform Architect, focussed on Cloud Foundry</li>
        <li>Picked up Spring Boot, running live demos at DevOxx etc</li>
    </ul></li>
    <li><strong>2018 - Now</strong> - Google Cloud
    <ul>
        <li>Joined as a Customer Engineer in UK&amp;I Retail</li>
        <li>Moved to the EMEA AppMod specialist team, focussed on Anthos &amp; so on</li>
        <li>Now manage the UK&amp;I specialist CE team for App and Infrastructure Modernisation</li>
    </ul></li>
</ul>

<h2>Misc</h2>
<p>I love to BBQ. Like really love it.</p>

<?php
    page_footer();
?>

