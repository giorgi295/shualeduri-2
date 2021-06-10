<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\History;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function transfer(Request $request){
        $senderuser=auth()->user();
        $recipientuser=User::find($request->user_id);
        if($senderuser->balance['balance']>$request->amount){
            if(is_null( $user->balance)){
                $fillbalance=Balance::create([
                    'user_id'=>$request->get('user_id'),
                    'balance'=>$request->get('balance')
                ]);
                $fillhistory=History::create([
                    'user_id'=>$request->get('user_id'),
                    'amount_history'=>$request->get('balance')
                ]);
            }else{
                $fillbalance=Balance::find($balance['0']['id']);
                $fillbalance->user_id=$request->get('user_id');
                $fillbalance->balance=$balance['0']['balance']+$request->get('balance');
                $fillbalance->update();
                $fillhistory=History::create([
                    'user_id'=>$request->get('user_id'),
                    'amount_history'=>$request->get('balance')
                ]);
            }
            return response(['fillbalance'=>$fillbalance, 'fillhistory'=>$fillhistory]);

        }else{
            return response(['message' => 'არ გაქვთ საკმარისი თანხა']);
        }

    }


    public function my_transactions(){

    }



    public function transactions_history(){

    }
}
