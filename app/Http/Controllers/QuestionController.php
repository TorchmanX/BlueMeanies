<?php

namespace App\Http\Controllers;

use App\Category;
use App\Department;
use App\Http\Controllers\Controller;
use App\Session;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
	const ERROR_INVALID_SESSION = 'INVALID_SESSION';

	private $addressPatterns = [
		'台北', '臺北', '中正區', '大同區', '中山區', '松山區', '大安區', '萬華區', '信義區', '士林區', '北投區', '內湖區',
		'南港區', '文山區', '新北市', '板橋區', '新莊區', '中和區', '永和區', '土城區', '樹林區', '三峽區', '鶯歌區', '三重區', '蘆洲區',
		'五股區', '泰山區', '林口區', '淡水區', '金山區', '八里區', '萬里區', '石門區', '三芝區', '瑞芳區', '汐止區',
		'平溪區', '貢寮區', '雙溪區', '深坑區', '石碇區', '新店區', '坪林區', '烏來區', '桃園', '桃園區', '中壢區', '平鎮區', '八德區',
		'楊梅區', '蘆竹區', '大溪區', '龍潭區', '龜山區', '大園區', '觀音區', '新屋區', '復興區', '台中', '臺中', '中區', '東區', '南區', '西區',
		'北區', '北屯區', '西屯區', '南屯區', '太平區', '大里區', '霧峰區', '烏日區', '豐原區', '后里區', '石岡區', '東勢區', '和平區',
		'新社區', '潭子區', '神岡區', '大雅區', '大肚區', '沙鹿區', '龍井區', '梧棲區', '清水區', '大甲區', '外埔區', '大安區', '台南', '臺南',
		'中西區', '東區', '南區', '北區', '安平區', '安南區', '永康區', '歸仁區', '新化區', '左鎮區', '玉井區', '楠西區', '南化區',
		'仁德區', '關廟區', '龍崎區', '官田區', '麻豆區', '佳里區', '西港區', '七股區', '將軍區', '學甲區', '北門區', '新營區', '後壁區', '白河區', '東山區', '六甲區',
		'下營區', '柳營區', '鹽水區', '善化區', '大內區', '山上區', '新市區', '安定區', '高雄', '楠梓區', '左營區', '鼓山區', '三民區', '鹽埕區', '前金區',
		'新興區', '苓雅區', '前鎮區', '旗津區', '小港區', '鳳山區', '大寮區', '鳥松區', '林園區', '仁武區', '大樹區', '大社區', '岡山區', '路竹區', '橋頭區',
		'梓官區', '彌陀區', '永安區', '燕巢區', '田寮區', '阿蓮區', '茄萣區', '湖內區', '旗山區', '美濃區', '內門區', '杉林區', '甲仙區', '六龜區', '茂林區', '桃源區', '那瑪夏區',
	];

	private $badWordsPatterns = [
		'我操', '操你', '我靠', '靠你的', '去你的', '去你媽', '三小'
	];

	public function init(Request $request){
		\Illuminate\Support\Facades\Session::flush();

		$session = new Session();
		$session->CreateTime = time();
		$session->save();

		$departments = Department::where('Type', 'execution')
			->orderBy('ID', 'ASC')
			->get();
		$retDepartments = [];
		for ($i = 0, $l = count($departments); $i < $l; $i++){
			$retDepartments[] = $departments[$i]->toObject();
		}

		$categories = Category::orderBy('ID', 'ASC')->get();
		$retCategories = [];
		for ($i = 0, $l = count($categories); $i < $l; $i++){
			$retCategories[] = $categories[$i]->toObject();
		}

		return view('index', [
			'sessionId' => $session->id,
			'categories' => $retCategories,
			'departments' => $retDepartments
		]);
	}

	public function answer(Request $request){
		$sessionId = $request->get('id');
		$saidWord = $request->get('saidWord');

		$session = Session::find($sessionId);

		if ($session == null){
			return response()->error(self::ERROR_INVALID_SESSION, 'You need to initialize the session first.');
		}

		$data = $this->analyse($saidWord, $session);

		if (property_exists($data, 'category')) {
			$session->Category = $data->category->id;
			$session->save();
		}

		$department = null;
		if (property_exists($data, 'department')){
			$department = $data->department;
		}

		$category = null;
		if (property_exists($data, 'category')){
			$category = $data->category;
		}

		return response()->success([
			'response' => $data->response,
			'department' => $department,
			'category' => $category
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
			'嘩。。。水呀！我現在找人去，給我淹水的地址'
		],

		'thanks' => [
			'我現在找人去，感謝你的匯報'
		],

		'no-bad-words' => [
			'你再駡髒話我就掛線嚕',
			'要不是我老闆站在我背後我早就駡死你'
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

	private function getResponse($type){
		return $this->responses[$type][array_rand($this->responses[$type])];
	}

	private function getDepartmentByName($name){
		return Department::where('Name', $name)->first();
	}

	private function getCategoryById($id){
		return Category::where('ID', $id)->first();
	}

	private function getCategoryByName($name){
		return Category::where('Name', $name)->first();
	}

	private function getDepartment($type){
		switch ($type){
			case 'flooding':
				return $this->getDepartmentByName('臺北市文山區第二戶政事務所');
		}
	}

	private function trainedRecogniseIncident($saidWord, Session $session){
		$data = file_get_contents('');
		$data = json_decode($data);

		$category = $data->category;
		$department = $data->department;


	}

	private function recogniseIncident($saidWord, Session $session){
		if (mb_strpos($saidWord, '淹水') !== false){
			if (mb_strlen($session->location) == 0) {
				\Illuminate\Support\Facades\Session::put('incident.recognised', 'flooding');

				return (object) [
					'response' => $this->getResponse('flooding-need-address'),
					'category' => $this->getCategoryByName('衛生服務類')->toObject(),
					'department' => $this->getDepartment('flooding')->toObject()
				];
			}else{
				return $this->respond('thanks');
			}
		}

		return null;
	}

	private function recogniseAddress($saidWord, Session $session){
		for ($i = 0, $l = count($this->addressPatterns); $i < $l; $i++){
			$pattern = $this->addressPatterns[$i];
			if (mb_strpos($saidWord, $pattern) !== false){
				$session->Location = $saidWord;
				$session->save();

				return (object) [
					'response' => $this->getResponse('thanks')
				];
			}
		}
	}

	private function recogniseBadWords($saidWord, Session $session){
		if (mb_strlen($saidWord) > 6){
			return null;
		}

		for ($i = 0, $l = count($this->badWordsPatterns); $i < $l; $i++){
			$pattern = $this->badWordsPatterns[$i];

			if (mb_strpos($saidWord, $pattern) !== false) {
				return (object)[
					'response' => $this->getResponse('no-bad-words')
				];
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

		$result = $this->recogniseBadWords($saidWord, $session);

		if ($result != null){
			return $result;
		}


		$result = $this->recogniseIncident($saidWord, $session);

		if ($result != null){
			return $result;
		}

		$result = $this->recogniseAddress($saidWord, $session);
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
