create database shopcz;
use shopcz;
set names utf8;
drop table if exists sc_goods;
create table sc_goods
(
	id mediumint unsigned not null auto_increment comment 'Id',
	goods_name varchar(150) not null comment '商品名称',
	market_price decimal(10,2) not null comment '本店价格',
	shop_price decimal(10,2) not null comment '市场价格',
	goods_desc longtext comment '商品描述',
	is_on_sale int(5) not null default 1 comment '是否上架',
	is_delete int(5) not null default 0 comment '是否放到回收站',
	addtime datetime not null comment '添加时间',
	logo varchar(150) not null default '' comment '原图',
	sm_logo varchar(150) not null default '' comment '小图',
	mid_logo varchar(150) not null default '' comment '中图',
	big_logo varchar(150) not null default '' comment '大图',
	mbig_logo varchar(150) not null default '' comment '更大图',
	brand_id mediumint unsigned not null default '0' comment '品牌id',
	primary key (id),
	key market_price(market_price),
	key addtime(addtime),
	key brand_id(brand_id),
	key is_on_sale(is_on_sale),
	key is_delete(is_delete)

)engine=InnoDB default charset=utf8 comment '商品';

drop table if exists sc_brand;
create table sc_brand
(
	id mediumint unsigned not null auto_increment comment 'Id',
	brand_name varchar(30) not null comment '品牌名称',
	site_url varchar(150) not null default '' comment '官方网址',
	logo varchar(150) not null default '' comment '品牌logo图片',
	primary key (id)
)engine=InnoDB default charset=utf8 comment '品牌';

drop table if exists sc_member_level;
create table sc_member_level
(
	id mediumint unsigned not null auto_increment comment 'Id',
	level_name varchar(30) not null comment '级别名称',
	jifen_top mediumint unsigned not null comment '积分上限',
	jifen_bottom mediumint unsigned not null comment '积分下限',
	primary key (id)
)engine=InnoDB default charset=utf8 comment '会员级别';

drop table if exists sc_member_price;
create table sc_member_price
(
	price decimal(10,2) not null comment '会员价格',
	goods_id mediumint unsigned not null comment '商品id',
	level_id mediumint unsigned not null comment '级别id',
	key level_id(level_id),
	key goods_id(goods_id)
)engine=InnoDB default charset=utf8 comment '会员价格';

drop table if exists sc_category;
create table sc_category
(
	id mediumint unsigned not null auto_increment comment 'Id',
	cat_name varchar(30) not null comment '分类名称',
	parent_id mediumint unsigned not null default '0' comment '上级分类的Id, 0:顶级分类',
	primary key (id)
)engine=InnoDB default charset=utf8 comment '分类';