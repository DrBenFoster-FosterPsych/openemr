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

use OpenEMR\Common\ORDataObject\ORDataObject;

class FormPsychotherapy_Note extends ORDataObject
{
    public $id;
    public $pid;
    public $encounter;
    public $date;
    public $user;
    public $groupname;
    public $authorized;
    public $activity;

    // General Block
    public $subjective = null;
    public $objective = null;

    // Mental Status Exam Block
    public $orientation = null;
    public $attention = null;
    public $appearance = null;
    public $behavior = null;
    public $speech = null;
    public $affect = null;
    public $mood = null;
    public $thought_process = null;
    public $thought_content = null;
    public $judgment = null;
    public $insight = null;

    // Risk Assessment Block
    public $denies_all_risk = null;
    public $suicide_level = null;
    public $homicide_level = null;
    public $selfharm_level = null;
    public $risk_info = null;
    public $overall_risk = null;

    // Assessment Block
    public $clinical_summary = null;
    public $diagnostic_support = null;
    public $assessment_symptoms = null;
    public $medical_necessity = null;
    public $therapeutic_interventions = null;

    // Progress Towards Goals Block
    public $treatment_goals = null;
    public $goals_progress = null;
    public $barriers = null;

    // Plan Block
    public $next_steps = null;
    public $next_appt_date = null;
    public $next_appt_time = null;

    // === Constructor ===
    public function __construct($id = "")
    {
        parent::__construct();
        $this->_table = "form_psychotherapy_note";
        $this->_idField = "id";
        $this->fields = [
            'id', 'pid', 'encounter', 'user', 'groupname', 'authorized',
            'activity', 'date', 'subjective', 'objective',
            'orientation','attention','appearance','behavior','speech',
            'affect','mood','thought_process','thought_content','judgment',
            'insight','denies_all_risk','suicide_level','homicide_level',
            'selfharm_level','risk_info','overall_risk','clinical_summary',
            'diagnostic_support','assessment_symptoms','medical_necessity',
            'therapeutic_interventions','treatment_goals','goals_progress',
            'barriers','next_steps','next_appt_date','next_appt_time'
        ];

        if (!empty($id) && is_numeric($id)) {
            $this->id = (int)$id;
            $this->populate();
        } else {
            $this->date = date("Y-m-d H:i:s");
            $this->activity = 1;
        }
    }


    /*
    * ORDataObject::populate() has been rather buggy with SQL errors in the ADOdb layer
    * populate_custom() bypasses parent::populate() and uses a manual workaround
    */
    public function populate() { return $this->populate_custom(); }

    public function populate_custom()
    {
        try {
            $conf = $GLOBALS['sqlconf'];
            $pdo = new \PDO(
                "mysql:host={$conf['host']};dbname={$conf['dbase']};charset=utf8mb4",
                $conf['login'],
                $conf['pass'],
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );

            $stmt = $pdo->prepare("SELECT * FROM {$this->_table} WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $this->id]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($row) {
                foreach ($row as $col => $val) {
                    if (property_exists($this, $col)) {
                        $this->$col = $val;
                    }
                }
            } else {
                error_log("DEBUG populate(PDO): no record found for id={$this->id}");
            }

            return $this;
        } catch (\Throwable $e) {
            error_log("populate(PDO) exception: " . $e->getMessage());
            return $this;
        }
    }

    public function set_id($id) { $this->id = $id; }

    public function get_id() { return $this->id; }

    /*
    * ORDataObject::persist() has been rather buggy with SQL errors in the ADOdb layer
    * persist_custom() bypasses parent::persist() and uses a manual workaround
    */
    public function persist() { return $this->persist_custom(); }

    public function persist_custom()
    {
        try {
            //------------------------------------------------------------------
            // 1. Open a clean PDO connection using OpenEMR credentials
            //------------------------------------------------------------------
            $conf = $GLOBALS['sqlconf'];
            $pdo = new \PDO(
                "mysql:host={$conf['host']};dbname={$conf['dbase']};charset=utf8mb4",
                $conf['login'],
                $conf['pass'],
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );

            //------------------------------------------------------------------
            // define which fields should treat '' as NULL
            //------------------------------------------------------------------
            $dateFields = ['next_appt_date', 'next_appt_time', 'date'];

            //------------------------------------------------------------------
            // 2. Existing record â†’ UPDATE
            //------------------------------------------------------------------
            if (!empty($this->id)) {
                $setParts = [];
                $params   = [];

                foreach ($this->fields as $f) {
                    if ($f === 'id') continue;

                    $val = $this->$f ?? null;

                    // ðŸ©º convert empty strings in date/time fields to NULL
                    if ($val === '' && in_array($f, $dateFields, true)) {
                        $val = null;
                    }

                    $setParts[] = "`$f` = :$f";
                    $params[":$f"] = $val;
                }

                $params[':id'] = (int)$this->id;
                $sql = "UPDATE {$this->_table} SET " . implode(',', $setParts) . " WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);

                return $this->id;
            }

            //------------------------------------------------------------------
            // 3. New record â†’ INSERT
            //------------------------------------------------------------------
            $fields = array_filter($this->fields, fn($f) => $f !== 'id');
            $cols   = '`' . implode('`,`', $fields) . '`';
            $phs    = ':' . implode(',:', $fields);

            $sql = "INSERT INTO {$this->_table} ($cols) VALUES ($phs)";
            $stmt = $pdo->prepare($sql);

            $params = [];
            foreach ($fields as $f) {
                $val = $this->$f ?? null;

                // ðŸ©º convert empty strings in date/time fields to NULL
                if ($val === '' && in_array($f, $dateFields, true)) {
                    $val = null;
                }

                $params[":$f"] = $val;
            }

            $stmt->execute($params);
            $this->id = (int)$pdo->lastInsertId();

            return $this->id;
        }
        catch (\Throwable $e) {
            //------------------------------------------------------------------
            // 4. Clean failure reporting (no recursive OpenEMR logger)
            //------------------------------------------------------------------
            error_log("persist_custom(PDO) exception: " . $e->getMessage());
            return 0;
        }
    }

}
