<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>MoneyConvertor Example</title>
		<meta http-equiv="Content-Type"  content="text/html; charset=utf-8" />

		<style>
			body {
				font-size: 14px;
				font-family: Cambria, "Tahoma", "SimSun";
			}
			table {
				border-collapse: collapse;
				border-spacing: 0;
				margin-bottom: 20px;
			}

			table th, table td {
				padding: 8px;
				line-height: 20px;
				text-align: left;
				vertical-align: top;
				border: 1px solid #DDD;
			}
		</style>
	</head>
	<body>

		<table style="min-width: 600px">
			<thead>
				<tr>
					<th style="text-align:left">编号</th>
					<th style="text-align:left">小写货币</th>
					<th style="text-align:left">大写货币</th>
				</tr>
			</thead>
			<tbody>

				<?php
				ini_set("display_errors", "on");
				require __DIR__ . '/MoneyConvertor.php';
				$money = array(
					'1.5',
					'50000',
					65001.05,
					101010101011.01,
					'1,999,999,999',
					'.5',
					'1000.00',
					'a.22'
				);

				$moneyObj = new MoneyConvertor();

				$output = '';

				foreach ($money as $key => $value) {
					$output .= "<tr>";
					$output .= "<td style=\"text-align:left\">" . ($key + 1) . "</td>";
					$output .= "<td style=\"text-align:left\">￥ " . $value . "</td>";
					$output .= "<td style=\"text-align:left\"><em style='font-style:normal;margin-right:7px;'>人民币(大写)</em> " . $moneyObj->convert($value) . "</td>";
					$output .= "</tr>";
				}

				echo $output;
				?>
			</tbody>
		</table>

	</body>
</html>