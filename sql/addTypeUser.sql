-- Author : Yannick.BAUDRAZ@cpnv.ch

ALTER TABLE users
    ADD COLUMN userType SMALLINT(1) NOT NULL DEFAULT 0;