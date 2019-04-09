SET FOREIGN_KEY_CHECKS = false;
DELETE FROM `admin_resources` WHERE `identifier` IN ('blocklayouts', 'pagelayouts', 'pages', 'blocks');
DELETE FROM `casbin_rule` WHERE `v1` = 'pages' AND `v2` = 'advanced-write';
DROP TABLE IF EXISTS `block_layouts`;
DROP TABLE IF EXISTS `blocks`;
DROP TABLE IF EXISTS `page_layouts`;
DROP TABLE IF EXISTS `pages`;
DROP TABLE IF EXISTS `user_groups_pages`;
SET FOREIGN_KEY_CHECKS = true;
