<?php

namespace App\Form\Widget;

use Symfony\Component\Form\FormBuilderInterface;

interface WidgetInterface {

    public function addField(FormBuilderInterface $formBuilderInterface);
}