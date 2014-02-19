datravel-schedule
=================

Install
=======
* Clone this repository or dowload it
* Install Composer:  ```curl -sS https://getcomposer.org/installer | php```
* Run application: ```php console schedule:mid-meeting```

Description
===========
Calculate upcoming meeting and testing days and output result into console. You can save result by appending ```> filename.csv``` at the end of command.
By default timetable builds for six months including current. By adding a number of months you can can change output.
