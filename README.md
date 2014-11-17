datravel-schedule
=================
* PHP version: >=5.3.x
* [Symfony Console Component](https://github.com/symfony/Console)
* [Composer](https://getcomposer.org/)

About
=====
The development team are required to have a mid month meeting in order to discuss the ongoing improvements and new features to the code base. This meeting is planned for the 14th of every month. Testing is also done on a monthly basis and should be done on the last day of the month.

If the 14th falls on a Saturday or Sunday then the mid month meeting should be arranged for the following Monday.

If the testing day falls on a Friday, Saturday or Sunday then testing should be set for the previous Thursday.

The command line script will write a CSV to file outputting the columns 'Month', 'Mid Month Meeting Date' and 'End of Month Testing Date' for the next six months.

Install
=======
* Clone this repository or dowload it
  * `git clone https://github.com/vansanblch/datravel-schedule.git`
  * https://github.com/vansanblch/datravel-schedule/archive/v0.1.zip
* Switch to the application folder
* Install Composer:  `curl -sS https://getcomposer.org/installer | php`
* ... and install all dependancies with `php composer.phar install`
* Run application: `php console schedule:mid-meeting`

Description
===========
Calculate upcoming meeting and testing days and output result into console. You can save result by appending `> filename.csv` at the end of command.
By default timetable builds for six months including current. By adding a number of months you can can change output.
It it possible to pass file name with an option `-f foo` or `--file="foo"` where **foo** is filename to output the result. File will be placed in the folder from which application was called. Be careful, file will be overwritten.
For help run `php console help schedule:mid-meeting`

