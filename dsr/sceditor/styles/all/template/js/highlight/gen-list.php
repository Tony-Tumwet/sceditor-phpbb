<?php

// config
$fix_lang_name = [
    'cs'        => 'C#',
    'cpp'       => 'C/C++',
    'vbscript'  => 'VB Script',
    'vbnet'     => 'VB .NET',
    'sql'       => 'SQL',
    'css'       => 'CSS',
    'json'      => 'JSON',
    'html'      => 'HTML',
    'asm'       => 'ASM',
    'ini'       => 'INI',
];

$most_used_langs = [
    'bash',
	'cs',
	'cpp',
	'java',
	'javascript',
	'php',
	'python',
	'sql',
	'shell',
	'autoit',
	'delphi',
	'vbnet',
	'vbscript',
];



// helpers
function fix_lang_name($lang)
{
    global $fix_lang_name;

	$name = $lang;
	if (isset($fix_lang_name[ $lang ])) {
        $name = $fix_lang_name[ $lang ];
    }

	return ucfirst($name);
}



// se fini
$content = file_get_contents('config.json');
$decode  = json_decode($content);

echo "var languages = {\n";
foreach ($decode as $key => $value) {
    $clean_key = substr($key, 0, -3);
    if ($value == 0 || !in_array($clean_key, $most_used_langs)) {
        continue;
	}

    $name = fix_lang_name($clean_key);
    echo "  '{$clean_key}': '{$name}',\n";
}
echo "};\n\n";

echo "var more = {\n";
foreach ($decode as $key => $value) {
    $clean_key = substr($key, 0, -3);
    if ($value == 0 || in_array($clean_key, $most_used_langs)) {
        continue;
    }

    $name = fix_lang_name($clean_key);
    echo "  '{$clean_key}': '{$name}',\n";
}
echo "};\n\n";


/*
And if I only could
I'd make a deal with God
And I'd get him to swap our places
We're running up that road
We're running up that hill
We're running up that building
Say if I only could

https://www.youtube.com/watch?v=EL2Z-p1pdPM
*/