<?php

/**
 * Psychotherapy Note form
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Benjamin Foster <dr@fosterpsych.com>
 * @copyright Copyright (c) 2025 Benjamin Foster <dr@fosterpsych.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
*/

require_once(__DIR__ . '/../../globals.php');
require_once($GLOBALS['srcdir'] . '/api.inc.php');
require_once($GLOBALS['srcdir'] . '/forms.inc.php');
require_once(__DIR__ . '/C_FormPsychotherapy_Note.class.php');

function psychotherapy_note_report($pid, $encounter, $cols, $id)
{
    $controller = new C_FormPsychotherapy_Note();
    echo $controller->report_action($pid, $encounter, $cols, $id);
}
