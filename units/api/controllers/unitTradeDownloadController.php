 <?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require $_SERVER['DOCUMENT_ROOT'] . "/KPIGraphs/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
ini_set('memory_limit', '1024M'); // or you could use 1G
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

class unitTradeDownloadController
{

    function unit_trade_stats($sort)
    {
        $database = new database();
        $connection = $database -> connection();
        $unit_trade = array();
        $status_sql = "";
        
        $in_statement = str_repeat("?,", count($sort -> divisions) - 1) . "?";
        
        $in_statement2 = str_repeat("?,", count($sort -> companies) - 1) . "?";
        
        $in_statement3 = str_repeat("?,", count($sort -> terminals) - 1) . "?";

        $parameters = array();

        if($sort -> divisions){
            foreach($sort -> divisions as $div){
                array_push($parameters,  $div);
            }
        }
        
        if($sort -> companies){
            foreach($sort -> companies as $company){
                array_push($parameters, $company);
            }
        }
        
        if($sort -> terminals){
            foreach($sort -> terminals as $terminal){
                array_push($parameters, $terminal);
            }
        }

        if($sort -> terminals and $sort -> divisions)
        {
            $company_sql = " and {REDACTED} in (" . $in_statement . ") and dd.{REDACTED} in (" . $in_statement2 . ") and substring({REDACTED},1,3) in (" . $in_statement3 . ")" ;
        }
        else
        {
            $company_sql = " ";
        }

        if($sort -> status) {
            if ($sort -> status == "all") {
                $status_sql = "";
            }
            elseif ($sort -> status == "1") {
                array_push($parameters, 1);
                $status_sql = "where {REDACTED} in ( ? )";
            }
            elseif ($sort -> status == "2") {
                array_push($parameters, 2);
                $status_sql = "where {REDACTED} in ( ? )";
            }
            elseif ($sort -> status == "3") {
                array_push($parameters, 3);
                $status_sql = "where {REDACTED} in ( ? )";
            } 
            
        }

        $sql = "select * from
                (
                    select 
                        unnum, 
                        unyear, 
                        unmake, 
                        serial_number, 
                        current_odom, 
                        cast(cast(odom_timestamp as date) as varchar) 'odom_timestamp',
                        (
                            SELECT case when sum({REDACTED}) is null then 0 else sum({REDACTED}) end 
                            FROM {REDACTED}.{REDACTED} 
                            where date between cast(dateadd(day, -9, getdate()) as date) and cast(dateadd(day, -3, getdate()) as date) and {REDACTED} = {REDACTED}
                        ) 'last_bus_week',
                        {REDACTED},
                        {REDACTED},
                        cast(cast({REDACTED} as date) as varchar) 'projected_trade_date',
                        case 
                            when ({REDACTED}) > 500000 then 3
                            when (({REDACTED}) + ({REDACTED} * 26)) < 500000 then 1
                            when (({REDACTED}) + ({REDACTED} * 26)) >= 500000 then 2
                        end 'status'
                    from {REDACTED} join {REDACTED} dd on {REDACTED} = dd.{REDACTED}
                    where {REDACTED} != 'D' and {REDACTED} = 'TRACTOR' and len{REDACTED} < 6 " . $company_sql . "
                ) e
                " . $status_sql . "
                order by cast({REDACTED} as int) desc
                ";

        $results = sqlsrv_query($connection, $sql, $parameters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet -> setAutoFilter('A1:J1');
        $sheet -> getStyle('A1:J1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('df1b16');
        $sheet -> getStyle('A1:J1')
            ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);

        $row = 1;
        if($results)
        {
            $sheet->setCellValue('A' . (string)$row, "Unit Number");
            $sheet->setCellValue('B' . (string)$row, "Model Year");
            $sheet->setCellValue('C' . (string)$row, "Make");
            $sheet->setCellValue('D' . (string)$row, "VIN");
            $sheet->setCellValue('E' . (string)$row, "Odometer");
            $sheet->setCellValue('F' . (string)$row, "Odom Read On");
            $sheet->setCellValue('G' . (string)$row, "Last Week Miles");
            $sheet->setCellValue('H' . (string)$row, "Past 4 Weeks Avg. Mi.");
            $sheet->setCellValue('I' . (string)$row, "Lifetime Avg. Mi.");
            $sheet->setCellValue('J' . (string)$row, "Est. 500k Mi. Date");

            $row = 2;
            while($status = sqlsrv_fetch_object($results))
            {
                
                $sheet->setCellValue('A' . (string)$row, $status -> unnum);
                $sheet->setCellValue('B' . (string)$row, $status -> unyear);
                $sheet->setCellValue('C' . (string)$row, $status -> unmake);
                $sheet->setCellValue('D' . (string)$row, $status -> serial_number);
                $sheet->setCellValue('E' . (string)$row, $status -> current_odom);
                $sheet->setCellValue('F' . (string)$row, $status -> odom_timestamp);
                $sheet->setCellValue('G' . (string)$row, $status -> last_bus_week);
                $sheet->setCellValue('H' . (string)$row, $status -> average_4_week_miles);
                $sheet->setCellValue('I' . (string)$row, $status -> average_lifetime_miles);
                $sheet->setCellValue('J' . (string)$row, $status -> projected_trade_date);

                $row = $row + 1;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save('C:/Apache24/htdocs/KPIGraphs/units/downloads/hello_world.xlsx');

        //$this -> make_download('C:/Apache24/htdocs/KPIGraphs/units/downloads/hello_world.xlsx');

    }



    function validate_sort($sort, $required_fields)
    {
        $missing_fields = array();

        foreach($required_fields as $field)
        {

            if(!property_exists($sort, $field))
            {
                array_push($missing_fields, $field);
            }
        }
        
        if(count($missing_fields) > 0)
        {
            return new error_response('500', 'Request could not be processed, missing ' . json_encode($missing_fields) . ' variable(s) in the sort object.');
        }
        else
        {
            return true;
        }


    }

    //Implementation of the POST request
    function post()
    {

        $required_fields = array('divisions', 'terminals', 'companies');
        if(!empty($_POST['sort']))
        {
            try
            {
                $sort = json_decode($_POST['sort']);
                //echo var_dump($sort);
                $validator = $this -> validate_sort($sort, $required_fields);
                if($validator === true)
                {
                    $transactions = $this -> unit_trade_stats($sort);
                    return $transactions;
                }
                else
                {
                    http_response_code(500);
                    echo json_encode($validator);
                }
            }
            catch (Exception $e)
            { 
                $error = new error_response('500', $e);
                http_response_code(500);
                return json_encode($error);
            }

        }
        else
        {
            $error = new error_response('500', 'Request could not be processed, please include a "sort" JSON object');
            http_response_code(500);
            return json_encode($error);
        }

    }
}

