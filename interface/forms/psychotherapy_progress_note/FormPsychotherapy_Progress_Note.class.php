<?php
/*
 * Psychotherapy Progress Note form
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Benjamin Foster <dr@fosterpsych.com>
 * @copyright Copyright (c) 2025 Benjamin Foster <dr@fosterpsych.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
*/

require_once($GLOBALS['fileroot'] . "/library/forms.inc.php");
use OpenEMR\Common\ORDataObject\ORDataObject;

class FormPsychotherapy_Progress_Note extends ORDataObject {
    var $table = "form_psychotherapy_progress_note";
    var $id;
    var $pid;
    var $encounter;

    // General Block
    var $subjective;
    var $objective;

    // Mental Status Exam Block
    var $orientation = [];
    var $attention = [];
    var $appearance = [];
    var $behavior = [];
    var $speech = [];
    var $affect = [];
    var $mood = [];
    var $thought_process = [];
    var $thought_content = [];
    var $judgment = [];
    var $insight = [];

    // Risk Assessment Block
    var $denies_all_risk;
    var $suicide_level = [];
    var $homicide_level = [];
    var $selfharm_level = [];
    var $risk_info;
    var $overall_risk;

    // Assessment Block
    var $clinical_summary;
    var $diagnostic_support;
    var $assessment_symptoms;
    var $medical_necessity;
    var $therapeutic_interventions;

    // Progress Towards Goals Block
    var $treatment_goals;
    var $goals_progress;
    var $barriers = [];

    // Plan Block
    var $next_steps;
    var $next_appt_date;
    var $next_appt_time;

    function __construct($id = "") {
        parent::__construct();
        $this->_table = "form_psychotherapy_progress_note";
        if ($id != "") {
            $this->id = $id;
            $this->populate();
        }
    }
}
