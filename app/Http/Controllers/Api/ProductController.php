<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController as BaseController;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class ProductController extends BaseController
{
    public function index()
    {
        $products = Product::all();
        // return $this->successResponse($products->toArray(), 'Product Retrive');
        return $this->successResponse(ProductResource::collection($products), 'Product Retrive');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Name and Description Field Required', $validator->errors());
        }

        $product = Product::create($request->all());

        return $this->successResponse(new ProductResource($product), 'Product created successfully');
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Both field is required', $validator->errors());
        }

        $product->update($request->all());

        return $this->successResponse(new ProductResource($product), 'Product Updated successfully');
    }

    public function show($product)
    {
        $product = Product::find($product);

        if (empty($product)) {
            return $this->sendError('Product Not Found');
        } else {
            return $this->successResponse(new ProductResource($product), 'Product find');
        }
        
    }

    public function destroy($product)
    {
        $product = Product::find($product);
        if (empty($product)) {
            return $this->sendError('Product Not Found');
        } else {
            $product->delete();

            return $this->successResponse(new ProductResource($product), 'Product Deleted Successfully');
        }
        
        
    }

}
