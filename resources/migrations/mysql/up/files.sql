--
-- Table data for table `admin_resources`
--

INSERT INTO `admin_resources` (`id`, `identifier`)
VALUES (UUID(), 'files'),
       (UUID(), 'filecategories'),
       (UUID(), 'filedownloads'),
       (UUID(), 'usergroups_filecategories');

--
-- Table data for table `casbin_rule`
--

INSERT INTO `casbin_rule` (`ptype`, `v0`, `v1`, `v2`)
VALUES ('p', 'file-uploader', 'files', 'upload');

--
-- Table data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `identifier`, `name`)
VALUES (UUID(), 'file-uploader', 'File Uploader');

--
-- Table structure and data for table `file_categories`
--

CREATE TABLE `file_categories`
(
  `id`         char(36)         NOT NULL,
  `name`       varchar(100)        NOT NULL,
  `identifier` varchar(160)        NOT NULL,
  `is_public`  tinyint(1) unsigned NOT NULL DEFAULT 1,
  `created_at` timestamp           NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp           NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted`    tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `file_categories_deleted_index` (`deleted`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

INSERT INTO `file_categories` (`id`, `name`, `identifier`, `is_public`)
VALUES (UUID(), 'public', 'public', 1),
       (UUID(), 'privat', 'privat', 0);

--
-- Table structure and data for table `files`
--

CREATE TABLE `files`
(
  `id`               char(36)         NOT NULL,
  `file_category_id` char(36)          NOT NULL,
  `filesystem_name`  varchar(100)        NOT NULL,
  `public_name`      varchar(255)        NOT NULL DEFAULT '',
  `uploaded_at`      timestamp           NOT NULL DEFAULT current_timestamp(),
  `description`      text                NOT NULL,
  `created_at`       timestamp           NOT NULL DEFAULT current_timestamp(),
  `updated_at`       timestamp           NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted`          tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `file_category_id` (`file_category_id`),
  KEY `filesystem_name` (`filesystem_name`),
  KEY `files_deleted_index` (`deleted`),
  CONSTRAINT `files_ibfk_1` FOREIGN KEY (`file_category_id`) REFERENCES `file_categories` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Table structure and data for table `file_downloads`
--

CREATE TABLE `file_downloads`
(
  `id`            char(36)         NOT NULL,
  `file_id`       char(36)         NOT NULL,
  `user_id`       char(36)         NOT NULL,
  `downloaded_at` timestamp           NOT NULL DEFAULT current_timestamp(),
  `created_at`    timestamp           NOT NULL DEFAULT current_timestamp(),
  `updated_at`    timestamp           NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted`       tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `file_id` (`file_id`),
  KEY `user_id` (`user_id`),
  KEY `file_downloads_deleted_index` (`deleted`),
  CONSTRAINT `file_downloads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `file_downloads_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Table structure and data for table `user_groups_file_categories`
--

CREATE TABLE `user_groups_file_categories`
(
  `id`               char(36)  NOT NULL,
  `user_group_id`    char(36)  NOT NULL,
  `file_category_id` char(36)  NOT NULL,
  `created_at`       timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at`       timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_group_id` (`user_group_id`),
  KEY `file_category_id` (`file_category_id`),
  CONSTRAINT `ugfc_ibfk_1` FOREIGN KEY (`file_category_id`) REFERENCES `file_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ugfc_ibfk_2` FOREIGN KEY (`user_group_id`) REFERENCES `user_groups` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- Provide admins and file uploaders access to all file categories
INSERT INTO `user_groups_file_categories` (`id`, `user_group_id`, `file_category_id`)
SELECT UUID() AS `id`, `user_groups`.`id` AS `user_group_id`, `file_categories`.`id` AS `file_category_id`
FROM `user_groups`
       INNER JOIN `file_categories` ON 1
WHERE `user_groups`.`identifier` IN ('admin', 'file-uploader');

-- Provide access to relevant pages for file uploaders
INSERT IGNORE INTO `user_groups_admin_resources` (`id`, `user_group_id`, `admin_resource_id`)
SELECT UUID() AS `id`, user_groups.id AS user_group_id, admin_resources.id AS admin_resource_id
FROM user_groups
       INNER JOIN admin_resources ON admin_resources.identifier IN
                                     ('files', 'filecategories', 'filedownloads', 'usergroups_filecategories')
WHERE user_groups.identifier = 'file-uploader';

-- Provide admins access to all admin resources
INSERT IGNORE INTO `user_groups_admin_resources` (`id`, `user_group_id`, `admin_resource_id`)
SELECT UUID() AS `id`, `user_groups`.`id` AS `user_group_id`, `admin_resources`.`id` AS `admin_resource_id`
FROM user_groups
       INNER JOIN admin_resources ON 1
WHERE user_groups.identifier = 'admin';
