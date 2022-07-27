<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function __construct() {
        $this->middleware('auth:users');

        $this->middleware(function($request, $next) {
            $id = $request->route()->parameter('item');
            if (!is_null($id)) { // null判定
                $itemId = Product::availableItems()->where('product_id', $id)->exists();
                if (!$itemId) { // 同じじゃなかったら404表示
                    abort(404);
                }
            }
            return $next($request);
        });
    }

    public function index() {
        $products = Product::availableItems()->get();

        return view('user.index', compact('products'));
    }

    public function show($id) {
        $product = Product::findOrFail($id);
        $quantity = Stock::Where('product_id', $product->id)
        ->sum(('quantity'));

        if($quantity > 9) {
            $quantity = 9;
        }

        return view('user.show', compact('product', 'quantity'));
    }
}
