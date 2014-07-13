<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");

// url prefix
$base = "/";
$json = Array();

if (isset($_REQUEST['id'])) {
	$id = trim($_REQUEST['id']);
	$url = 'https://www.youtube.com/watch?v=$id';
}

if (isset($_REQUEST['nome'])) {
	$nome = trim($_REQUEST['nome']);
	$nome = str_replace(".mp3", "", $nome);
}

if ((!(isset($nome))) && (!(isset($id)))) {
	$json['errori'] = 'Request Error';
	$json = json_encode($json);
	echo isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;
	exit;
}

if (file_exists("$nome.mp3")) {
	$json['link'] = $base . "$nome.mp3";
}
else {
	$descriptorspec = array(
		0 => array(
			"pipe",
			"r"
		) , // stdin
		1 => array(
			"pipe",
			"w"
		) , // stdout
		2 => array(
			"pipe",
			"w"
		) , // stderr
	);
	$cmd = "./youtube-dl --audio-format mp3 --audio-quality 0 -f flv --id https://www.youtube.com/watch?v=$id ";
	$cmd1 = "ffmpeg -b 320000 -y -i '$id.flv' '$id.wav' ";
	$cmd2 = " lame -b 160 '$id.wav' '$id.mp3'";
	$process = proc_open($cmd, $descriptorspec, $pipes);
	$stderr1 = stream_get_contents($pipes[2]);
	fclose($pipes[2]);
	$ret = proc_close($process);
	$process = proc_open($cmd1, $descriptorspec, $pipes);
	$stderr2 = stream_get_contents($pipes[2]);
	fclose($pipes[2]);
	$ret = proc_close($process);
	$process = proc_open($cmd2, $descriptorspec, $pipes);
	$stderr3 = stream_get_contents($pipes[2]);
	fclose($pipes[2]);
	$ret = proc_close($process);
	if ($stderr1 or $stderr2 or $stderr3) {
		$json['errori'] = [$stderr1, $stderr2, $stderr3];
	}

	if (file_exists("$id.mp3")) {
		unlink("$id.wav");
		unlink("$id.flv");
		rename("$id.mp3", "$nome.mp3");
		$json['link'] = $base . "$nome.mp3";
	}
}

$json = json_encode($json);
echo isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;
?>