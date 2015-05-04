ALTER TABLE `bdei_bookmark`
ADD COLUMN `add_time`  datetime NULL AFTER `book_id`;
ALTER TABLE `bdei_book`
ADD COLUMN `index_show`  tinyint(1) NULL AFTER `add_time`;

ALTER TABLE `bdei_bookmark`
ADD UNIQUE INDEX `un_book_user_id` (`user_id`, `book_id`) ;

ALTER TABLE `bdei_user_read_history`
ADD UNIQUE INDEX `un_book_user_id` (`book_id`, `user_id`) ;

ALTER TABLE `bdei_book_extend`
ADD COLUMN `book_info`  mediumtext NULL COMMENT '书本的内容，用于有声图书' AFTER `book_catalog_desc`;


