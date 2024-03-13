<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class ProductController extends Controller
{
    public function index()
    {
        if (!session()->has('token')) {
            return redirect()->route('login')->with('error', 'Verileri görebilmek için giriş yapmalısınız.');
        }

        $token = session('token');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token, 
        ])->get('http://host.docker.internal/api/products');

        $productsResponse = $response->successful() ? $response->json()['products'] : [];

        return view('index', compact('productsResponse'));
    } 


    public function create()
    {
    
        if (!session()->has('token')) {
        return redirect()->route('login')->with('error', 'Ürün eklemek için giriş yapmalısınız.');
        }

        return view('create');
    }

    public function store(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('token'),
        ])->post('http://host.docker.internal/api/products/store', [
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'description' => $request->description,
        ]);

        if ($response->successful()) {
            $successMessage = $response->json()['message'];
            return redirect()->route('index')->with('success', $successMessage);
        }

        $errorMessage = $response->json()['error'];
        return back()->with('error', $errorMessage);
    }
    
    public function edit($id)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('token'),
        ])->get('http://host.docker.internal/api/products/show/' . $id);

        if ($response->successful()) {
            $product = $response->json()['product'];
            return view('edit', compact('product'));
        }
    }

    public function update(Request $request, $id)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('token'),
        ])->post('http://host.docker.internal/api/products/update/' . $id, [
            'product_name' => $request->input('product_name'),
            'product_price' => $request->input('product_price'),
            'description' => $request->input('description'),
        ]);

        if ($response->successful()) {
            $successMessage = $response->json()['message'];
            return redirect()->route('index')->with('success', $successMessage);
        }

        $errorMessage = $response->json()['error'];
        return back()->with('error', $errorMessage);
    }

    public function destroy($id)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('token'),
        ])->delete('http://host.docker.internal/api/products/destroy/'.$id);
     
        if ($response->successful()) {
            $successMessage = $response->json()['message'];
            return redirect()->route('index')->with('success', $successMessage);
        }

        if (!session()->has('token')) {
            return redirect()->route('login')->with('error', 'Ürünü silebilmek için giriş yapmalısınız.');
        }
      
        $errorMessage = $response->json()['error'];
        return back()->with('error', $errorMessage);
    }

    public function show($id)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('token'),
        ])->get('http://host.docker.internal/api/products/show/' . $id);

        if ($response->successful()) {
            $product = $response->json()['product'];
            return view('show', compact('product'));
        }

        if (!session()->has('token')) {
            return redirect()->route('login')->with('error', 'Ürünü görebilmek için giriş yapmalısınız.');
        }

        $errorMessage = $response->json()['error'];
        return back()->with('error', $errorMessage);
    }   
}
