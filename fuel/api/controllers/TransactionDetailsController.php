<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class TransactionDetailsController
{

    function get_transaction_details($id) {

        if ($id != null) {
            $database = new database();
            $connection = $database -> connection();

            $sql = "
                SELECT  *,
                substring({REDACTED}, 4, 3) 'division',
                ft.{REDACTED},
                right('0000' + cast({REDACTED} as varchar), 4) 'time',
                case when {REDACTED} > 0 then 'Y' else 'N' end 'cash_advance_flag',
                case when (({REDACTED} != 'T' and fc.{REDACTED} is null) or {REDACTED} != 'Y') then 'N'
                when ({REDACTED} = 'T' or {REDACTED} = 'Y') then 'Y'
                else 'N'
                end 'in_network'
                FROM {REDACTED} ft
                join {REDACTED} dd on dd.{REDACTED} = substring({REDACTED}, 4, 3)
                left join {REDACTED}  fc on substring(ft.{REDACTED},1,2) = fc.{REDACTED}
                where {REDACTED} = " . $id;

            $results = sqlsrv_query($connection, $sql);

            if ($results) {
                $details = sqlsrv_fetch_object($results);
                return $details;
            }
            else
            {
                $error = new error_response('500', 'Request could not be processed, please check the API\'s input for errors.');
                http_response_code(500);
                return $error;
            }

        }

    }

    function post() {

        
        if (!empty($_POST['id'])) {
            try {
                $id = json_decode($_POST['id']);

                $transaction = $this -> get_transaction_details($id);
                echo json_encode($transaction);
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
            $error = new error_response('500', 'Request could not be processed, please include a "id" JSON object');
            http_response_code(500);
            return json_encode($error);
        }
    }
}