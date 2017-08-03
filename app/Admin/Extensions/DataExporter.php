<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

class DataExporter extends AbstractExporter
{
    public function export()
    {

//        echo '---------';
//        echo memory_get_usage();
//        echo '---------';
//


        $filename = date('Y-m-d H:i:s').'.csv';

        $lables = [];
        foreach($this->grid->columns() as $column){
            $lables[] = $column->getLabel();
        }


        $model = $this->grid->model()->eloquent();
        $data = $model->getAttribute('id');
        dd($data);

        Excel::create('xlx', function($excel) use ($grid) {

            // Set the title
            $excel->setTitle('Our new awesome title');

            // Chain the setters
            $excel->setCreator('Maatwebsite')
                ->setCompany('Maatwebsite');

            // Call them separately
            $excel->setDescription('A demonstration to change the file properties');

            $excel->sheet('First sheet', function($sheet) use ($grid) {
                $names = array_unique($grid->columnNames);
                $grid->model()->eloquent()->each(function ($row, $key) use ($names,$sheet) {
//             print($item);
                    $temprow = [];
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

                    $sheet->appendRow($temprow);
                });


            });

        })->export('xls');
        exit;




//        $this->grid->model()->usePaginate(true);
//        $this->grid->model()->Paginate(15);
        $names = array_unique($this->grid->columnNames);
        $output = $this->grid->model()->eloquent()->each(function ($row, $key) use ($names,$output) {
//             print($item);
            $temprow = [];
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
        });
////        foreach ($data as $value){
////            print_r($value);
////        }
//        dd('');
//        echo '---------|||||||';
//        echo memory_get_usage();
//        echo '---------|||||||';
        $headers = [
            'Content-Encoding'    => 'UTF-8',
            'Content-Type'        => 'text/csv;charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        echo "\xEF\xBB\xBF";
        response(rtrim($output, "\n"), 200, $headers)->send();

        dd('');
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

        echo '---------|||||||';
        echo memory_get_usage();
        echo '---------|||||||';
        dd('');
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