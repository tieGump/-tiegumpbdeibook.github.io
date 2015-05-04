ALTER TABLE `bdei_bookmark`
ADD COLUMN `add_time`  datetime NULL AFTER `book_id`;
ALTER TABLE `bdei_book`
ADD COLUMN `index_show`  tinyint(1) NULL AFTER `add_time`;

