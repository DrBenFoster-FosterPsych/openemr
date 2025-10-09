<?php
/*
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

require_once($GLOBALS['fileroot'] . "/library/forms.inc.php");
require_once("FormPsychotherapy_Progress_Note.class.php");

use OpenEMR\Common\Twig\TwigContainer;

////
// Controller for form
////
class C_FormPsychotherapy_Progress_Note
{
    protected $formDir;

    public function __construct()
    {
        $this->formDir = __DIR__;
    }

    ////
    // Default action (view new form or existing form)
    ////
    public function default_action()
    {
        return $this->view_action();
    }

    ////
    // Render the form (new or existing)
    ////
    public function view_action($id = null)
    {
        $form = new FormPsychotherapy_Progress_Note(
            $id,
            $_SESSION["pid"],
            $_SESSION["encounter"]
        );

        // All JSON fields that will render as checkboxes
        $checkbox_fields = [
            'orientation', 'attention', 'appearance', 'behavior',
            'speech', 'affect', 'mood', 'thought_process',
            'thought_content', 'judgment', 'insight', 'suicide_level',
            'homicide_level', 'selfharm_level'
        ];

        foreach ($checkbox_fields as $field) {
            if (is_string($form->$field)) {
                $form->$field = json_decode($form->$field, true) ?: [];
            }
        }

	$loader = new \Twig\Loader\FilesystemLoader([
	    $this->formDir . '/templates',      // look the form folder first
	    $GLOBALS['fileroot'] . '/templates' // fallback to global templates
	]);

	$twig = new \Twig\Environment($loader, ['cache' => false]);

	echo $twig->render("form_psychotherapy_progress_note.twig", [
	    "form"      => $form,
	    "id"        => $id,
	    "pid"       => $_SESSION["pid"],
	    "encounter" => $_SESSION["encounter"],
	    "action"    => "save.php",
	]);

    }

    ////
    // Save the form (insert or update)
    ////
    public function save_action()
    {
        $form = new FormPsychotherapy_Progress_Note(
            $_POST["id"] ?? "",
            $_SESSION["pid"],
            $_SESSION["encounter"]
        );

        // load posted data into form object
        $form->populate_object($_POST);

        // Encode Mental Status JSON fields before saving
        $checkbox_fields = [
            'orientation', 'attention', 'appearance', 'behavior',
            'speech', 'affect', 'mood', 'thought_process',
            'thought_content', 'judgment', 'insight', 'suicide_level',
            'homicide_level', 'selfharm_level'
        ];

        foreach ($checkbox_fields as $field) {
            if (isset($_POST[$field]) && is_array($_POST[$field])) {
                $form->$field = json_encode($_POST[$field]);
            }
        }

        // save to DB
        $form->persist();

        // redirect back to encounter summary
        formHeader("Redirecting...");
        formJump();
        formFooter();
    }

    ////
    // Render a read-only report view (for encounter summary screen)
    ////
    public function report_action($id)
    {
        $form = new FormPsychotherapy_Progress_Note($id);

	$loader = new \Twig\Loader\FilesystemLoader([
	    $this->formDir . '/templates',      // look in form folder first
	    $GLOBALS['fileroot'] . '/templates' // fallback to global templates
	]);

	$twig = new \Twig\Environment($loader, ['cache' => false]);

	echo $twig->render("form_psychotherapy_progress_note_report.twig", [
	    "form"      => $form,
	    "id"        => $id,
	    "pid"       => $form->pid,
	    "encounter" => $form->encounter,
	]);

    }
}
