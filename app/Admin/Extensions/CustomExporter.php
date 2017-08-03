<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Illuminate\Support\Arr;

class CustomExporter extends AbstractExporter
{
    public function export()
    {


        $filename = date('Y-m-d H:i:s').'.csv';

        $lables = [];
        foreach($this->grid->columns() as $column){
            $lables[] = $column->getLabel();
        }

        $output = implode(',', $lables)."\n";

        $this->grid->build();
        foreach($this->grid->rows() as $row){
            $temprow = [];
            $names = array_unique($this->grid->columnNames);

            foreach($names as $name){
                if($name=='__actions__'){
                    continue;
                }

                if( $name=='order_sn' or   $name=='bank_account' or $name=='trans_id' ){
                    $temprow[] =  "\t".$row->column($name);
                }elseif(  $name=='store_name'){
                    $str =   str_replace(',','',$row->column($name));
                    $temprow[] = str_replace('"','',$str);
                }
                elseif ($name=='sku.goods_name'){
                    $str = strip_tags($row->column($name));
                    $str =   str_replace(',','',$str);
                    $temprow[] = str_replace('"','',$str);

                }else{
                    $temprow[] =  $row->column($name);
                }
            }

            $output .= implode(',', array_dot($temprow))."\n";
        }


/*        foreach ($data as $row) {
            $temprow = [];
            foreach ($titles as $title_key=>$title){
                if( isset($row[$title])){
                    $temprow[$title] = $row[$title];
                }else{
                    $temprow[$title] = ' ';
                }
            }
            $output .= implode(',', array_dot($temprow))."\n";
        }*/

        $headers = [
            'Content-Encoding'    => 'UTF-8',
            'Content-Type'        => 'text/csv;charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        echo "\xEF\xBB\xBF";
        response(rtrim($output, "\n"), 200, $headers)->send();

        exit;
    }


    /**
     * Remove indexed array.
     *
     * @param array $row
     *
     * @return array
     */
    protected function sanitize(array $row)
    {
        return collect($row)->reject(function ($val, $_) {
            return is_array($val) && !Arr::isAssoc($val);
        })->toArray();
    }
}