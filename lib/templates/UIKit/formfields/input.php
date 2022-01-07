<?php
    $fieldclass = "";
    if ($formfield->isFilled()) {
        $fieldclass = ($formfield->isValid()) ? " uk-form-success" : " uk-form-danger";
    }
?>

<input class="uk-input<?= $fieldclass ?>" type="<?= $formfield->inputtype() ?>" id="<?= $formfield->slug() ?>" name="<?= $formfield->slug() ?>" value="<?= $formfield->value() ?>" <?= e($formfield->required()->isTrue(), " required") ?> <?= e($formfield->isInvalid(), "invalid") ?> <?= e($formfield->isInvalid(), "aria-describedby='" . $formfield->slug() . "-error-message'") ?> />
