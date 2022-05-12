#!/usr/bin/perl
# Opens your iTunes Music Library.xml, pulls out playlists to m3u files and
# does other wonderfully amazing things!
# 
# See http://matt.fragilegeek.com/androidsync for configuration information
#
# Copyright 2011 Matt Day
#
# Copying and distribution of this file, with or without modification,
# are permitted in any medium without royalty provided the copyright
# notice and this notice are preserved.  This file is offered as-is,
# without any warranty.


#use common::sense;
use Math::Round;
use File::Copy;

# Base iTunes music directory
our $base_dir = $ENV{'HOME'}.'/Music/iTunes';

our $config_file = $ENV{'HOME'}."/.asyncrc";

our $db_dir = $ENV{'HOME'}.'/.async';

our $itunes_xml = 'iTunes Music Library.xml';

# Some defaults
our $info = 1;
our $debug = 0;
our $verbose = 0;
our $no_copy = 0;
our $no_delete = 0;

sub info;
sub debug;
sub verbose;

# Don't do album artwork by default:
our $album_artwork = 0;

if ($ARGV[0] ne '') {
	$config_file = $ARGV[0];
	info "Using config file: ".$config_file."\n";
}

my $pc = 0;
our $db_file;

our $destination = '/Volumes/SD CARD/Music';
our @sync_playlists;
open(CONFIG, $config_file);
foreach(<CONFIG>) {
	if ((!/^#/) and (/=/)) {
		chomp;
		my ($key, $value) = split(/=/);
		$base_dir = $value if ($key eq 'itunes');
		$itunes_xml = $value if ($key eq 'itunes_xml');
		$destination = $value if ($key eq 'dest');
		@sync_playlists[$pc++] = $value if ($key eq 'playlist');
		$verbose = $value if ($key eq 'verbose');
		$debug = $value if ($key eq 'debug');
		$info = $value if ($key eq 'info');
		$no_copy = $value if ($key eq 'no_copy');
		$db_dir = $value if ($key eq 'db_dir');
		$no_delete = $value if ($key eq 'no_delete');
		$db_file = $value if ($key eq 'db_file');
	}
}
close(CONFIG);

if ($db_file eq '') {
	$db_file = $destination;
	$db_file =~ s/\///g;
	$db_file =~ s/ //g;
}
our $mod_database_file = $db_dir.'/'.$db_file;

debug "Using db file: ".$mod_database_file."\n";

verbose "Reading database file: ".$mod_database_file."\n";

our %mod_database;

if (-e $mod_database_file) {
	open(DB, $mod_database_file);
	foreach (<DB>) {
		chomp;
		my ($file, $value) = split(/\t/);
		$mod_database{$file} = $value;
	}
	close(DB);
}

# iTunes Music Library
our $itunes_xml = $base_dir.'/'.$itunes_xml;

###### End config
our $tracks = 0;
our $added = 0;
our $removed = 0;
our $modified = 0;

# Load library

# I looked on CPAN and found nothing useful, so resorted to this kludge >:D
open(LIBRARY, $itunes_xml);

our $context = '';
our %playlists;
our $playlist_count = 0;
our $skip = 1;
our $active_key = 0;
our %names;
our %artist;
our %time;
our %location;
our %existing;
our %modified;

# Scan Destination
info "Scanning current library at '".$destination."'\n";
scan_dir($destination);

# Remove playlists, we're gonna replace 'em all
opendir(DIR, $destination);
foreach(readdir(DIR)) {
	if (/\.m3u$/) {
		unlink $destination.'/'.$_ if ($no_delete != 1);
	}
}

info "Done - $tracks items found\n";

closedir(DIR);

info "Scanning iTunes library\n";
foreach (<LIBRARY>) {
	if ((/<key>(.*?)<\/key>$/) and ($playlist_count == 0)) {
		$active_key = $1;
	}
	elsif (/<key>Name<\/key><string>(.*?)<\/string>/) {
		$names{$active_key} = $1;
	}
	elsif (/<key>Artist<\/key><string>(.*?)<\/string>/) {
		$artist{$active_key} = $1;
	}
	elsif (/<key>Total Time<\/key><integer>(.*?)<\/integer>/) {
		$time{$active_key} = $1;
	}
	elsif (/<key>Location<\/key><string>(.*?)<\/string>/) {
		$location{$active_key} = $1;
	}
	elsif (/<key>Date Modified<\/key><date>(.*?)<\/date>/) {
		my $mod_date = $1;
		$mod_date =~ s/-//g;
		$mod_date =~ s/T//g;
		$mod_date =~ s/Z//g;
		$mod_date =~ s/://g;
		$modified{$active_key} = $mod_date;
	}
	if ($playlist_count == 0) {
		if (/<key>Playlists<\/key>/) {
			info "Done\nScanning playlists...\n";
			$playlist_count = 1;
		}
	}
	else {
		if (/<key>Name<\/key><string>(.*?)<\/string>/) {
			my $pl = $1;
			$skip = 1;
			debug "Found playlist '".$pl."'\n";
			foreach my $desirable (@sync_playlists) {
				if ($desirable =~ /^$pl$/i) {
					$context = $pl;
					$context =~ tr/[A-Z]/[a-z]/;
					$playlist_count++;
					$playlists{$context} = $pl;
					debug "Added playlist ".$context."\n";
					$skip = 0;
				}
			}
		}
		elsif ($skip == 0) {
			if (/<key>Track ID<\/key><integer>(.*?)<\/integer>/) {
				$playlists{$context} .= ','.$1;
			}
		}
	}
}
close(LIBRARY);
foreach my $pl (@sync_playlists) {
	my $pll = $pl;
	$pll =~ tr/[A-Z]/[a-z]/;
	if ($playlists{$pll} eq '') {
		info 'Warning: Playlist "'.$pl.'" not found!'."\n";
	}
}

info "Done\nCopying files...\n";
foreach my $key (keys %playlists) {
	my @list = split(',', $playlists{$key});
	open(PLAYLIST, '>'.$destination.'/'.$list[0].'.m3u');
	for (my $i = 1; $i <= $#list; $i++) {
		my $index = $list[$i];
		$location{$index} =~ s/%20/ /g;
		$location{$index} =~ s/&#38;/&/g;
		$location{$index} =~ s/%23/#/g;
		$location{$index} =~ s/%5b/[/g;
		$location{$index} =~ s/%5d/]/g;
		$location{$index} =~ s/file:\/\/localhost//;
		my $newloc = $location{$index};
		$newloc =~ s/$base_dir//i;

		$newloc =~ s/\/iTunes Media\/(.*?)\//$destination\//i;
		$newloc =~ tr/[A-Z]/[a-z]/;
		$mod_database{$index} = $modified{$index} if ($mod_database{$index} == 0);
		
		if ($existing{$newloc} != 1) {
			my $newdir = '';
			my @parts = split('/', $newloc);
			for (my $j = 0; $j < $#parts; $j++) {
				$newdir .= $parts[$j].'/';
				if (!-d $newdir) {
					verbose "Making directory: \"".$newdir."\"\n";
					mkdir $newdir or die "Failed to create directory ".$newdir;
				}
			}
			if (!-e $newloc) {
				verbose "Copying ".$artist{$index}.' - '.$names{$index}."\n";
				copy($location{$index},$newloc) or die "Failed to copy file: ".$newloc if ($no_copy != 1);
				$added++;
			}
		}
		elsif ($modified{$index} > $mod_database{$index}) {
			verbose "File changed, updating file: ".$newloc."\n";
			copy($location{$index},$newloc) or die "Failed to copy file: ".$newloc if ($no_copy != 1);
			$mod_database{$index} = $modified{$index};
			$modified++;
		}

		$existing{$newloc} = 2;

		my $destkey = $newloc;

		my $time = $time{$index}."\n";
		if ($time > 0) {
			$time = round($time{$index} / 1000);
		}
		print PLAYLIST "#EXTINF:".$time.",".$names{$index}." - ".$artist{$index}."\n";
		$newloc =~ s/$destination\///i;
		print PLAYLIST $newloc."\n";
	}
	close(PLAYLIST);
}

info "Done - $added added, $modified changed\n";

verbose "Updating local database ".$mod_database_file."\n";
open(DB, ">".$mod_database_file);
foreach my $key (sort { $a <=> $b } keys %modified) {
	print DB $key."\t".$modified{$key}."\n";
}
close(DB);
verbose "Done\n";
verbose "Checking for orphaned files...\n";

foreach my $key (keys %existing) {
	if ($existing{$key} == 1) {
		verbose "Removing ".$key."\n";
		$removed--;
		unlink($key) if ($no_delete != 1);
	}
}

verbose "Done\n";
verbose "Cleaning up directories...\n";
# Run three times as a hack to avoid too much trouble :S
clean_up($destination);
clean_up($destination);
clean_up($destination);
verbose "Done\n";


info "Sync complete!\n";
info "$modified changed, $added added, $removed removed, ".($tracks+$added-$removed)." total\n";


sub clean_up {
	my $dir = shift;
	opendir(DIR, $dir);
	my $files = 0;
	my @files = readdir(DIR);
	closedir(DIR);
	foreach (@files) {
		my $item = $dir.'/'.$_;
		if (!/^\.\.?$/) {
			$files++;
			if(-d $item) {
				clean_up($item);
			}
			elsif (/^\./) {
				verbose "Hidden file found: ".$item." (removed)\n";
				unlink($item) if ($no_delete != 1);
				$files--;
			}
		}
	}
	if (($files == 0) and ($dir !~ /^$destination\/?$/)) {
		verbose "Empty directory ".$dir."... Removing\n";
		rmdir($dir);
	}
}


sub scan_dir {
	my $dir = shift;
	opendir(SCAN, $dir) or die "Unable to open directory at ".$dir;
	my @files = readdir(SCAN);
	closedir(SCAN);
	foreach (@files) {
		if (!/^\.\.?$/) {
			if (-d $dir.'/'.$_) {
				scan_dir($dir.'/'.$_);
			}
			elsif (/\.(m4a|mp3|m4v|mp4|aac)/i) {
				my $key = $dir.'/'.$_;
				$key =~ tr/[A-Z]/[a-z]/;
				$existing{$key} = 1;
				$tracks++;
			}
		}
	}
}

sub debug {
	my $output = shift;
	print $output if ($debug == 1);
}

sub info {
	my $output = shift;
	print $output if ($info == 1);
}

sub verbose {
	my $output = shift;
	print $output if ($verbose == 1);
}

