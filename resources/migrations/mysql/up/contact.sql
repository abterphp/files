--
-- Table data for table `pages`
--

INSERT INTO `pages` (`id`, `identifier`, `title`, `meta_description`, `meta_robots`, `meta_author`, `meta_copyright`,
                     `meta_keywords`, `meta_og_title`, `meta_og_image`, `meta_og_description`, `body`, `header`,
                     `footer`, `css_files`, `js_files`, `layout_id`, `layout`, `created_at`, `updated_at`, `deleted`)
VALUES (UUID(), 'contact-success', 'Contact Success', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '',
        NOW(), NOW(), 0),
       (UUID(), 'contact-error', 'Contact Error', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', NOW(),
        NOW(), 0);
