<?php
$fieldclass = "";
if ($formfield->isFilled()) {
    $fieldclass = ($formfield->isValid()) ? " uk-form-success" : " uk-form-danger";
}
?>

<select class="uk-select<?= $fieldclass ?>" name="<?= $formfield->slug() ?>" id="<?= $formfield->slug() ?>" <?= e($formfield->required()->isTrue(), " required") ?> <?= e($formfield->isInvalid(), "invalid") ?> <?= e($formfield->isInvalid(), "aria-describedby='" . $formfield->slug() . "-error-message'") ?>>

    <option value="" disabled <?= e($formfield->value() == "", ' selected') ?>><?= $formfield->placeholder() ?></option>

    <?php foreach ($formfield->options() as $option) : ?>

        <option value="<?= $option->slug() ?>" <?= e($option->selected()->isTrue(), " selected") ?>>
            <?= $option->label() ?>
        </option>
    <?php endforeach ?>

</select>
