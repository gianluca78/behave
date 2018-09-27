<?php
namespace App\Utility;

use Symfony\Component\Translation\TranslatorInterface;

class EffectSizeChecker {

    private $translator;
    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getResultMessage($analysesValue)
    {
        switch($analysesValue->outMonte) {
            case 'AvsB+trendB-trendA':
                $analysisValue = $analysesValue->TAU_U_Analysis[10]->AvsBTrendBTrendA;
                break;

            case 'AvsB+trendB':
                $analysisValue = $analysesValue->TAU_U_Analysis[10]->AvsBTrendB;
                break;

            case 'AvsB':
                $analysisValue = $analysesValue->PartitionsOfMatrix[10]->AvsB;
                break;

            case 'Allison & Gorman':
                $analysisValue = $analysesValue->R->value;
                break;
        }

        $sign = ($analysisValue > 0) ? $this->translator->trans('increase', array(), 'r_analysis')
            : $this->translator->trans('decrease', array(), 'r_analysis');

        if ($this->hasTauNotEffect($analysisValue)) {
            return $this->translator->trans('treatment_no_effect', array(), 'r_analysis');
        }

        if ($this->hasTauSmallEffect($analysisValue)) {
            $small = $this->translator->trans('small', array(), 'r_analysis');
            $message = sprintf($this->translator->trans('treatment_effect', array(), 'r_analysis'), $small, $sign);

            return $message;
        }

        if ($this->hasTauMediumEffect($analysisValue)) {
            $small = $this->translator->trans('medium', array(), 'r_analysis');
            $message = sprintf($this->translator->trans('treatment_effect', array(), 'r_analysis'), $small, $sign);

            return $message;
        }

        if ($this->hasTauLargeEffect($analysisValue)) {
            $small = $this->translator->trans('large', array(), 'r_analysis');
            $message = sprintf($this->translator->trans('treatment_effect', array(), 'r_analysis'), $small, $sign);

            return $message;
        }
    }

    private function hasTauNotEffect($analysisValue)
    {
        return ($analysisValue <= 0.1 && $analysisValue >= -0.1) ? true : false;
    }

    private function hasTauSmallEffect($analysisValue)
    {
        $analysisValue = abs($analysisValue);

        return ($analysisValue >= 0.1 && $analysisValue <= 0.3) ? true : false;
    }

    private function hasTauMediumEffect($analysisValue)
    {
        $analysisValue = abs($analysisValue);

        return ($analysisValue >= 0.3 && $analysisValue <= 0.5) ? true : false;
    }

    private function hasTauLargeEffect($analysisValue)
    {
        $analysisValue = abs($analysisValue);

        return ($analysisValue >= 0.5) ? true : false;
    }

} 