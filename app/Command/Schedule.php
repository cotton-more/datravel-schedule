<?php

namespace Niki\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

use DateTime;
use DateInterval;

class Schedule extends Command
{
    protected function configure()
    {
        $this->setName('schedule:mid-meeting');
        $this->setDescription('Generate timetable for meeting and testing days');
        $this->addArgument(
            'months',
            InputArgument::OPTIONAL,
            'Months to display',
            6
        );
        $this->addOption(
            'file',
            'f',
            InputOption::VALUE_OPTIONAL,
            'Output file'
        );
    }

    protected function execute(InputInterface $in, OutputInterface $out)
    {
        $lines = [
            [ 'Month', 'Meeting day', 'Testing day' ]
        ];

        // set timezone
        if (!ini_get('date.timezone')) {
            date_default_timezone_set('UTC');
        }

        // start from today
        $date = new DateTime();

        $months = (int) $in->getArgument('months');
        if ($months <= 0) {
            throw new \Exception('Number of months should be greater than zero');
        }

        // fill a result array
        do {
            $line = [
                $date->format('F'),
                $this->getMeetingDay($date),
                $this->getTestingDay($date),
            ];
            $this->moveToNextMonth($date);
            $lines[] = $line;
        } while (--$months);

        // if filename was set replace output with file resource
        if ($file = $in->getOption('file')) {
            $basaname = basename($file);
            $fh = fopen('./'.$basaname, 'w');
            if ($fh) {
                $out = new StreamOutput($fh);
            }
        }

        // output result
        foreach ($lines as $line) {
            $out->writeln(implode(';', $line));
        }
    }

    /**
     * getMeetingDay
     * Get meeting day in the date
     *
     * @param \DateTime $date
     * @return void
     */
    protected function getMeetingDay(DateTime $date)
    {
        $meetingDay = 14;
        list($day, $month, $year) = explode(' ', $date->format('j n Y'));

        $result = null;

        if ($day <= $meetingDay) {
            $date->setDate($year, $month, $meetingDay);
            $weekday = (int) $date->format('N');
            // if meeting day is Sat or Sun
            // select following Mon
            if ($weekday > 5) {
                $offset = 8 - $weekday;
                $date->modify('+'.$offset.' days');
            }

            $result = $date->format('j, D');
        }

        return $result;
    }

    /**
     * getTestingDay
     * Get testing day in the date. Testing date should be the last
     * day of the month or the last Thursday
     *
     * @param \DateTime $date
     * @return void
     */
    protected function getTestingDay(DateTime $date)
    {
        $testingDay = $date->format('t');
        list($day, $month, $year) = explode(' ', $date->format('j m Y'));
        $date->setDate($year, $month, $testingDay);

        // if testing
        $weekday = $date->format('N');
        switch ($weekday) {
            case 5:
                $date->modify('-1 day');
                break;
            case 6:
                $date->modify('-2 day');
                break;
            case 7:
                $date->modify('-3 day');
                break;
            default:
                break;
        }

        return $date->format('j, D');
    }

    /**
     * moveToNextMonth
     * Move the passed date to the first day of the next month
     *
     * @param \DateTime $date
     * @return void
     */
    protected function moveToNextMonth(DateTime $date)
    {
        static $oneMonthInterval = null;
        if (null === $oneMonthInterval) {
            $oneMonthInterval = new DateInterval('P1M');
        }
        $date->add($oneMonthInterval);
        list($month, $year) = explode(' ', $date->format('n Y'));
        $date->setDate($year, $month, 1);
    }
}
