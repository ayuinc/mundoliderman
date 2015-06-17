<?php

$date = new \DateTime('now');
echo $date->format('Y n d') . "<br>";

echo "The 2 digit representation of current month with leading zero is: " . date("m");
echo "<br />";
echo "The textual representation of current month with leading zero is: " . date("M");
echo "<br />";
echo "The 2 representation digit of current month without leading zero is: " . date("n");
