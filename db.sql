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
	primary key (id)

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