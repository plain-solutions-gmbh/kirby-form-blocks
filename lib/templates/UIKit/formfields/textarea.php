<?php
$fieldclass = "";
if ($formfield->isFilled()) {
    $fieldclass = ($formfield->isValid()) ? " uk-form-success" : " uk-form-danger";
}
?>

<textarea class="uk-textarea<?= $fieldclass ?>" id="<?= $formfield->slug() ?>" name="<?= $formfield->slug() ?>" rows="<?= $formfield->row() ?>" placeholder="<?= $formfield->placeholder() ?>" <?= e($formfield->required()->isTrue(), " required") ?>><?= $formfield->value() ?></textarea>
