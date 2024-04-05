<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1); // Default page is 1
        $perPage = $request->input('perPage', 10); // Default pagination size is 10

        // Start building the query
        $products = Product::orderBy('id', 'DESC')->paginate($perPage, ['*'], 'page', $page);

        // Get the total count of users
        $totalPackages = $products->total();


        // Return the paginated results along with the total count
        return response()->json([
            'products' => $products,
            'total_products' => $totalPackages
        ]);
    }

    public function show(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            return response()->json([
                'product' => $product
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required',
                'seller' => 'required',
                'in_stock' => 'required',
                'price' => 'required',
                'discount_price' => 'required',
                'img_url' => 'required',
                'quantity' => 'required'
            ]);

            $product = Product::create($request->all());

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->update($request->all());

            return response()->json([
                'message' => 'Product updated successfully',
                'product' => $product
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);

        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'message' => 'Product deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function productsAll()
    {
        $products = Product::where('in_stock', '>', 0)->orderBy('id', 'DESC')->get();
        return response()->json([
            'products' => $products
        ]);
    }
}
