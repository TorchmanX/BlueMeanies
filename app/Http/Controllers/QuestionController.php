<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Session;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
	const ERROR_INVALID_SESSION = 'INVALID_SESSION';

	public function answer(Request $request){
		$sessionId = $request->get('id');
		$question = $request->get('question');

		$data = $this->analyse($question);

		$session = Session::find($sessionId);

		if ($session == null){
			return response()->error(self::ERROR_INVALID_SESSION, 'You need to initialize the session first.');
		}

		$session->Category = $data->category;
		$session->save();

		return response()->success([

		]);
	}

	public function init(Request $request){
		$session = new Session();
		$session->CreateTime = time();
		$session->save();

		return view('index');
	}

	private function analyse($question){
		return (object) [
			'category' => '0'
		];
	}

}
