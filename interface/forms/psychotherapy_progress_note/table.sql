CREATE TABLE IF NOT EXISTS `form_psychotherapy_progress_note` (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    pid BIGINT(20) NOT NULL,            -- patient id
    encounter BIGINT(20) NOT NULL,      -- encounter id
    user VARCHAR(255) DEFAULT NULL,     -- logged in user
    groupname VARCHAR(255) DEFAULT NULL,
    authorized TINYINT(4) DEFAULT 0,
    activity TINYINT(4) DEFAULT 0,
    date DATETIME DEFAULT NULL,

    -- General Block
    subjective TEXT,
    objective TEXT,

    -- Mental Status Exam Block
    orientation TEXT,
    attention TEXT,
    appearance TEXT,
    behavior TEXT,
    speech TEXT,
    affect TEXT,
    mood TEXT,
    thought_process TEXT,
    thought_content TEXT,
    judgment TEXT,
    insight TEXT,

    -- Risk Assessment Block
    denies_all_risk TINYINT(1) DEFAULT 0,
    suicide_level TEXT,
	homicide_level TEXT,
    selfharm_level TEXT,
    risk_info TEXT,
    overall_risk ENUM('Low','Moderate','High'),

    -- Assessment Block
    clinical_summary TEXT,
    diagnostic_support TEXT,
    assessment_symptoms ENUM('Escalated','Maintained','Improved'),
    medical_necessity TEXT,
    therapeutic_interventions TEXT,

    -- Progress Towards Goals Block
    treatment_goals TEXT,
    goals_progress ENUM('Progressed','Regressed','No Change', 'Goals Achieved'),
    barriers TEXT,
	
    -- Plan Block
    next_steps TEXT,
    next_appt_date DATE,
    next_appt_time TIME,

    PRIMARY KEY (id)
) ENGINE=InnoDB;
