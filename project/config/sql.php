<?php


return array
(
		"CREATE_TABLE"=> array
		(
				"test"=> array
				(
						"name"=> "test",
						"expand"=> "Y",
						"createTable"=> "CREATE TABLE IF NOT EXISTS `{{table}}` (
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
				)
        )
);