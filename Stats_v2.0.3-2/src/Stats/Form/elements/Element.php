<?php

namespace Stats\Form\elements;

use Stats\Form\WindowForm;

interface Element
{

    public function getName(): String;

    public function getForm(): WindowForm;
}