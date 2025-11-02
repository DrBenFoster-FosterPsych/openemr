CREATE TABLE IF NOT EXISTS `form_psychotherapy_note` (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    pid BIGINT(20) NOT NULL,
    encounter BIGINT(20) NOT NULL,
    user VARCHAR(255) DEFAULT NULL,
    groupname VARCHAR(255) DEFAULT NULL,
    authorized TINYINT(4) DEFAULT 0,
    activity TINYINT(4) DEFAULT 0,
    date DATETIME DEFAULT NULL,

    -- General Block
    subjective TEXT DEFAULT NULL,
    objective TEXT DEFAULT NULL,

    -- Mental Status Exam Block
    orientation VARCHAR(255) DEFAULT NULL,
    attention VARCHAR(255) DEFAULT NULL,
    appearance VARCHAR(255) DEFAULT NULL,
    behavior VARCHAR(255) DEFAULT NULL,
    speech VARCHAR(255) DEFAULT NULL,
    affect VARCHAR(255) DEFAULT NULL,
    mood VARCHAR(255) DEFAULT NULL,
    thought_process VARCHAR(255) DEFAULT NULL,
    thought_content VARCHAR(255) DEFAULT NULL,
    judgment VARCHAR(255) DEFAULT NULL,
    insight VARCHAR(255) DEFAULT NULL,

    -- Risk Assessment Block
    denies_all_risk TINYINT(1) DEFAULT 0,
    suicide_level VARCHAR(255) DEFAULT NULL,
    homicide_level VARCHAR(255) DEFAULT NULL,
    selfharm_level VARCHAR(255) DEFAULT NULL,
    risk_info TEXT DEFAULT NULL,
    overall_risk ENUM('None', 'Low','Moderate','High'),

    -- Assessment Block
    clinical_summary TEXT DEFAULT NULL,
    diagnostic_support TEXT DEFAULT NULL,
    assessment_symptoms ENUM('Escalated','Maintained','Improved'),
    medical_necessity TEXT DEFAULT NULL,
    therapeutic_interventions TEXT DEFAULT NULL,

    -- Psychotherapy Towards Goals Block
    treatment_goals TEXT DEFAULT NULL,
    goals_progress ENUM('Regressed','No change', 'Progressed', 'Goals achieved'),
    barriers TEXT DEFAULT NULL,

    -- Plan Block
    next_steps TEXT DEFAULT NULL,
    next_appt_date DATE DEFAULT NULL,
    next_appt_time TIME DEFAULT NULL,

    PRIMARY KEY (id)
) ENGINE=InnoDB;
