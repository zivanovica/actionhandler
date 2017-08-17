CREATE SCHEMA IF NOT EXISTS `bulletproof`
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `unique` (
  `id`           INT         NOT NULL,
  `entity_model` VARCHAR(45) NULL,
  `entity_id`    INT         NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `entity_UNIQUE` (`entity_model` ASC, `entity_id` ASC)
);