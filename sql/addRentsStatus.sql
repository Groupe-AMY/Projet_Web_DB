-- Author : Yannick.BAUDRAZ@cpnv.ch

USE snows;

ALTER TABLE rent_details
    ADD COLUMN status TINYINT UNSIGNED NOT NULL DEFAULT 0;

-- Example of an insert in the table with the new column
-- INSERT INTO rent_details (fk_rentId, fk_snowId, leasingDays, qtySnow) VALUES (3, 2, 7, 3)

-- Query to delete the column
-- ALTER TABLE rent_details DROP COLUMN status