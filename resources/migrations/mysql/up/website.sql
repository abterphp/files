--
-- Table data for table `admin_resources`
--

INSERT INTO `admin_resources` (`id`, `identifier`)
VALUES (UUID(), 'blocklayouts'),
       (UUID(), 'pagelayouts'),
       (UUID(), 'pages'),
       (UUID(), 'blocks');

--
-- Table data for table `casbin_rule`
--

INSERT INTO `casbin_rule` (`ptype`, `v0`, `v1`, `v2`)
VALUES ('p', 'admin', 'pages', 'advanced-write'),
       ('p', 'admin', 'blocks', 'advanced-write'),
       ('p', 'content-editor', 'pages', 'advanced-write'),
       ('p', 'content-editor', 'blocks', 'advanced-write'),
       ('p', 'layout-editor', 'pages', 'advanced-write'),
       ('p', 'layout-editor', 'blocks', 'advanced-write');

--
-- Table data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `identifier`, `name`)
VALUES (UUID(), 'content-editor', 'Content Editor'),
       (UUID(), 'layout-editor', 'Layout Editor');

--
-- Table structure and data for table `block_layouts`
--

CREATE TABLE `block_layouts`
(
  `id`         char(36)     NOT NULL,
  `identifier` varchar(160) NOT NULL,
  `body`       mediumtext   NOT NULL,
  `created_at` timestamp    NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp    NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted`    tinyint(1)   NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `block_layouts_identifier_uindex` (`identifier`),
  KEY `block_layouts_deleted_index` (`deleted`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

INSERT INTO `block_layouts` (`id`, `identifier`, `body`)
VALUES (UUID(), 'index-text-section',
        '                <div><hr class=\"section-heading-spacer\"></div>\r\n                <div class=\"clearfix\"></div>\r\n                <h2 class=\"section-heading\">{{var/title}}</h2>\r\n                <div class=\"lead\">{{var/body}}</div>'),
       (UUID(), 'empty', '{{var/body}}');

--
-- Table structure and data for table `blocks`
--

CREATE TABLE `blocks`
(
  `id`         char(36)            NOT NULL,
  `identifier` varchar(160)        NOT NULL,
  `title`      varchar(120)        NOT NULL,
  `body`       mediumtext          NOT NULL,
  `layout_id`  char(36)            NULL,
  `layout`     mediumtext          NOT NULL,
  `created_at` timestamp           NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp           NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted`    tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `blocks_deleted_index` (`deleted`),
  KEY `block_layouts_id_fk` (`layout_id`),
  CONSTRAINT `blocks_layouts_id_fk` FOREIGN KEY (`layout_id`) REFERENCES `block_layouts` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Table structure and data for table `page_layouts`
--

CREATE TABLE `page_layouts`
(
  `id`         char(36)     NOT NULL,
  `identifier` varchar(160) NOT NULL,
  `body`       mediumtext   NOT NULL,
  `header`     mediumtext   NOT NULL,
  `footer`     mediumtext   NOT NULL,
  `css_files`  mediumtext   NOT NULL,
  `js_files`   mediumtext   NOT NULL,
  `created_at` timestamp    NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp    NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted`    tinyint(1)   NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_layouts_identifier_uindex` (`identifier`),
  KEY `page_layouts_deleted_index` (`deleted`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

INSERT INTO `page_layouts` (`id`, `identifier`, `body`, `header`, `footer`, `css_files`, `js_files`)
VALUES (UUID(), 'empty', '{{var/body}}', '', '', '', '');

--
-- Table structure and data for table `pages`
--

CREATE TABLE `pages`
(
  `id`                  char(36)            NOT NULL,
  `identifier`          varchar(160)        NOT NULL,
  `title`               varchar(255)        NOT NULL,
  `meta_description`    mediumtext          NOT NULL,
  `meta_robots`         varchar(100)        NOT NULL,
  `meta_author`         varchar(160)        NOT NULL,
  `meta_copyright`      varchar(160)        NOT NULL,
  `meta_keywords`       varchar(255)        NOT NULL,
  `meta_og_title`       varchar(255)        NOT NULL,
  `meta_og_image`       varchar(255)        NOT NULL,
  `meta_og_description` mediumtext          NOT NULL,
  `body`                mediumtext          NOT NULL,
  `layout_id`           char(36)            NULL,
  `layout`              mediumtext          NOT NULL,
  `header`              mediumtext          NOT NULL,
  `footer`              mediumtext          NOT NULL,
  `css_files`           mediumtext          NOT NULL,
  `js_files`            mediumtext          NOT NULL,
  `created_at`          timestamp           NOT NULL DEFAULT current_timestamp(),
  `updated_at`          timestamp           NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted`             tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `pages_deleted_index` (`deleted`),
  KEY `page_layouts_id_fk` (`layout_id`),
  CONSTRAINT `pages_layouts_id_fk` FOREIGN KEY (`layout_id`) REFERENCES `page_layouts` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

INSERT INTO `pages` (id, identifier, title, meta_description, meta_robots, meta_author, meta_copyright, meta_keywords,
                     meta_og_title, meta_og_image, meta_og_description, body, layout_id, layout, header, footer,
                     css_files, js_files)
VALUES (UUID(), 'index', 'New AbterCMS installation',
        'AbterCMS is a security first, simple and flexible open source content management system for both educational and commercial usecases.',
        '', '', '', 'cms, open source', '', '', '', 'Hello, World!', NULL, '<div class="container">{{var/body}}</div>',
        '', '', '', '');

--
-- Table structure and data for table `user_groups_pages`
--

CREATE TABLE `user_groups_pages`
(
  `id`            char(36)  NOT NULL,
  `user_group_id` char(36)  NOT NULL,
  `page_id`       char(36)  NOT NULL,
  `created_at`    timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at`    timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_group_id` (`user_group_id`),
  KEY `page_id` (`page_id`),
  CONSTRAINT `ugp_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ugp_ibfk_2` FOREIGN KEY (`user_group_id`) REFERENCES `user_groups` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- Provide admins, content editors and layouts access to all pages
INSERT INTO `user_groups_pages` (`id`, `user_group_id`, `page_id`)
SELECT UUID(), `user_groups`.`id` AS `user_group_id`, `pages`.`id` AS `file_category_id`
FROM `user_groups`
       INNER JOIN `pages` ON 1
WHERE `user_groups`.`identifier` IN ('admin', 'content-editor', 'layout-editor');

-- Provide access to relevant pages for layout editors
INSERT IGNORE INTO `user_groups_admin_resources` (`id`, `user_group_id`, `admin_resource_id`)
SELECT UUID(), user_groups.id AS user_group_id, admin_resources.id AS admin_resource_id
FROM user_groups
       INNER JOIN admin_resources ON admin_resources.identifier IN ('blocklayouts', 'pagelayouts', 'pages', 'blocks')
WHERE user_groups.identifier = 'layout-editor';

-- Provide access to relevant pages for content editors
INSERT IGNORE INTO `user_groups_admin_resources` (`id`, `user_group_id`, `admin_resource_id`)
SELECT UUID(), user_groups.id AS user_group_id, admin_resources.id AS admin_resource_id
FROM user_groups
       INNER JOIN admin_resources ON admin_resources.identifier IN ('pages', 'blocks')
WHERE user_groups.identifier = 'content-editor';

-- Provide admins access to all resources
INSERT IGNORE INTO `user_groups_admin_resources` (`id`, `user_group_id`, `admin_resource_id`)
SELECT UUID(), user_groups.id AS user_group_id, admin_resources.id AS admin_resource_id
FROM user_groups
       INNER JOIN admin_resources ON 1
WHERE user_groups.identifier = 'admin';
