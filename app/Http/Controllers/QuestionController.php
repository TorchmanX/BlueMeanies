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
		$saidWord = $request->get('saidWord');

		$session = Session::find($sessionId);

		if ($session == null){
			return response()->error(self::ERROR_INVALID_SESSION, 'You need to initialize the session first.');
		}

		$data = $this->analyse($saidWord, $session);

		if (property_exists($data, 'category')) {
			$session->Category = $data->category;
			$session->save();
		}

		return response()->success([
			'response' => $data->response
		]);
	}

	public function init(Request $request){
		\Illuminate\Support\Facades\Session::flush();

		$session = new Session();
		$session->CreateTime = time();
		$session->save();

		return view('index', [
			'sessionId' => $session->id
		]);
	}

	private $responses = [
		'greeting' => [
			'不太好，每天都要接很多電話，很累'
		],
		'greeting-twice' => [
			'快問你的問題呀，不然我要掛線'
		],

		'flooding-need-address' => [
			'嘩嘩嘩，水呀！我現在找人去，給我淹水的地址'
		],

		'thanks' => [
			'我現在找人去，感謝你的匯報'
		],

		'unknown' => [
			'呀，你再講一次吧，我的腦子轉得沒那麼快',
			'我聽不懂',
			'你是在耍我呀？再給你一次機會',
			'講慢一點，我老了'
		],

		'unknown-need-question' => [
			'有什麼問題呢'
		]
	];

	private $incidentType = [
		'flooding'
	];

	private function getResponse($type){
		return $this->responses[$type][array_rand($this->responses[$type])];
	}

	private function recogniseIncident($saidWord, Session $session){
		if (mb_strpos($saidWord, '淹水') !== false){
			if (strlen($session->location) == 0) {
				\Illuminate\Support\Facades\Session::put('incident.recognised', 'flooding');
				return $this->respond('flooding-need-address');
			}else{
				return $this->respond('thanks');
			}
		}

		return null;
	}

	private function analyse($saidWord, Session $session){
		if (mb_strpos($saidWord, '你好嗎') !== false){
			if (\Illuminate\Support\Facades\Session::get('greeting.before')){
				return $this->respond('greeting-twice');
			}
			\Illuminate\Support\Facades\Session::put('greeting.before', true);
			return $this->respond('greeting');
		}

		$result = $this->recogniseIncident($saidWord, $session);

		if ($result != null){
			return $result;
		}

		return $this->respond('unknown');
	}

	private function o($data){
		return (object) $data;
	}

	private function respond($responseType){
		return $this->o([
			'response' => $this->getResponse($responseType)
		]);
	}

}
