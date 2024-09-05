<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        try {
            $products = Product::all();

            return response()->json([
                'status' => 'true',
                'message' => count($products) . " Product(s) fetched!",
                'data' => $products
            ], 200); // Success

        } catch (Exception $e) {
            return response()->json([
                'status' => 'false',
                'message' => 'API failed due to an error!',
                'error' => $e->getMessage()
            ], 500); // Internal server error
        }
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "description" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "false",
                "message" => "Validation error occurred!",
                'error_message' => $validator->errors()
            ], 400); // Bad request
        }

        try {
            $product = Product::create([
                "name" => $request->name,
                "description" => $request->description,
            ]);

            return response()->json([
                "status" => "true",
                "message" => "Product created successfully!",
                "data" => $product
            ], 201); // Created

        } catch (Exception $e) {
            return response()->json([
                "status" => "false",
                "message" => "Something went wrong!",
                'error' => $e->getMessage()
            ], 500); // Internal server error
        }
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'false',
                'message' => "Product not found!",
            ], 404); // Not found
        }

        return response()->json([
            'status' => 'true',
            'message' => "Product found!",
            'data' => $product
        ], 200); // Success
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'false',
                'message' => "Product not found!",
            ], 404); // Not found
        }

        $validator = Validator::make($request->all(), [
            "name" => "required",
            "description" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "false",
                "message" => "Validation error occurred!",
                'error_message' => $validator->errors()
            ], 400); // Bad request
        }

        try {
            $product->name = $request->name;
            $product->description = $request->description;
            $product->save();

            return response()->json([
                "status" => "true",
                "message" => "Product updated successfully!",
                'data' => $product
            ], 200); // Success

        } catch (Exception $e) {
            return response()->json([
                "status" => "false",
                "message" => "Something went wrong!",
                'error' => $e->getMessage()
            ], 500); // Internal server error
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'false',
                'message' => "Product not found!",
            ], 404); // Not found
        }

        try {
            $product->delete();

            return response()->json([
                'status' => 'true',
                'message' => "Product deleted successfully!",
                'data' => $product
            ], 200); // Success

        } catch (Exception $e) {
            return response()->json([
                'status' => 'false',
                'message' => "Something went wrong!",
                'error' => $e->getMessage()
            ], 500); // Internal server error
        }
    }
}
