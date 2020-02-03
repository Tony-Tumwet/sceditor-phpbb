<?php

function fix_lang_name($lang)
{
	$name = $lang;

	switch ($lang) {
		case 'cs':
			$name = 'C#';
			break;
		case 'cpp':
			$name = 'C/C++';
			break;
		case 'vbscript':
			$name = 'VB Script';
			break;
		case 'vbnet':
			$name = 'VB .NET';
			break;
		case 'sql':
			$name = 'SQL';
			break;
		case 'css':
			$name = 'CSS';
			break;
		case 'json':
			$name = 'JSON';
			break;
		case 'html':
			$name = 'HTML';
			break;
		case 'asm':
			$name = 'ASM';
			break;
		
	}

	return ucfirst($name);
}

$content = file_get_contents('config.json');
$decode  = json_decode($content);
foreach ($decode as $key => $value) {
	if ($value == 1) {
		$clean_key = substr($key, 0, -3);
		$name = fix_lang_name($clean_key);
		echo "'{$clean_key}': '{$name}',\n";
	}
}
