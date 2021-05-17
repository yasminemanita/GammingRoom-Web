<?php

namespace App\Form;

use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use App\Form\CourType;

class CourTypeFlow extends FormFlow {

    protected function loadStepsConfig() {
        return [
            [
                'form_type' => CourType::class,
            ],
            [
                'form_type' => CourType::class,
                'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
                    return $estimatedCurrentStepNumber > 1 && !$flow->getFormData()->getDescription();
                },
            ],
            [

            ],
        ];
    }

}