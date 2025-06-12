<?php

namespace App\Services\Banking;

use App\Models\Banking\BankAccount;

class BankAccountStoreService
{
    public function store($data)
    {
        $bank_account = new BankAccount();

        $bank_account->owner_name = $data['owner_name'];
        $bank_account->account_type_id = $data['account_type_id'];
        $bank_account->account_number = $data['account_number'];
        $bank_account->bank_name = $data['bank_name'];
        $bank_account->swift = $data['swift'];

        $bank_account->save();

        return $bank_account;
    }
}
