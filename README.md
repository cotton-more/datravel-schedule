datravel-schedule
=================

Install
=======
* Clone this repository or dowload it
* Switch to the application folder
* Install Composer:  ```curl -sS https://getcomposer.org/installer | php```
* Run application: ```php console schedule:mid-meeting```

Description
===========
Calculate upcoming meeting and testing days and output result into console. You can save result by appending ```> filename.csv``` at the end of command.
By default timetable builds for six months including current. By adding a number of months you can can change output.
It it possible to pass file name with an option ```-f foo``` or ```--file="foo"``` where ```foo``` is filename to output the result. File will be placed in the folder from which application was called. Be careful, file will be overwritten.
For help run ```php console help schedule:mid-meeting```
