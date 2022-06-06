<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Accounts;

use ClassyPOS\Models\Accounts\General\AccountLedger;
use ClassyPOS\Models\Accounts\General\Accounts;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Account = new Accounts();
        $Account->Name          = $request->Name;
        $Account->Type          = $request->Type;
        $Account->Description   = $request->Description;
        $Account->Status        = $request->Status;
        $Account->save();

        $AccountID = $Account->id;

        $AccountLedger = new AccountLedger();
        $AccountLedger->UserID      = $request->UserID;
        $AccountLedger->AccountID   = $AccountID;
        $AccountLedger->Description = $request->Description;
        $AccountLedger->Debit       = $request->Debit;
        $AccountLedger->Credit      = $request->Credit;
        $AccountLedger->Balance     = $request->Balance;
        $AccountLedger->Date        = $request->Date;

        $AccountLedger->save();

        return response()->json($Account, 201);
    }

    public function accountTransaction(Request $request)
    {
        $transaction = (array) $request;
        $Transaction = new AccountLedger();
        $Transaction->transaction($transaction);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
