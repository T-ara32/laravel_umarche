<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function($request, $next) {
            $id = $request->route()->parameter('shop');
            if (!is_null($id)) { // null判定
                $shopsOwnerId = Shop::findOrfail($id)->owner->id;
                $shopId = (int)$shopsOwnerId; // キャスト文字列->数値
                $ownerId = Auth::id();
                if ($shopId !== $ownerId) { // 同じじゃなかったら404表示
                    abort(404);
                }
            }
            return $next($request);
        });
    }

    public function index() {
        $ownerId = Auth::id();
        $shops = Shop::where('owner_id', $ownerId)->get();

        return view('owner.shops.index', compact('shops'));
    }

    public function edit($id) {
        dd(Shop::findOrfail($id));
    }

    public function update($id) {

    }
}
