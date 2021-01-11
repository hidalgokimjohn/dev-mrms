<?php

namespace app;
use DatabaseTravelOrder;

class TravelOrder
{
    public function connectDatabase_travelOrder()
    {
        $database = DatabaseTravelOrder::getInstance();
        $mysql = $database->getConnection();

        return $mysql;
    }

    public function to_list($month,$year)
    {
        $mysql = $this->connectDatabase_travelOrder();
        $q = "SELECT
				kcpis.travelorders.dateReceived,
				kcpis.travelorders.idNumber,
				pis.employee_info.lastname,
				pis.employee_info.firstname,
				kcpis.travelorders.dateFrom,
				kcpis.travelorders.dateTo,
				kcpis.travelorders.place,
				kcpis.travelorders.purpose,
				kcpis.travelorders.`status`
				FROM
					kcpis.`travelorders`
				INNER JOIN `pis`.employee_info ON `pis`.employee_info.id_number = `kcpis`.`travelorders`.idNumber
				WHERE
					`pis`.employee_info.position_id IN (103, 254, 202, 79, 112, 145)
				AND MONTH (
					`kcpis`.travelorders.dateFrom
				) = '$month' AND YEAR(kcpis.travelorders.dateFrom) = '$year'
				ORDER BY
					UNIX_TIMESTAMP(
						`kcpis`.travelorders.dateReceived
					) DESC";
        $result = $mysql->query($q) or die($mysql);
        if($result->num_rows>0){
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }else{
            return false;
        }

    }
}