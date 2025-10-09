ALTER TABLE `form_psychotherapy_progress_note`
    ADD COLUMN IF NOT EXISTS subjective TEXT,
    ADD COLUMN IF NOT EXISTS objective TEXT,

    ADD COLUMN IF NOT EXISTS orientation TEXT,
    ADD COLUMN IF NOT EXISTS attention TEXT,
    ADD COLUMN IF NOT EXISTS appearance TEXT,
    ADD COLUMN IF NOT EXISTS behavior TEXT,
    ADD COLUMN IF NOT EXISTS speech TEXT,
    ADD COLUMN IF NOT EXISTS affect TEXT,
    ADD COLUMN IF NOT EXISTS mood TEXT,
    ADD COLUMN IF NOT EXISTS thought_process TEXT,
    ADD COLUMN IF NOT EXISTS thought_content TEXT,
    ADD COLUMN IF NOT EXISTS judgment TEXT,
    ADD COLUMN IF NOT EXISTS insight TEXT,

    ADD COLUMN IF NOT EXISTS denies_all_risk TINYINT(1) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS suicide_level TEXT,
    ADD COLUMN IF NOT EXISTS homicide_level TEXT,
    ADD COLUMN IF NOT EXISTS selfharm_level TEXT,
	ADD COLUMN IF NOT EXISTS risk_info TEXT;
    ADD COLUMN IF NOT EXISTS overall_risk ENUM('Low','Moderate','High'),

    ADD COLUMN IF NOT EXISTS clinical_summary TEXT,
    ADD COLUMN IF NOT EXISTS diagnostic_support TEXT,
    ADD COLUMN IF NOT EXISTS assessment_symptoms ENUM('Escalated','Maintained','Improved'),
    ADD COLUMN IF NOT EXISTS medical_necessity TEXT,
    ADD COLUMN IF NOT EXISTS therapeutic_interventions TEXT,

    ADD COLUMN IF NOT EXISTS treatment_goals TEXT,
    ADD COLUMN IF NOT EXISTS goals_progress ENUM('Progressed','Regressed','No Change', 'Goals Achieved'),
    ADD COLUMN IF NOT EXISTS barriers TEXT,

    ADD COLUMN IF NOT EXISTS next_steps TEXT,
    ADD COLUMN IF NOT EXISTS next_appt_date DATE,
    ADD COLUMN IF NOT EXISTS next_appt_time TIME;
