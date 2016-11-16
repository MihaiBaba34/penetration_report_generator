PENETRATION REPORT GENERATOR   Version 0.1      11/16/2016
---------------------------------------------------------------------------------------------------------

GENERAL USAGE NOTES
----------------------------------------------------------------------------------------------------------
-Penetration Report Generator supports the following file types:
	Acunetix XML
	Nessus XML
	Retina XML

-It has one HTML and one TXT file as output with unique reports from input XML files

-The reports from the output are sorted by risk from highest to lowest and by alphabet inside one risk group

-Left checkboxes are for selecting items that will be present in final report, those from the right side are for making a issue mandatory to fix

-Reports are divided in Web Application Vulnerabilities (Acunetix XML) and Infrastructure Vulnerabilities (Nessus and Retina XML)

INSTALLING 
----------------------------------------------------------------------------------------------------------
-Please set: Max_input_vars = 5000
 in php.ini to avoid problems with parsing large files

-the application was developed and tested in SCOTCH BOX 2.5 under Vagrant

CONTACT INFORMATION
----------------------------------------------------------------------------------------------------------
GitHub: https://github.com/dorinbujor/penetration_report_generator/
e-mail: dor-in@mail.ru
