<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

/**
 * 缓存配置
 * @param action 支持：apc、files、memcache、memcached、wincache、xcache，其中files memcache memcached这三种缓存支持分布式配置
 * @return array
 */

$conf = array();

$conf["action"] = "memcache";
$conf["expire"] = 86400;
$conf["flag"] = false; //启用压缩
$conf["key"] = "__CACHE_CONF__";

$conf["files"] = array
(
		"127.0.0.1:3309", "127.0.0.1:3308"
);

$conf["memcache"] = array(array("127.0.0.1", "11211"));

Pools::set("CACHE_CONF", $conf); //注册配置信息
unset($conf);