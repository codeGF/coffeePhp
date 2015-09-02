<?php


class SqlStatement
{

	public function term($term="")
	{
		if (!$term)
		{
			$term = "*";
		}else if (is_array($term))
        {
			$tmp = "";
			foreach ($term as $v)
			{
				$tmp .= sprintf("`%s`,", $v);
			}
			$term = trim($tmp, ",");
		}
		return $term;
	}

	public function where($data)
	{
		if ($data)
		{
			$tmp = " WHERE "; //char
			if (is_array($data))
			{
				foreach ($data as $k => $v)
				{
					if (is_array($v))
					{
						$or = "(";
						foreach ($v as $vv)
						{
							$tmp .= sprintf("%s`%s`='%s' OR ", $or, $k, $vv);
							$or = "";
						}
						$tmp = sprintf("%s) AND ", trim($tmp, "OR "));
					}else
					{
						$tmp .= sprintf("`%s`='%s' AND ", $k, $v);
					}
				}
				$tmp = trim($tmp, "AND ");
				$tmp = count($data, true) > 2 ? $tmp : str_replace(array("(", ")"), "", $tmp);
			}else
			{
				$tmp .= sprintf("%s ", $data);
			}
			return $tmp;
		}
		return false;
	}

	public function insertValue($data)
	{
		$tmp = "";
		foreach ($data as $k => $v)
		{
			$tmp .= "`$k`='$v',";
		}
		return trim($tmp, ",");
	}

	public function limit($data)
	{
		if (is_array($data))
		{
			return " LIMIT ".implode(",", $data)." ";
		}else if (!empty($data))
        {
			return " LIMIT {$data}";
		}
		return "";
	}

	public function order($order="")
	{
		if (isset($order[0]) && isset($order[1]))
		{
			if (is_array($order[0]))
			{
				$key = trim(implode(",", $order[0]), ",");
			}else
			{
				$key = $order[0];
			}
			$eqe = strtoupper($order[1]);
			return " ORDER BY {$key} {$eqe}";
		}
		return false;
	}

	public function group($group="")
	{
		if ($group)
		{
			if (is_array($group))
			{
				$key = trim(implode(",", $group), ",");
			}else
			{
				$key = $group;
			}
			return " GROUP BY {$key}";
		}
		return false;
	}
}