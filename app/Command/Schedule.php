<?php

namespace Niki\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Schedule extends Command
{
    protected function configure()
    {
        $this->setName('schedule:mid-meeting');
        $this->setDescription('Generate timetable for meeting and testing days');
    }

    protected function execute(InputInterface $in, OutputInterface $out)
    {
        $lines = [];
        // N 1 for Monday, 7 for Sunday
        // t - number of days in the given month

        if (!ini_get('date.timezone')) {
            date_default_timezone_set('UTC');
        }
        // $out->writeln(date_default_timezone_get());
        // die();

        $date = new \DateTime('2000-01-01');

        // get current day of the month and a month number
        list($today, $month) = explode(' ', $date->format('j n'));

        $oneMonthInterval = new \DateInterval('P1M');
        $i = 6;
        do {
            $date->add($oneMonthInterval);
        } while (--$i);
    }
}
