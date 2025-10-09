<?php

/**
 * Psychotherapy Progress Note form
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Benjamin Foster <dr@fosterpsych.com>
 * @copyright Copyright (c) 2025 Benjamin Foster <dr@fosterpsych.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 *
 *
*/

use OpenEMR\Common\Csrf\CsrfUtils;

require_once("C_FormPsychotherapy_Progress_Note.class.php");

// Verify CSRF token
if (!CsrfUtils::verifyCsrfToken($_POST["csrf_token_form"] ?? "")) {
    CsrfUtils::csrfNotVerified();
}

// Save the form
$controller = new C_FormPsychotherapy_Progress_Note();
$controller->save_action();
