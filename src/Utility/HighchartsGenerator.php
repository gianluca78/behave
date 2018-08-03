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

    public function generateScatterPlot($title, array $series, $divId, $xAxisTitle, $yAxisTitle) {
        $xAxisTitle = $this->translator->trans($xAxisTitle, array(), 'chart');
        $yAxisTitle = $this->translator->trans($yAxisTitle, array(), 'chart');

        $ob = new Highchart();
        $ob->chart->renderTo($divId);
        $ob->title->text($title);

        $ob->xAxis->title(array('text'  => $xAxisTitle));
        $ob->xAxis->allowDecimals(false);
        //$ob->xAxis->plotLines($this->generatePlotLines($series));

        $ob->yAxis->title(array('text' => $yAxisTitle));
        $ob->yAxis->allowDecimals(false);

        $ob->tooltip->useHTML('true');

        $formatter = new Expr('function () {
                 return "<b>' . $xAxisTitle .': </b>" + this.x + "<br>" +
                        "<b>' . $yAxisTitle .': </b>" + this.y
             }');

        $ob->tooltip->formatter($formatter);

        /*
        $ob->lang->downloadJPEG($this->translator->trans('download_jpeg_image', array(), 'chart'));
        $ob->lang->downloadPDF($this->translator->trans('download_pdf_document', array(), 'chart'));
        $ob->lang->downloadPNG($this->translator->trans('download_png_image', array(), 'chart'));
        $ob->lang->downloadSVG($this->translator->trans('download_svg_vector_image', array(), 'chart'));
        $ob->lang->printChart($this->translator->trans('print_chart', array(), 'chart'));
        */

        $ob->series($series);

        return $ob;
    }
}