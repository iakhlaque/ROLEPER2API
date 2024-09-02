<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductController extends Controller
{
    /**
     * Instantiate a new ProductController instance.
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('permission:create-product|edit-product|delete-product', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:create-product', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:edit-product', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:delete-product', ['only' => ['destroy']]);
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::all();

            $result = array(
                'status' => 'true',
                'message' => count($products) . " Products(s) fetched!",
                'data' => $products
            );
            $responseCode = 200; //Success

            return response()->json($result, $responseCode);

        } catch (Exception $e) {
            $result = array(
                'status' => 'false',
                'message' => "API failed due to an errors!",
                'error' => $e->getMessage()
            );
            return response()->json($result, 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "description" => "required",
        ]);

        if ($validator->fails()) {
            $result = array(
                "status" => "false",
                "message" => "Validation error occured!",
                'error_message' => $validator->errors()
            );
            return response()->json($result, 400); // Bad request
        }

        $product = Product::create([
            "name" => $request->name,
            "description" => $request->description,
        ]);

        if ($product->id) {
            $result = array(
                "status" => "true",
                "message" => "Product Created!",
                "data" => $product
            );
            $responseCode = 200;
        } else {
            $result = array(
                "status" => "false",
                "message" => "Something went wrong!"
            );
            $responseCode = 400;
        }

        return response()->json($result, $responseCode);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'false',
                'message' => "Product not Found!",
            ], 404);
        }

        $result = array(
            'status' => 'true',
            'message' => "Product Found!",
            'data' => $product
        );
        $responseCode = 200; //Success

        return response()->json($result, $responseCode);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        return view('products.edit', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'false',
                'message' => "Product not Found!",
            ], 404);
        }

        //Validation
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "description" => "required",
        ]);

        if ($validator->fails()) {
            $result = array(
                "status" => "false",
                "message" => "Validation error occured!",
                'error_message' => $validator->errors()
            );
            return response()->json($result, 400); // Bad request
        }

        //Update Code
        $product->name = $request->name;
        $product->description = $request->description;
        $product->save();

        $result = array(
            "status" => "true",
            "message" => "Product updated Successfully!",
            'data' => $product
        );

        return response()->json($result, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'false',
                'message' => "Product not Found!",
            ], 404);
        }

        $product->delete();
        $result = array(
            'status' => 'true',
            'message' => "Product has been deleted Successfully!",
            'data' => $product
        );
        $responseCode = 200; //Success

        return response()->json($result, $responseCode);
    }
}
