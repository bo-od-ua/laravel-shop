<?php

namespace App\Http\Controllers;

use App\Basket;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cookie;

class BasketController extends Controller
{
    private $basket;

    public function __construct()
    {
        $this->basket= Basket::getOrCreate();
    }

    public function index(){
        $products= $this->basket->products;

        return view('basket.index', compact('products'));
    }

    public function checkout(){
        return view('basket.checkout');
    }

    public function add(Request $request, $id){
        $quantity= $request->input('quantity') ?? 1;
        $this->basket->increase($id, $quantity);

        return back();
    }

    public function plus($id){
        $this->basket->increase($id);

        return redirect()->route('basket.index');
    }

    public function minus($id){
        $this->basket->decrease($id);

        return redirect()->route('basket.index');
    }

    public function remove($id){
        $this->basket->remove($id);

        return redirect()->route('basket.index');
    }

    public function clear(){
        $this->basket->delete();

        return redirect()->route('basket.index');
    }
}
