# How to user #

#note: There must be:
#	3 .php files: Employee.php, EmployeeList.php, main.php
#	1 .csv file: input.csv

1. how to run: php main.php input.csv
2. output: cat output.csv
3. restricted days: Employee.php file has array of restricted days private field.
   Sample: 'x-10-29' : 'y-m-d' format. x means any year(month, day)
   Example: If you want to restrict the second day of every month, add 'x-x-2' to the array
