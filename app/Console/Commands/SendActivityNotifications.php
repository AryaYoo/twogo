<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TripActivity;
use Carbon\Carbon;
use App\Notifications\ActivityStartedNotification;
use App\Notifications\ActivityEndedNotification;

class SendActivityNotifications extends Command
{
    protected $signature = 'send:activity-notifications';

    protected $description = 'Send notifications for activity start and end times.';

    public function handle()
    {
        $now = Carbon::now();

        // Start notifications
        $activitiesToStart = TripActivity::whereNull('notified_start')
            ->orWhere('notified_start', false)
            ->whereNotNull('start_time')
            ->whereHas('day')
            ->get();

        foreach ($activitiesToStart as $activity) {
            $dt = Carbon::parse($activity->day->date->toDateString() . ' ' . $activity->start_time);
            if ($dt->lte($now) && !$activity->notified_start) {
                $user = $activity->day->trip->creator;
                if ($user) {
                    $user->notify(new ActivityStartedNotification($activity));
                }
                $activity->update(['notified_start' => true]);
            }
        }

        // End notifications
        $activitiesToEnd = TripActivity::whereNull('notified_end')
            ->orWhere('notified_end', false)
            ->whereNotNull('end_time')
            ->whereHas('day')
            ->get();

        foreach ($activitiesToEnd as $activity) {
            $dt = Carbon::parse($activity->day->date->toDateString() . ' ' . $activity->end_time);
            if ($dt->lte($now) && !$activity->notified_end) {
                $user = $activity->day->trip->creator;
                if ($user) {
                    $user->notify(new ActivityEndedNotification($activity));
                }
                $activity->update(['notified_end' => true]);
            }
        }

        $this->info('Activity notification check complete.');
        return 0;
    }
}
