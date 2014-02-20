<?php

namespace Niki\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use DateTime;
use DateInterval;

class Schedule extends Command
{
    private $today;

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
    }

    protected function execute(InputInterface $in, OutputInterface $out)
    {
        $lines = [
            [ 'Month', 'Meeting day', 'Testing day' ]
        ];

        if (!ini_get('date.timezone')) {
            date_default_timezone_set('UTC');
        }

        $today = new DateTime();
        $date = clone $today;

        $this->today = $today;

        $i = (int) $in->getArgument('months');
        if ($i <= 0) {
            throw new \Exception('Number of months should be greater than zero');
        }

        do {
            $line = [
                $date->format('F'),
                $this->getMeetingDay($date),
                $this->getTestingDay($date),
            ];
            $this->moveToNextMonth($date);
            $lines[] = $line;
        } while (--$i);

        foreach ($lines as $line) {
            $out->writeln(implode(';', $line));
        }
    }

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

    protected function getTestingDay(DateTime $date)
    {
        $testingDay = $date->format('t');
        list($day, $month, $year) = explode(' ', $date->format('j m Y'));
        $date->setDate($year, $month, $testingDay);

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
