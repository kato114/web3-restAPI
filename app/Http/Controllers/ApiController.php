<?php
namespace App\Http\Controllers;

use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;

class ApiController extends Controller
{
	public $web3 = null;

    public function __construct()
    {
		$this->web3 = new Web3(new HttpProvider(new HttpRequestManager('http://localhost:7545', 30)));
    }

    public function create()
    {
		Global $password;
		Global $caccount;

		$password = request("password") ? request("password") : '12345';

		$this->web3->personal->newAccount($password, function ($err, $account) use (&$newAccount) {
			Global $password;
			Global $caccount;

		    if ($err !== null) {
				echo json_encode(array("error" => 'Create Failed'));
		        return;
		    }

			$caccount = $account;
		});

		$this->web3->personal->unlockAccount($caccount, $password, function ($err, $unlocked) {
			Global $password;
			Global $caccount;

			if ($err !== null) {
				echo json_encode(array("error" => 'Unlock Failed'));
				return;
			}

			if($unlocked == true) {
				echo json_encode(array(
					"walletAddress" => $caccount,
					"password" => $password,
					"createDate" => date("d/m/Y"),
				));	
			} else {

			}
		});
	}

    public function transfer()
    {
		$fromAccount = request("fromAccount");
		$toAccount = request("toAccount");
		$password = request("password");
		$amount = request("amount");

		if( $this->web3->utils->isAddress($fromAccount) == false || 
			$this->web3->utils->isAddress($toAccount) == false || 
			$password == null || $amount == null) {
			echo json_encode(array("error" => 'Parameter Error'));
			return;
		}

		$eth = $this->web3->eth;
	    $eth->sendTransaction([
	        'from' => $fromAccount,
	        'to' => $toAccount,
	        'value' => (int)($amount * pow(10, 18))
	    ], function ($err, $transaction) use ($eth, $fromAccount, $toAccount) {
	        if ($err !== null) {
				echo json_encode(array("error" => 'Transfer Failed'));
	            return;
	        }

			echo json_encode(array(
				"status" => "success",
				"transactionID" => $transaction,
				"createDate" => date("Y-m-d H:i:s"),
			));
	    });
	}

    public function transaction()
    {
		$transactionID = request("hash");

		if( $transactionID == null ) {
			echo json_encode(array("error" => 'Parameter Error'));
			return;
		}

		$eth = $this->web3->eth;
	    $eth->getTransactionByHash($transactionID, function ($err, $transaction) use ($eth, $transactionID) {
	        if ($err !== null) {
				echo json_encode(array("error" => 'Transfer Failed'));
	            return;
	        }

			echo json_encode($transaction);
	    });
	}

	public function trxlist()
	{
		
	}

    public function acclist()
    {
		$eth = $this->web3->eth;
	    $eth->accounts(function ($err, $accounts) use ($eth) {
		    if ($err !== null) {
				echo json_encode(array("error" => 'Invalid Error'));
		        return;
		    }

		    echo json_encode($accounts);
		});
	}
}