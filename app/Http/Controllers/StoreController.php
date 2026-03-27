<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function index(): View
    {
        $products = Product::where('is_active', true)
            ->latest('id')
            ->paginate(12);

        return view('', compact('products'));
    }

    public function show(Product $product, Request $request): View
    {
        $recentlyViewed = $request->session()->get('recently_viewed', []);
        array_unshift($recentlyViewed, $product->id);
        $recentlyViewed = array_values(array_unique($recentlyViewed));
        $request->session()->put('recently_viewed', array_slice($recentlyViewed, 0, 8));

        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('store.show', compact('product', 'relatedProducts'));
    }



    public function dashboard(Request $request): View
    {
        $recentlyViewedIds = $request->session()->get('recently_viewed', []);

        $recentlyViewed = Product::whereIn('id', $recentlyViewedIds)
            ->where('is_active', true)
            ->get()
            ->sortBy(function (Product $product) use ($recentlyViewedIds) {
                return array_search($product->id, $recentlyViewedIds, true);
            });

        $stats = [
            'orders' => 3,
            'saved' => 5,
            'spent' => 1489000,
        ];

        return view('store.dashboard', compact('recentlyViewed', 'stats'));
    }
}
