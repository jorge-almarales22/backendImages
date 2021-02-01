<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{

    public function index(Request $request)
    {
        $products = Product::orderBy('id', 'DESC')->paginate(2);
        // return $products;
        return [
            'pagination' => [
                'total'         => $products->total(),
                'current_page'  => $products->currentPage(),
                'per_page'      => $products->perPage(),
                'last_page'     => $products->lastPage(),
                'from'          => $products->firstItem(),
                'to'            => $products->lastItem(),
            ],
            'products' => $products
        ];
    }

   
    public function store(Request $request)
    {
     
        $credentials = $request->only('name', 'description', 'image');

        $validator = Validator::make($credentials, [
            'name' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Los campos no son correctos',
                'error' => $validator->errors()
            ],422);
        }
        $exploded = explode(',', $request->image);
        $decoded = base64_decode($exploded[1]);
        if(str_contains($exploded[0], 'jpg')){
            $extension = 'jpg';
        }else{
            $extension = 'png';
        }
        $filename = Str::random().'.'.$extension;
        $path = public_path().'/'.$filename;
        file_put_contents($path, $decoded);
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->image = $filename;
        $product->save();
        return $product;
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $exploded = explode(',', $request->image);
        $decoded = base64_decode($exploded[1]);
        if(str_contains($exploded[0], 'jpg')){
            $extension = 'jpg';
        }else{
            $extension = 'png';
        }
        $filename = Str::random().'.'.$extension;
        $path = public_path().'/'.$filename;
        file_put_contents($path, $decoded);
        unlink(public_path().'/'.$product->image);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->image = $filename;
        $product->save();
        return $product;
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        unlink(public_path().'/'.$product->image);
        $product->delete();
    }
}
