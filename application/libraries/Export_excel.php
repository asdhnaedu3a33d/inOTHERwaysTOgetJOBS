<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "PHP_Excel/PHPExcel.php";
class Export_excel extends PHPExcel{
    public $data=NULL;
    public $size=12;
    public $size_header=12;
    public $filename="Data Export ";

    public $first_col = NULL;
    public $last_col = NULL;

    public $cell_iden = array();

    public $start = 1;
    private $char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    private $jml_char;

    private $custom_render_count = 0;

    public $default_border = array(
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        );

    public function __construct()
    {
        parent::__construct();
        $this->now = date('d-m-Y h:i:s');
        $this->nowdir = date('d-m-Y_h-i-s');

        $this->setActiveSheetIndex(0);
        $this->getActiveSheet()->setTitle('Data Export');
        $this->jml_char = strlen($this->char);

        $this->custom_render_count = $this->start;
    }

    public function get_cell_name($index=NULL)
    {
        if (is_null($index)) {
            return $this->cell_iden;
        }

        return $this->cell_iden[$index-1];
    }

    public function do_export($execute=TRUE)
    {
        if (is_null($this->data)) {
            echo "Data kosong";
            return false;
        }

        $this->setActiveSheetIndex(0);
        $this->getActiveSheet()->setTitle('Data Export');

        $borderStyleArray = $this->default_border;
        $this->getDefaultStyle()->getFont()->setSize($this->size);

        $no = $this->start;
        $this->first_col = $this->start;

        $loop = 0;
        $jml_char = $this->jml_char;
        $col_count=0;

        $first_position = $this->char[0].$no;
        $first_position_char = $this->char[0];
        foreach ($this->data->list_fields() as $field) {
            if ($jml_char==$col_count) {
                $loop++;
                $col_count=0;
            }

            $col_count++;

            $position = ($loop > 0?$this->char[$loop-1]:'');

            $this->cell_iden[] = $position.$this->char[$col_count-1];
            $position .= $this->char[$col_count-1].$no;

            $this->getActiveSheet()->setCellValue($position, $field);
        }
        $last_position = $position;
        $last_position_char = ($loop > 0?$this->char[$loop-1]:'').$this->char[$col_count-1];

        $this->getActiveSheet()->getStyle($first_position.':'.$last_position)->getFont()->setSize($this->size_header);
        $this->getActiveSheet()->getStyle($first_position.':'.$last_position)->getFont()->setBold(true);
        $this->getActiveSheet()->getStyle($first_position.':'.$last_position)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->getActiveSheet()->getStyle($first_position.':'.$last_position)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CFCFCF'))));
        $this->getActiveSheet()->getStyle($first_position.':'.$last_position)->applyFromArray($borderStyleArray);

        $loop = 0;
        foreach ($this->data->result() as $row) {
            $no++;
            $col_count=0;

            foreach ($row as $cell) {
                if ($jml_char==$col_count) {
                    $loop++;
                    $col_count=0;
                }

                $col_count++;

                $position = ($loop > 0?$this->char[$loop-1]:'');
                $position .= $this->char[$col_count-1].$no;
                $this->getActiveSheet()->setCellValueExplicit($position, $cell, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->getActiveSheet()->getStyle($position)->applyFromArray($borderStyleArray);
            }
        }

        $this->last_col = $this->getActiveSheet()->getHighestRow();

        if ($execute) {
            $this->execute();
            return false;
        }

        return $this;
    }

    public function execute()
    {
        $filename = $this->filename.$this->nowdir.'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this, 'Excel5');
        $objWriter->save('php://output');
    }

    public function create_header($header = array())
    {
        $borderStyleArray = $this->default_border;

        $this->first_col = $this->custom_render_count;
        $no = $this->custom_render_count;

        $this->cell_iden = array();

        $loop = 0;
        $jml_char = $this->jml_char;
        $col_count=0;

        $first_position = $this->char[0].$no;
        $first_position_char = $this->char[0];
        foreach ($header as $field) {
            if ($jml_char==$col_count) {
                $loop++;
                $col_count=0;
            }

            $col_count++;

            $position = ($loop > 0?$this->char[$loop-1]:'');

            $this->cell_iden[] = $position.$this->char[$col_count-1];
            $position .= $this->char[$col_count-1].$no;

            if (!is_null($field)) {
                $this->getActiveSheet()->setCellValue($position, $field);
            }
        }
        $last_position = $position;
        $last_position_char = ($loop > 0?$this->char[$loop-1]:'').$this->char[$col_count-1];

        $this->custom_render_count++;

        $this->getActiveSheet()->getStyle($first_position.':'.$last_position)->getFont()->setSize($this->size_header);
        $this->getActiveSheet()->getStyle($first_position.':'.$last_position)->getFont()->setBold(true);
        $this->getActiveSheet()->getStyle($first_position.':'.$last_position)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->getActiveSheet()->getStyle($first_position.':'.$last_position)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CFCFCF'))));
        $this->getActiveSheet()->getStyle($first_position.':'.$last_position)->applyFromArray($borderStyleArray);
    }

    public function set_row($row = array())
    {
        $borderStyleArray = $this->default_border;

        $no = $this->custom_render_count;
        $loop = 0;
        $jml_char = $this->jml_char;

        $col_count=0;
        foreach ($row as $cell) {
            if ($jml_char==$col_count) {
                $loop++;
                $col_count=0;
            }

            $col_count++;

            $position = ($loop > 0?$this->char[$loop-1]:'');
            $position .= $this->char[$col_count-1].$no;
            if (!is_null($cell)) {
                $this->getActiveSheet()->setCellValueExplicit($position, $cell, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->getActiveSheet()->getStyle($position)->applyFromArray($borderStyleArray);
            }
        }

        $this->last_col = $this->getActiveSheet()->getHighestRow();
        $this->custom_render_count++;
    }

    public function set_col($cell, $value, $row=NULL)
    {
        if (empty($row)) {
            $no = $this->custom_render_count;
        }

        $borderStyleArray = $this->default_border;

        $position = $this->get_cell_name($cell);
        $position .= $no;
        $this->getActiveSheet()->setCellValueExplicit($position, $value, PHPExcel_Cell_DataType::TYPE_STRING);
        $this->getActiveSheet()->getStyle($position)->applyFromArray($borderStyleArray);
    }

    public function new_row()
    {
        $this->custom_render_count++;
    }

    public function merge_cell($position_a, $position_b=NULL)
    {
        if (!is_null($position_b)) {
            $this->getActiveSheet()->mergeCells($position_a.':'.$position_b);
        }else{
            $this->getActiveSheet()->mergeCells($position_a);
        }

    }

    public function get_last_row()
    {
        return $this->custom_render_count-1;
    }

    public function set_border($position=NULL)
    {
        if (is_null($position)) {
            $f_char = key($this->cell_iden);
            end($this->cell_iden);
            $l_char = key($this->cell_iden);

            $position = $this->cell_iden[$f_char].$this->first_col.':'.$this->cell_iden[$l_char].$this->last_col;
        }
        $this->getActiveSheet()->getStyle($position)->applyFromArray($this->default_border);
    }

    public function set_readonly()
    {
        $this->getActiveSheet()->getProtection()->setSheet(true);
        $this->getActiveSheet()->getProtection()->setPassword("sirenbangda-s1r3nb4ngd4");
    }
}

?>
