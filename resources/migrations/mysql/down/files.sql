SET FOREIGN_KEY_CHECKS = false;
DELETE FROM `admin_resources` WHERE `identifier` IN ('files', 'file-categories', 'file-downloads', 'user-groups_file-categories');
DELETE FROM `casbin_rule` WHERE `v1` = 'files' AND `v2` = 'upload';
DROP TABLE IF EXISTS `file_categories`;
DROP TABLE IF EXISTS `file_downloads`;
DROP TABLE IF EXISTS `files`;
DROP TABLE IF EXISTS `user_groups_file_categories`;
SET FOREIGN_KEY_CHECKS = true;
