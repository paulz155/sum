<?php
ini_set('display_errors', '1');
include 'pdo.php';

class Hint {	
	function gitHint($mes) {
		if(empty($mes)) return '[]';
		$fromBase = Hint::getFromBase($mes);
		if($fromBase != '[]') {
			return $fromBase;
		}
		
		$fromKladr = Hint::getKladr($mes);
		if($fromKladr != '[]') {
			Hint::saveHints($mes, $fromKladr);				
		}
		return $fromKladr;
	}
	
	function saveHints ($mes, $hints) {
		if(empty($mes) || empty($hints)) return;
		$db = new myPdo();
		$db->insert('hint', ['mes' => $mes, 'city' => $hints]);
	}

	function getFromBase($mes) {
		if(empty($mes)) return '[]';
		$db = new myPdo();
		$inBase = $db->search('hint', 'city', ['mes' => $mes]);	
		return $inBase ? : '[]';
	}
	
	function getKladr($mes) {
		if(empty($mes)) return '[]';
		$kladr = json_decode(file_get_contents('http://kladr-api.ru/api.php?contentType=city&limit=6&_=' . time() . '&query=' . urlencode($mes)));
		return json_encode($kladr->result ? : []);
	}
	
	function ErrorPage404()
	{
        header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
    }

}

$controller = new Hint;
$action = get('action');
$mes = get('mes');

if (method_exists($controller, $action)) {
	header('Content-Type: application/json');
	echo $controller->$action($mes);
} else {
	Hint::ErrorPage404();
}

function get($name) {
	return empty($_GET[$name]) ? '' : urldecode(trim($_GET[$name]));
}