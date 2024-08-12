<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Product;
use App\Http\Resources\ProductResource;


class CategoryController extends Controller
{

    

    // Display all catagories.
    public function index()
    {
        $category = Category::all();
       
        return $category;
    }

    // create a new category
    public function create(CategoryRequest $request)
    {
        $category = new Category(); // create an object of the wanted Model
        $category->category_name = $request->category_name;
        $category->save();
        return response()->json([
            'message' => 'Category Added Successfuly'
        ]);
    }

    // Display the specified resource.
    public function show($id)
    {
        if ($id) {
            $searchById = Category::where('id', $id)->first();
            if ($searchById) {
                return response()->json([
                    "message" => $searchById
                ]);
            }
        }

        // if ($name) {
        //     $searchByName = Category::where('category_name', $name)->first();
        //     if ($searchByName) {
        //         return response()->json([
        //             "message" => $searchByName
        //         ]);
        //     }
        // }

        return response()->json([
            "message" => "Category not found"
        ], 404);
    }
    

    public function update(Request $request, $id)
{
    // Find the category by ID
    $category = Category::find($id);

    // If the category is not found, return a 404 response
    if (!$category) {
        return response()->json([
            'message' => 'Category not found'
        ], 404);
    }

    // Validate the request data
    $request->validate([
        'category_name' => 'required|string|max:255',
    ]);

    // Update only the category_name field
    $category->category_name = $request->input('category_name');
    $category->save();

    // Return a successful response
    return response()->json([
        'message' => 'Category updated successfully',
        'category' => $category
    ]);
}


      // Remove the specified resource from storage.
      public function destroy($id)
      {
          $category = Category::find($id);
  
          if ($category) {
              $category->delete();
  
              return response()->json([
                  'message' => 'Category deleted successfully'
              ]);
          }
  
          return response()->json([
              'message' => 'Category not found'
          ], 404);
      }// the end of the method 


      // Filter for specific a category in a market 
      // View products of specific category 
      public function categoryFilter(Request $request, $market_id)
      {
        // Get the record for the wanted category 
        $category = Category::where('category_name', $request->category_name)->first();

        if ($category) { // if there is a category 

            // get the procduct of the specific market of the wanted category 
            $products = Product::where('market_id', $market_id)
                ->where('category_id', $category->id)
                ->get();
    
            if ($products->isNotEmpty()) {
                return ProductResource::collection($products);
            } else {
                return response()->json([
                    'message' => 'Product Not Found',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Category Not Found',
            ]);
        }

      }//the end of the method 


}//the end of the class 
