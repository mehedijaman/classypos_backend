<?php

namespace ClassyPOS\Models\Accounts\General;

use Illuminate\Database\Eloquent\Model;

class AccountLedger extends Model
{
    public function transaction($transaction)
    {
        $Balance = AccountLedger::where('AccountID', $transaction->AccountID)->orderBy('id', 'DESC')->first();
        $Balance = $Balance->Balance;

        $Transaction = new AccountLedger();
        $Transaction->UserID        = $transaction->UserID;
        $Transaction->AccountID     = $transaction->AccountID;
        $Transaction->Description   = $transaction->Description;
        $Transaction->Debit         = $transaction->Debit;
        $Transaction->Credit        = $transaction->Credit;
        $Transaction->Date          = $transaction->Date;

        if ($transaction->Debit > 0) {
            $Transaction->Balance = $Balance + $transaction->Balance;
        } elseif ($transaction->Credit > 0) {
            $Transaction->Balance = $Balance - $transaction->Balance;
        } elseif ($transaction->Debit > 0 && $transaction->Credit > 0) {
            $Transaction->Balance = $Balance + $transaction->Balance;
            $Transaction->Balance = $Balance - $transaction->Balance;
        } else {
            return response('M y   w h o l e   l i f e   i s   a   L i e !', 200);
        }

        $Transaction->save();

        $Transaction = json_encode($Transaction);

        return $Transaction;
    }
}
