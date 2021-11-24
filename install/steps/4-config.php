<?php
defined('MYAAC') or die('Direct access not allowed!');

$clients = array();
foreach($config['clients'] as $client) {
	$client_version = (string)($client / 100);
	if(strpos($client_version, '.') == false)
		$client_version .= '.0';

	$clients[$client] = $client_version;
}

$directory = shell_exec('listdirs(){ set -- */; printf "%s\n" "${@%/}"; } && cd /home/otserv/ && listdirs');
$servers = explode("\n", trim($directory));
$server_list = array();

if ($servers) {
    $blacklist = array(".", "..", "www", ".tmb", ".quarantine");
    foreach ($servers as $server) {
        if (!in_array($server, $blacklist)) {
            $serverStr = str_replace( '/', '', $server);
            $server_list[] = $serverStr;
        }
    }
}

$twig->display('install.config.html.twig', array(
	'clients' => $clients,
	'timezones' => DateTimeZone::listIdentifiers(),
	'locale' => $locale,
	'server_list' => $server_list,
	'session' => $_SESSION,
	'errors' => isset($errors) ? $errors : null,
	'buttons' => next_buttons()
));
?>