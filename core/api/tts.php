<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
require_once dirname(__FILE__) . "/../php/core.inc.php";

if (!jeedom::apiAccess(init('apikey'))) {
	echo 'Clef API non valide, vous n\'etes pas autorisé à effectuer cette action';
	die();
}

$engine = init('engine', 'espeak');
$text = init('text');
if ($text == '') {
	echo __('Aucun text à dire', __FILE__);
	die();
}
$md5 = md5($text);
$filename = '/tmp/' . $md5 . '.wav';
switch ($engine) {
	case 'espeak':
		$voice = init('voice', 'fr+f4');
		shell_exec('sudo espeak -v' . $voice . ' "' . $text . '" -w ' . $filename . ' > /dev/null 2>&1');
		break;
	default:
		echo __('Moteur de voix inconnue : ', __FILE__) . $engine;
		die();
		break;
}
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $md5 . '.wav');
readfile($filename);
shell_exec('sudo rm ' . $filename . ' > /dev/null 2>&1');
?>