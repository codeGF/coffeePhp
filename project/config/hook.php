<?php


/**
 * @author changguofeng <281441619>
 * 钩子配置
 * @param bool en 是否运行钩子
 * @param array conf 调用钩子配置
 * @return array
 */

return array
(
		"en"=> false,
		"conf"=> array
		(
				"index"=> array
				(
						"__construct"=> "auto_.controller.test.hooks[xxx, xxx]",
                )
		)
);