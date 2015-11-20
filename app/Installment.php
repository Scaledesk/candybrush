<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $table=self::TABLE;
    protected $primaryKey=self::ID;
    protected $fillable=[self::PACKAGE_ID,self::INSTALLMENT_NUMBER,self::INSTALLMENT_AMOUNT];
    public $timestamps=false;

    // define constants
    const TABLE = 'candybrush_packages_installments';
    const ID = 'candybrush_packages_installments_id';
    const PACKAGE_ID='candybrush_packages_installments_packages_id';
    const INSTALLMENT_NUMBER = 'candybrush_packages_installments_installment_number';
    const INSTALLMENT_AMOUNT='candybrush_packages_installments_installment_amount';

    public function package(){
        return $this->belongsTo('App\PackagesModel','candybrush_packages_installments_packages_id');
    }
}
