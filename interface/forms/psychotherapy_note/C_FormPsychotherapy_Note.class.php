<?php

/*
 * Psychotherapy Note form
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Benjamin Foster <dr@fosterpsych.com>
 * @copyright Copyright (c) 2025 Benjamin Foster <dr@fosterpsych.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once(__DIR__ . '/../../globals.php');
require_once($GLOBALS['fileroot'] . '/library/forms.inc.php');
require_once(__DIR__ . '/FormPsychotherapy_Note.class.php');


use OpenEMR\Common\Twig\TwigContainer;

class C_FormPsychotherapy_Note extends Controller
{
    private readonly TwigContainer $twig;

    public function __construct()
    {
        parent::__construct();
        $path = $this->getTemplatePath();
        $this->twig = new TwigContainer($path);
    }

    /**
     * default_action()
     * Load a new or existing form
     */

    public function default_action()
    {
        $form = new FormPsychotherapy_Note();

        return $this->twig->getTwig()->render(
            "form_psychotherapy_note.twig",
            [
                "form" => $form,
                "FORM_ACTION" => $GLOBALS['web_root'],
            ]
        );
    }

    /**
     * view_action()
     * Called when viewing a saved, unsigned note
     */
    public function view_action($form_id)
    {
        if (!is_numeric($form_id)) {
            throw new \Exception("ERROR: Invalid form ID passed to conroller. Please reload the encounter and try again");
        }

        $form = new FormPsychotherapy_Note((int)$form_id);
        $form->populate();

        // Decode JSON arrays for the checkbox fields
        $checkbox_fields = [
            'orientation','attention','appearance','behavior','speech','affect','mood',
            'thought_process','thought_content','judgment','insight','suicide_level',
            'homicide_level','selfharm_level','barriers'
        ];
        foreach ($checkbox_fields as $field) {
            if (is_string($form->$field)) {
                $form->$field = json_decode($form->$field, true) ?: [];
            }
        }

        // Render the form
        return $this->twig->getTwig()->render(
            'form_psychotherapy_note.twig',
            [
                'form'       => $form,
                'pid'        => $form->pid,
                'encounter'  => $form->encounter,
                'FORM_ACTION'=> $GLOBALS['web_root'],
            ]
        );
    }

    /**
     * report_action()
     * Displays this form in the encounter summary
     */
    public function report_action($pid, $encounter, $cols, $id)
    {
        $form = new FormPsychotherapy_Note((int)$id);
        $form->populate();

        // Decode JSON checkbox fields for display
        $checkbox_fields = [
            'orientation','attention','appearance','behavior','speech','affect','mood',
            'thought_process','thought_content','judgment','insight','suicide_level',
            'homicide_level','selfharm_level','barriers'
        ];
        foreach ($checkbox_fields as $field) {
            if (is_string($form->$field)) {
                $form->$field = json_decode($form->$field, true) ?: [];
            }
        }

        // Render the form
        return $this->twig->getTwig()->render(
            "form_psychotherapy_note_report.twig",
            ["form" => $form]
        );
    }

    /**
     * save_action()
     * Save the form by processing POST submission and persist to DB
     */

    public function save_action()
    {
       global $pid, $encounter;

        $formId = isset($_POST['id']) && ctype_digit((string)$_POST['id']) ? (int)$_POST['id'] : "";
        $form = new FormPsychotherapy_Note($formId ?: "");

        // Assign form data and normalize arrays before saving
        foreach ($_POST as $key => $val) {
            if (property_exists($form, $key)) {                if (is_array($val)) {
                    $form->$key = json_encode(array_values(array_filter($val, fn($v) => $v !== '')), JSON_UNESCAPED_SLASHES);
                } else {
                    $form->$key = trim((string)$val);
                }
            }
        }

        // Assign correct form metadata
        $form->pid        = $pid;
        $form->encounter  = $encounter;
        $form->user       = $_SESSION['authUser'] ?? "";
        $form->groupname  = $_SESSION['authProvider'] ?? "";
        $form->authorized = 0;
        $form->date       = date("Y-m-d H:i:s");

        // Save the form
        $form->persist();

        if (empty($_POST['id'])) {
            addForm(
                $encounter,             // 1. encounter
                "Psychotherapy Note",   // 2. form name
                $form->id,              // 3. form id from table
                "psychotherapy_note",   // 4. form directory
                $pid,                   // 5. patient id
                $form->authorized,      // 6. authorized
                $form->date,            // 7. date
                $form->user,            // 8. user
                $form->groupname        // 9. group/provider
            );
        }
    }

    /**
    * @return string
    */
    private function getTemplatePath(): string
    {
        return __DIR__ . "/templates";
    }
}
