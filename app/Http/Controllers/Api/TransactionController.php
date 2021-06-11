<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function transfer(Request $request){
        $senderuser=auth()->user();
        $recipientuser=User::find($request->user_id);
        $commission_amount=$request->get('amount')/100;
        $amount=$request->amount;


        if($senderuser->balance['balance']>$request->amount){
            if ($senderuser->id!=$request->user_id ){
                $senderbalance=Balance::find($senderuser->balance['id']);

                if (is_null($recipientuser->balance)){
                    Balance::create([
                        'user_id'=>$request->get('user_id'),
                        'balance'=>$request->get('amount')-$commission_amount
                    ]);
                    $senderbalance->balance=$senderuser->balance['balance']-$amount;
                    $senderbalance->update();
                }else{
                    $recipientuserbalance=Balance::find($recipientuser->balance['id']);
                    $recipientuserbalance->balance=$recipientuser->balance['balance']+$amount-$commission_amount;
                    $recipientuserbalance->update();
                    $senderbalance->balance=$senderuser->balance['balance']-$amount;
                    $senderbalance->update();
                }
                $transaction=Transaction::create([
                    'sender_user_id'=>$senderuser->id,
                    'recipient_user_id'=>$request->get('user_id'),
                    'amount'=>$request->get('amount'),
                    'commission_amount'=>$commission_amount
                ]);

                return response(['message' => 'გადარიცხულია','გადარიცხა'=>$transaction]);
            }else{
                return response(['message' => 'გადარიცხვა შეუძლებელია']);
            }
        }else{
            return response(['message' => 'არასაკმარისი თანხა']);
        }

    }

    public function my_transactions(){
        if($mytransaction=Transaction::where('sender_user_id','like','%'.auth()->user()->id.'%')->get()){
            return response(['თქვენი ტრანზაქციები' => $mytransaction]);
        }else{
            return response(['message' => 'გადარიცხვები არ არის']);
        }
    }



    public function transactions_history(){
        $is_admin=auth()->user()->is_admin;
        if ($is_admin){
            $transaction=Transaction::all();
            return response(['ტრანზაქციები' => $transaction]);
        }else{
            return response(['message' => 'უფლება შეზღუდულია']);
        }
    }
}
