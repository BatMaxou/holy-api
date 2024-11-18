<?php

namespace App\Scheduler;

use App\Messenger\Message\WeekScrapMessage;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('weekScrap')]
final class WeekScrapSchedule implements ScheduleProviderInterface
{
    public function getSchedule(): Schedule
    {
        return (new Schedule())->with(RecurringMessage::cron('@weekly', new WeekScrapMessage()));
    }
}
