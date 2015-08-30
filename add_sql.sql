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

INSERT INTO `bdei_config` VALUES ('1', '开放注册', 'open_register', '1', '是否开放注册的功能');
INSERT INTO `bdei_config` VALUES ('2', '阅读权限', 'open_read', '0', '是否需要注册后才能阅读资源');
INSERT INTO `bdei_config` VALUES ('3', 'I P 限制', 'open_ip', '0', '是否打开IP限制功能，单击此处详细设置');
INSERT INTO `bdei_config` VALUES ('4', '单位简介 ', 'company_desc', '向用户提供最高质量的产品、服务和解决方案以及更多潜在价值，以赢得他们的尊重和信任，这是拓迪人的基本价值观。用户的满意度、忠诚度等综合指标才是我们更为看重d的价值所在地方。', null);
INSERT INTO `bdei_config` VALUES ('5', '系统公告', 'system_notice', '本校数字图书馆已正式投入使用，欢迎广大师生注册阅览。', '系统公告');
INSERT INTO `bdei_config` VALUES ('6', '搜索例如', 'search_example', '三国演义、茶花女、鲁迅、陶行知、PhotoShop、Java、SQL、Flash……', '搜索例如');
INSERT INTO `bdei_config` VALUES ('7', '标题图片', 'title_img', 'head_r1_c1.gif', '标题图片');

ALTER TABLE `bdei_user`
ADD COLUMN `add_time`  datetime NULL AFTER `answer`;

ALTER TABLE `bdei_book`
ADD COLUMN `text_info`  varchar(200) NULL COMMENT 'text文本内容' AFTER `index_show`;

