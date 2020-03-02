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
	primary key (id)

)engine=InnoDB default charset=utf8 comment '商品';
