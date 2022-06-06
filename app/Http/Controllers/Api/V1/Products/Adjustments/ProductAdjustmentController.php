<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Products\Adjustments;

use ClassyPOS\Models\Products\Adjustments\ProductAdjustments;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class ProductAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Adjustments = ProductAdjustments::paginate(15);

        return response()->json($Adjustments, 200);
    }

    public function listTrash()
    {
        // view only trashed items
        return ProductAdjustments::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($AdjustmentID)
    {
        // Recover soft deleted items back to list
        ProductAdjustments::withTrashed()->find($AdjustmentID)->restore();
    }

    public function clearTrash($AdjustmentID)
    {
        // Permanently Delete
        ProductAdjustments::withTrashed()->find($AdjustmentID)->forceDelete();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Adjustment = ProductAdjustments::create($request->all());

        return response()->json($Adjustment, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ProductAdjustments $AdjustmentID)
    {
        return response()->json($AdjustmentID, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductAdjustments $AdjustmentID)
    {
        $AdjustmentID->update($request->all());

        return response()->json($AdjustmentID, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductAdjustments $AdjustmentID)
    {
        $AdjustmentID->delete();

        return response()->json(null, 204);
    }
}
