<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromotionRequest;
use App\Shop\Promotion;
use Illuminate\Http\Request;

class PromotionsController extends Controller
{
    public function store(PromotionRequest $request)
    {
        Promotion::new($request->promotionInfo());
    }

    public function update(Promotion $promotion, PromotionRequest $request)
    {
        $promotion->update($request->promotionInfo()->toArray());
    }

    public function delete(Promotion $promotion)
    {
        $promotion->delete();
    }
}
