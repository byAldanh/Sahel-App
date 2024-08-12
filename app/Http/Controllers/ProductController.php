<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    // The market will add a product (there is a middleware before this step)
    public function store(Request $request)
    {
         // Get the market user 
        $userMarket=auth()->User();

        // Store the product to the specific market 
        $product=new Product();
        $product->product_name= $request->product_name;

        //store the product image
        $product_image=$request->file('product_image');
        //$product_image_filename=time().'_'.$product_image->getClientOriginalName();

        $product_image_filename = $userMarket->id. '_' .$userMarket->name_user. '_product_image_' .time(). "." .$product_image->getClientOriginalExtension() ;
        //dd($product_image_filename);
        $path = 'images/products/';
        $product_image->storeAs('public/'.$path, $product_image_filename);
        $product->product_image=$product_image_filename;

       // dd( $product_image, $product_image_filename);
        // $base64File = $request->input("product_image");
        // if($base64File !==null){
        //     $product_image = base64_decode($base64File);
        //     Storage::disk('public')->put('images/products/' . $request-> . '/Project_filter/thumb' . '.jpg',  $product_image);        }
        /**********/
        $price=($request->price);
        $price_increment=($price)*($price)*0.20;
        $product->price=$price_increment;

        $product->quantity=$request->quantity;

        //store the barcode image 
        $barcode_image=$request->file('barcode_image');
        $barcode_image_filename = $userMarket->id. '_' .$userMarket->name_user. '_barcode_image_' .time(). "." .$barcode_image->getClientOriginalExtension() ;
        $pathB = 'images/barcodes/';
        $product_image->storeAs('public/'.$pathB, $barcode_image_filename);
        $product->barcode_image=$barcode_image_filename;

        $product->category_id=$request->category_id;
        $product->market_id=$userMarket->id;
        $product->save(); // to save the 

        return response()->json([
            'message'=>'Product added successfuly',
            'Market'=>$userMarket->name_user,
            'id'=>$userMarket->id
        ]);//the end of the return statement 
    } //the end of the method 


    // View the products of specific market (based on id) to the customer 
    public function index($market_id)
    {
         // Retrieve the products based on the market_id
         // Correct 
         $products = Product::where('market_id', $market_id)
         ->with('category')
         ->get(); // get all the records of the products of that market
         
        //$products=Product::select('select * from products where')
        //pass the products to the Resource 
        return ProductResource::collection($products);
    } // the end of the method 
    

     // Update the info of a specific product
    public function update(ProductRequest $request, $id)
    {
        // finding the product 
        $product = Product::find($id);

        // to get the market info
        $market=auth()->User(); 

        // if it not found either the record doesn't exist, or the product doesn't belong to the correct market 
        if (!$product || $product->market_id!=$market->id) { 
            return response()->json([
                'message' => 'Product Not Found In The Marke',
            ], 404);
        }

        $product->product_name = $request->product_name; 

        if ($request->hasFile('product_image')) {
            $product_image = $request->file('product_image');
            $product_image_filename = time() . '_' . $product_image->getClientOriginalName();
            $product_image->storeAs('public/images/products',$product_image_filename);
            $product->product_image = $product_image_filename;
        }

        $price = ($request->price);
        $price_increment = ($price) * ($price) * 0.20; // increasing the price of the product 20%
        $product->price = $price_increment;

        $product->quantity = $request->quantity;

        if ($request->hasFile('barcode_image')) {
            $barcode_image = $request->file('barcode_image');
            $barcode_image_filename = time() . '_' . $barcode_image->getClientOriginalName();
            $product_image->storeAs('public/images/barcodes',$barcode_image_filename);
            $product->barcode_image = $barcode_image_filename;
        }

        $product->category_id = $request->category_id;
        $product->save(); // save the updated product 

        return response()->json([
            'message' => 'Product updated successfully',
        ]);
    }// the end of the method 

    // Show a specific product based on market_id and product_id
    public function show($market_id, $product_id)
    {
        $product = Product::where('market_id', $market_id)
            ->where('product_id', $product_id)
            ->with('category')
            ->first();

        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }

        return new ProductResource($product); // to view the information 
    }// he end of the method 


    // Method for deleting a specific product for a market 
    public function destroy($id)
{
        $market=auth()->User(); // to get the market info
        $record = Product::find($id);
        
        if (!$record || $record->market_id!=$market->id) {
            return response()->json([
                'message' => 'Product Not Found In The Market',
            ], 404); // Not Found
        }

        $record->delete();
        
        return response()->json([
            'message' => 'Product Deleted Successfully',
        ]);
}// the end of the method 


    // To filter product of specific market 
    public function productFilter(Request $request,$market_id)
    {
        // Find first the market's products, then look for specific product 
        $products = Product::where('market_id', $market_id)
        ->where('product_name', $request->product_name)
        ->get();

         if ($products->isNotEmpty()) { // if there are products . . . 
              return ProductResource::collection($products); // to view the product information 
         }
          else {
              return response()->json([
            'message' => 'Product Not Found',
             ]);
         }// the end of the else statement 
        }//the end of the method 

  

}//the end of the method 
