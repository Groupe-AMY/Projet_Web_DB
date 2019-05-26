-- Author :     YAnnick.BAUDRAZ@cpnv.ch

CREATE TABLE IF NOT EXISTS `rents`
(
    `id`        INT(12) UNSIGNED NOT NULL,
    `fk_userId` INT(10) UNSIGNED NOT NULL,
    `dateStart` DATE             NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_rents_users1_idx` (`fk_userId` ASC) VISIBLE,
    CONSTRAINT `fk_rents_users1`
        FOREIGN KEY (`fk_userId`)
            REFERENCES `snows`.`users` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `rent_details`
(
    `fk_rentId`   INT(12) UNSIGNED     NOT NULL,
    `fk_snowId`   INT(11)              NOT NULL,
    `leasingDays` SMALLINT(4) UNSIGNED NOT NULL,
    `qtySnow`     SMALLINT(6) UNSIGNED NOT NULL,
    INDEX `fk_rents_has_snows_snows1_idx` (`fk_snowId` ASC) VISIBLE,
    INDEX `fk_rents_has_snows_rents_idx` (`fk_rentId` ASC) VISIBLE,
    PRIMARY KEY (`fk_rentId`, `fk_snowId`, `leasingDays`),
    CONSTRAINT `fk_rents_has_snows_rents`
        FOREIGN KEY (`fk_rentId`)
            REFERENCES `snows`.`rents` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
    CONSTRAINT `fk_rents_has_snows_snows1`
        FOREIGN KEY (`fk_snowId`)
            REFERENCES `snows`.`snows` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;