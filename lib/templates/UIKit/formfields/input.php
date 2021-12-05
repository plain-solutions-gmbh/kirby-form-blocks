<?php
    $fieldclass = "";
    if ($formfield->isFilled()) {
        $fieldclass = ($formfield->isValid()) ? " uk-form-success" : " uk-form-danger";
    }
?>

<input class="uk-input<?= $fieldclass ?>" type="<?= $formfield->inputtype() ?>" id="<?= $formfield->slug() ?>" name="<?= $formfield->slug() ?>" value="<?= $formfield->value() ?>" />