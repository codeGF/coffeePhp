<?php


/**
 * 注册表机制，如果启用，所有请求必须符合此配置表
 * @var is 是否启用
 * @var list 白名单列表
 * @return array
 */

return array
(
		"is"=> true,
		"list"=> array
		(
				"index"
		)
);