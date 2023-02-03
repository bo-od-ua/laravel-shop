<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Basket;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cookie;

class BasketController extends Controller    //
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

    public function checkout(Request $request){
        $profile=  null;
        $profiles= null;

        if(auth()->check()){
            $user= auth()->user();
            $profiles= $user->profiles;
            $profile_id= (int)$request->input('profile_id');

            if($profile_id){
                $profile= $user->profiles()->whereIdAndUserId($profile_id, $user->id)->first();
            }
        }
        return view('basket.checkout', compact('profiles', 'profile'));
    }

    public function add(Request $request, $id){
        $quantity= $request->input('quantity') ?? 1;
        $this->basket->increase($id, $quantity);

        if($request->ajax()){
            $positions= $this->basket->products->count();
            return view('basket.part.basket', compact('positions'));
        }

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

    public function saveOrder(Request $request){
        $this->validate($request, [
            'name'=>  'required|max:255',
            'email'=> 'required|email|max:255',
            'phone'=> 'required|max:255',
            'address'=> 'required|max:255',
        ]);

        $basket= Basket::getOrCreate();
        $user_id= auth()->check() ? auth()->user()->id : null;
        $order= Order::create(
            $request->all()+ ['amount'=> $basket->getAmount(), 'user_id'=> $user_id]
        );

        foreach ($basket->products as $product){
            $order->items()->create([
                'product_id'=> $product->id,
                'name'=>       $product->name,
                'price'=>      $product->price,
                'quontity'=>   $product->pivot->quantity,
                'cost'=>       $product->price* $product->pivot->quantity,
            ]);
        }

        $basket->delete();

        return redirect()
            ->route('basket.success')
            ->with('success', 'Ваш заказ успешно размещен');
    }

    public function success(Request $request){
        if($request->session()->exists('order_id')){
            $order_id= $request->session()->pull('order_id');
            $order= Order::findOrFail($order_id);
//            return redirect()->route('basket.index');
            return view('basket.success', compact('order'));
        }

        return redirect()->route('basket.index');
    }

    public function profile(Request $request){
        if(!$request->ajax()){
            abort(404);
        }
        if(!auth()->check()){
            return response()->json(['error'=> 'Необхадима авторизация'], 404);
        }
        $user= auth()->user();
        $profile_id= (int)$request->input('profile_id');
        if($profile_id){
            $profile= $user->profiles()->whereIdAndUserId($profile_id, $user->id)->first();
            if($profile){
                return response()->json(['profile'=> $profile]);
            }
        }
        return response()->json(['error'=> 'Профиль не найден'], 404);
    }
}
