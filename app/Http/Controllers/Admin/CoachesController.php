<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoachRequest;
use App\People\Coach;
use Illuminate\Http\Request;

class CoachesController extends Controller
{
    public function store(CoachRequest $request)
    {
        Coach::new($request->coachInfo());
    }

    public function update(Coach $coach, CoachRequest $request)
    {
        $coach->updateInfo($request->coachInfo());
    }

    public function delete(Coach $coach)
    {
        $coach->fullDelete();
    }
}
