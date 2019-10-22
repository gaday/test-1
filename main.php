<?php
include("EmployeeList.php");


if($argc != 2 )
{
    echo "Input file is missing!!!\n";
    exit();
}

$inputFile =  fopen($argv[1], 'r') or die("Unable to open file");
$outputFile=  fopen('output.csv', 'w') or die("Unable to open file");

print "\n" . "Starting ... " . "\n";

$elist = new EmployeeList();
$elist->addEmployeeFromFile($inputFile);

fwrite($outputFile, $elist->getResult());

print "\n" . "End of the Program ... " . "\n" . "Check the output from output.csv file" . "\n";
print "\n\n";

fclose($inputFile);
fclose($outputFile);
?>
