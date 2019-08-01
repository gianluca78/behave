<?php
namespace App\Utility;

use Ob\HighchartsBundle\Highcharts\Highchart;


use Symfony\Component\Translation\Translator;
use Zend\Json\Expr;

class HighchartsGenerator {

    private $translator;

    public function __construct($translator) {
        $this->translator = $translator;
    }

    public function generateScatterPlot($title, array $series, $divId, $xAxisTitle, $yAxisTitle, $plotLine = false) {
        $xAxisTitle = $this->translator->trans($xAxisTitle, array(), 'chart');
        $yAxisTitle = $this->translator->trans($yAxisTitle, array(), 'chart');

        $ob = new Highchart();
        $ob->chart->renderTo($divId);
        $ob->title->text($title);

        $ob->xAxis->title(array('text'  => $xAxisTitle));
        //$ob->xAxis->categories(array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'));
        $ob->xAxis->allowDecimals(false);

        if($plotLine) {
            $ob->xAxis->plotLines($this->generatePlotLines($series));
        }

        $ob->yAxis->title(array('text' => $yAxisTitle));
        $ob->yAxis->allowDecimals(false);

        $ob->tooltip->useHTML('true');

        $formatter = new Expr('function () {
                 return "<b>' . $xAxisTitle .': </b>" + this.x + "<br>" +
                        "<b>' . $yAxisTitle .': </b>" + this.y
             }');

        $ob->tooltip->formatter($formatter);

        $ob->lang->downloadJPEG($this->translator->trans('download_jpeg_image', array(), 'chart'));
        $ob->lang->downloadPDF($this->translator->trans('download_pdf_document', array(), 'chart'));
        $ob->lang->downloadCSV($this->translator->trans('download_csv_document', array(), 'chart'));
        $ob->lang->downloadXLS($this->translator->trans('download_xls_document', array(), 'chart'));
        $ob->lang->downloadPNG($this->translator->trans('download_png_image', array(), 'chart'));
        $ob->lang->downloadSVG($this->translator->trans('download_svg_vector_image', array(), 'chart'));
        $ob->lang->printChart($this->translator->trans('print_chart', array(), 'chart'));
        $ob->lang->openInCloud($this->translator->trans('open_in_cloud', array(), 'chart'));
        $ob->lang->viewData($this->translator->trans('view_data', array(), 'chart'));

        $ob->series($series);

        return $ob;
    }

    private function generatePlotLines(array $series)
    {
        $plotLines = array();

        $numberOfSeries = count($series);

        for($i=0; $i<=$numberOfSeries-1; $i++) {

            $nextSeriesIndex = $i + 1;

            if(array_key_exists($nextSeriesIndex, $series)) {

                $numberOfObservationFirstDataSet = array_pop($series[$i]['data']);
                $numberOfObservationFirstDataSet = $numberOfObservationFirstDataSet['x'];

                $xCoordinate = ($numberOfObservationFirstDataSet + $numberOfObservationFirstDataSet + 1) / 2;

                $plotLines[] = array(
                    'color' => 'red',
                    'dashStyle' => 'longdashdot',
                    'value' => $xCoordinate,
                    'width' => '2'
                );
            }
        }

        return $plotLines;
    }
}