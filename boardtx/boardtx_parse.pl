#!/usr/bin/env perl

use strict;
use warnings;
use Text::CSV; # library for reading/writing CSV files
use File::stat;
use Time::Piece;
use DateTime;

my @boardTxFiles = glob("./boardtx/input/*.tx");
my $boardTxFile;
my $is21stCentury = 1;

my $delimiter = ",";
my $use24HourTime = 0;

open(my $boardCSV, '>', "./boardtx/output/boardCSV.csv") or die $!;

# Print Header
print $boardCSV "Release DateTime" . $delimiter . "Release Name" . $delimiter .
    "Release Filename" . $delimiter . "Release URL" . $delimiter . "QuickStats Only Release" .
    $delimiter . "Release Static Link" . $delimiter . "Release Includes JSON" . "\n";

foreach $boardTxFile (@boardTxFiles) {

    my $fileYear = DateTime -> from_epoch(epoch => stat($boardTxFile)->mtime)->year;
    if ($fileYear =~ /^20/) {
        $is21stCentury = 1;
    } elsif ($fileYear =~ /^21/) {
        $is21stCentury = 0;
    }

    open(my $data, '<:encoding(UTF-8)', $boardTxFile) or die "Could not open '$boardTxFile' $!\n";

    while (my $line = <$data>) {
        chomp $line;

        my @lineArray = split(/\t+/, $line);
        my $dateTime;
        my $hourInt;
        my $year;

        # [0] = Year
        # [1] = Month
        # [2] = Day
        # [3] = Release Time (24-hour time, EST timezone)
        # [4] = Report ID
        # [5] = Report Name
        # [6] = Report File Name
        # [7] = Cornell ID
        # [8] = URL
        # [9] = QuickStats Only (not in Cornell)
        # [10] = Dissemination
        # [11] = Static Link
        # [12] = JSON Release Included?

        $lineArray[0] =~ s/"//g; # Release Year
        $lineArray[1] =~ s/"//g; # Release Month
        $lineArray[2] =~ s/"//g; # Release Day
        $lineArray[3] =~ s/"//g; # Release time (e.g., 1500 is 3:00 PM)
        $lineArray[6] =~ s/"//g; # Release File Name
        $lineArray[8] =~ s/"//g; # Release URL
        $lineArray[9] =~ s/"//g; # QuickStats Only [boolean]
        $lineArray[11] =~ s/"//g; # Static Link (AG) [boolean]
        if(scalar @lineArray == 13) {
            $lineArray[12] =~ s/"//g; # JSON Release [boolean]
        }

        # Add the correct century (21st or 22nd) board.tx uses double-digit years...
        if ($is21stCentury != 0) {
            $year = '20'.$lineArray[0];
        } else {
            $year = '21'.$lineArray[0];
        }

        # Convert 24-hour time for hours, if $use24HourTime is true (1)
        if($lineArray[3] ne " " && $lineArray[3] ne "") {
            $hourInt = int($lineArray[3]);
            if($hourInt > 0) {
                $hourInt = $hourInt / 100;
                if($use24HourTime eq 1 && $hourInt > 12) {
                    $hourInt = $hourInt - 12;
                }
            }
        } else {
            $hourInt = 0;
        }

        $dateTime = DateTime->new(
            year  => int($year),
            month => int($lineArray[1]),
            day   => int($lineArray[2]),
            hour  => $hourInt
        );

        print $boardCSV $dateTime->iso8601() . "Z" . $delimiter; # Date
        print $boardCSV $lineArray[5] . $delimiter; # Name
        print $boardCSV $lineArray[6] . $delimiter; # Filename
        print $boardCSV $lineArray[8] . $delimiter; # URL

        # QuickStats Only Release?
        if ($lineArray[9] ne "Y") {
            print $boardCSV "N";
        } else {
            print $boardCSV $lineArray[9];
        }

        print $boardCSV $delimiter;

        # AG Static Link?
        if ($lineArray[11] ne "Y") {
            print $boardCSV "N";
        } else {
            print $boardCSV $lineArray[11];
        }

        print $boardCSV $delimiter;

        # JSON File Included in Release?
        if(scalar @lineArray == 13) {
            if ($lineArray[12] ne "Y") {
                print $boardCSV "N";
            } else {
                print $boardCSV $lineArray[12];
            }
        } else {
            print $boardCSV "N";
        }


        print $boardCSV "\n";
    }

    close($data);
}

close($boardCSV);