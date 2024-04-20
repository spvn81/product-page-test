<?php

namespace App\Http\Controllers;

use App\Models\productDiscounts;
use App\Models\productImages;
use App\Models\productPrice;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Storage;


class ProductsController extends Controller
{

    public function createProduct(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'slug' => 'required|string|unique:products,slug',
                'status' => 'required|boolean',
            ]);

            $product = new products();
            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->slug = Str::slug($request->input('slug'));
            $product->status = $request->input('status');
            $product->save();
            return response()->json(['message' => 'Product created successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function createProductDiscount(Request $request)
    {

        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'type' => 'required|in:percent,amount',
                'discount' => 'required|integer|min:0',
            ]);

            $productDiscount = new productDiscounts();
            $productDiscount->product_id = $validatedData['product_id'];
            $productDiscount->type = $validatedData['type'];
            $productDiscount->discount = $validatedData['discount'];

            $productDiscount->save();
            return response()->json(['message' => 'Product discount created successfully'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function createProductPrice(Request $request)
    {

        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'full' => 'required|integer|min:0',
                'discounted' => 'required|integer|min:0',
            ]);

            $productPrice = new productPrice();
            $productPrice->product_id = $validatedData['product_id'];
            $productPrice->full = $validatedData['full'];
            $productPrice->discounted = $validatedData['discounted'];

            $productPrice->save();

            return response()->json(['message' => 'Product price created successfully'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function uploadProductImage(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imagePath = $request->file('image')->store('/pubic/images');

            $productImage = new productImages();
            $productImage->product_id = $validatedData['product_id'];
            $productImage->path = $imagePath;

            $productImage->save();

            return response()->json(['message' => 'Product image uploaded successfully'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function productList(){

        $productsAll = products::get();
        foreach($productsAll as $productsData){
            $product_id = $productsData->id;
            $products = products::where(['id'=>$product_id])->first();
            $products['price'] = productPrice::where(['product_id'=>$product_id])->first();
            $products['discount'] = productDiscounts::where(['product_id'=>$product_id])->first();
            $products['images'] = productImages::where(['product_id'=>$product_id])->get();
            $productList[] = $products;
        }

        return response()->json($productList);

    }



public function product($product_id=''){

    $products = products::where(['id'=>$product_id])->first();
    $products['price'] = productPrice::where(['product_id'=>$product_id])->first();
    $products['discount'] = productDiscounts::where(['product_id'=>$product_id])->first();
    $products['images'] = productImages::where(['product_id'=>$product_id])->get();
    return response()->json($products);

}

}
