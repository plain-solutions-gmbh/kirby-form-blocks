<?php
    $fieldclass="";
    if ($formfield->isFilled()) {
        $fieldclass = ($formfield->isValid()) ? " uk-form-success" : " uk-form-danger";
    }
?>

<div class="form-block-field-options" uk-grid>

    <?php foreach ($formfield->options() as $option) : ?>

        <div class="form-block-field-option uk-width-1-1 uk-width-<?= $formfield->columns('dash') ?>@m<?= $fieldclass ?>">
            <label for="<?= $option->slug() ?>">
                <input class="uk-checkbox" type="checkbox" id="<?= $option->slug() ?>" name="<?= $option->slug() ?>" value="<?= $option->slug() ?>" <?= e($option->selected()->isTrue(), " checked") ?><?= e($formfield->required()->isTrue(), " required") ?>>
                <?= $option->label() ?>
            </label>
        </div>

    <?php endforeach ?>

</div>
