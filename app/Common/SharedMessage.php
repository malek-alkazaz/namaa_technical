<?php

namespace App\Common;

use Illuminate\Support\Facades\Log;

class SharedMessage
{
    public $data;

    public $resultCode;

    public $status;

    public $message;

    public $exception;

    public $statusCode;

    public $paginationData;

    public function __construct($message, $data, $status, $exception, $statusCode = null ,$paginationData = null)
    {
        $this->message = $message;
        $this->data = $data;
        $this->status = $status;
        $this->exception = $exception;
        $this->statusCode = $statusCode;
        if($paginationData)
            $this->paginationData = $this->formatPagination($paginationData);
    }

    private function formatPagination($data) : array
    {
        if(count($data)) {
            $paginated_arr = $data->toArray();
            return $paginateData = [
                'currentPage' => $paginated_arr['current_page'],
                'from' => $paginated_arr['from'],
                'to' => $paginated_arr['to'],
                'total' => $paginated_arr['total'],                 //total number of items across all pages.
                'perPage' => $paginated_arr['per_page'],            //number of items per page
            ];
        }
        return [];
    }
}
