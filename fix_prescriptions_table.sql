ALTER TABLE prescriptions ADD COLUMN morning TINYINT UNSIGNED DEFAULT 0 AFTER frequency;
ALTER TABLE prescriptions ADD COLUMN evening TINYINT UNSIGNED DEFAULT 0 AFTER morning;
ALTER TABLE prescriptions ADD COLUMN night TINYINT UNSIGNED DEFAULT 0 AFTER evening;

-- If duration exists and days doesn't, rename it
SET @exist := (SELECT count(*) FROM information_schema.columns WHERE table_name = 'prescriptions' AND column_name = 'duration');
SET @sqlstmt := if(@exist > 0, 'ALTER TABLE prescriptions RENAME COLUMN duration TO days', 'SELECT 1');
PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
