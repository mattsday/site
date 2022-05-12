<?php
    include('site.php');
    page_header('Hackintosh', 'hackintosh');
?>
<div class="alert alert-danger" role="alert">
    <strong>Warning</strong> This information is woefully out of date. I've since switched to Linux and strongly recommend others to do the same
</div>
<p>I've been a Hackintosh user for some time now and thought I'd document my own setup experience and steps to get OS X working on my hardware.</p>
<p style="text-align: center;"><img src="hack/10.10" alt="OS X 10.10 on a Hackintosh" /></p>
<h2>My Hardware Components</h2>
<ul>
<li><strong>Motherboard</strong> - Gigabyte GA-Z77X-UD5H - Works without custom DSDT files, loads of SATA and USB 3 ports, 4 RAM banks for up to 32Gb.</li>
<li><strong>CPU</strong> - Intel i7 3770 @ 3.4Ghz - would have got a 3770K but got a REALLY good deal on the non-K</li>
<li><strong>Graphics Card</strong> - Gigabyte Nvidia 660 Ti</li>
<li><strong>Hard disks</strong> - OCZ Agility 4 SSD + a 3Tb 7200rpm drive running as a fusion drive - good compromise between performance and space. Also have some other kinds of storage for media files - totalling around 6Tb</li>
</ul>

<h2>Software Components</h2>
<ul>
<li><strong><a href="http://sourceforge.net/projects/cloverefiboot/">Clover EFI Bootloader</a></strong> - Native EFI bootloader for OS X, can load kernel extensions (kexts) at boot as well as patch existing ones, so things like audio, networking survive entire OS X updates, upgrades and even new installations.</li>
<li><strong><a href="http://sourceforge.net/projects/hwsensors/files/">FakeSMC.kext</a></strong> - the most essential component of any hackintosh - this emulates the Macintosh System Management Controller and is essential for <em>any</em> Hackintosh. Download the Binaries.dmg file</li>
<li><strong><a href="http://www.hackintoshosx.com/files/file/49-clover-configurator/">Clover Configurator</a></strong> - Clover is a pig to configure sometimes, this makes it (a bit) easier</li>
<li><strong>Various Kernel Extensions (kexts) for my hardware:</strong></li>
<ul>
<li><a href="http://sourceforge.net/projects/osx86drivers/files/Kext/">AppleIntelE1000.kext</a> - Intel Ethernet drivers for those not natively supported by OS X (including the on-board GA-Z77X-UD5H ethernet)</li>
<li><a href="http://www.insanelymac.com/forum/topic/283086-updated-atheros-ar8131325152-driver-for-107108/">AtherosL1cEthernet.kext</a> for the Atheros Ethernet NIC (note: use this (version 1.2.3) and <em>not</em> the AtherosL1Ethernet.kext which has a known memory leak)</li>
<li><a href="https://github.com/toleda/audio_ALCinjection">Realtek ALC Audio Injection</a> - drivers for various ALC audio codecs which are patched on boot (includes ALC898 from the GA-Z77X-UD5H)</li>
</ul>
</ul>

<h2>Creating an OS X Installation USB Stick</h2>
<p>You will need a mac for this and a USB stick with at least 8GB capacity.</p>
<h3>Step 1 - Format a suitable USB Stick</h3>
<p>In disk Utility, find your USB stick and select <strong>Partition</strong> and select <strong>1 Partition</strong> under layout. Name it <strong>Yosemite</strong>. It should look like this:</p>
<p style="text-align: center"><img src="hack/Partition"></p>
<p>Click the <strong>Options...</strong> button and select <strong>GUID Partition table</strong>.</p>
<p>When done click <strong>Apply</strong> and wait a few moments for it to format.</p>

<h3>Step 2 - Copy the OS X Installation Media</h3>
<p>When done with that open a terminal window (Utilities/Terminal) and paste the following command:</p>
<code>
sudo /Applications/Install\ OS\ X\ Yosemite.app/Contents/Resources/createinstallmedia --volume /Volumes/Yosemite --applicationpath /Applications/Install\ OS\ X\ Yosemite.app --no interaction
</code>
<p>This may take a while to complete, so be patient!</p>

<h3>Step 3 - Install Clover</h3>
<p>Now you need to install Clover on to the USB stick. Run the installer and click <strong>Change Install Location</strong> and select your OS X Installation USB stick (now called "Install OS X Yosemite")</p>
<p style="text-align:center"><img src="hack/Clover-Change-Location"></p>
<p style="text-align:center"><img src="hack/Clover-USB-Select"></p>
<p>Then click <strong>Customize</strong></p>
<p style="text-align:center"><img src="hack/Clover-Customize"></p>

<p>Select the following options for a pure EFI system (i.e. GA-Z77X-UD5H):</p>
<ul>
<li>Install for UEFI booting only</li><li>Install Clover in the ESP</li><li>Drivers64UEFI:</li><ul><li>EmuVariableUefi-64</li><li>OsxAptioFixDriv-64</li><li>PartitionDxe-64</li></ul></ul>
<p style="text-align:center"><img src="hack/Clover-Customize-options"></p>
<p style="text-align:center"><img src="hack/Clover-Customize-options-2"></p>
<p>Click <strong>Install</strong> and wait for it to complete</p>

<h3>Step 3 - Configuring Clover and kexts</h3>
<p>Once installed Clover will create a partition on your USB stick. This will be auto mounted as <strong>EFI</strong></p>
<p>Navigate to /Volumes/EFI/EFI/CLOVER/ and open the <strong>kexts</strong> folder. If it does not already exist, create a subfolder called <strong>10.10</strong> and place any kexts you need (including FakeSMC.kext). Clover will automatically add these at startup.</p>
<p>Next use clover configurator to edit the config.plist file in /Volumes/EFI/EFI/CLOVER. Configuration varies from system to system, but for mine I used this <a href="hack/config-install.plist">config.plist</a></p>

<p>Clover should create a system definition for you, so there's no need to worry about this.</p>

<h2>Installing OS X</h2>
<p>Once Clover is on your USB key you're ready to install OS X on your hackintosh.</p>
<h3>Booting</h3>
<p>With the USB key inserted, reboot your Hackintosh...TBC</p>
<h3>Setting up a Fusion Drive</h3>

<h2>Post-installation configuration</h2>
<p>

</ul>
<?php
    page_footer();
?>

