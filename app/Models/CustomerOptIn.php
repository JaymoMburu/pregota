<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class CustomerOptIn extends Model
{
    protected $fillable = ['bill_split_id', 'phone_encrypted'];

    public function billSplit()
    {
        return $this->belongsTo(BillSplit::class);
    }

    public function setPhone(string $phone): void
    {
        $this->phone_encrypted = Crypt::encryptString($phone);
    }

    public function getPhone(): string
    {
        return Crypt::decryptString($this->phone_encrypted);
    }
}
