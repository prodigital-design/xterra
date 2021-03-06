<?php


namespace App\Occasions;


trait HasActivities
{
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function addRace(ActivityInfo $info): Activity
    {
        return $this->activities()->create($info->toArray());
    }

    public function addActivity(ActivityInfo $info): Activity
    {
        return $this->activities()->create($info->toArray());
    }

    public function listCategories(): array
    {
        return $this->activities->map(fn (Activity $activity) => $activity->category)->values()->all();
    }
}
