<select name="<?= $formfield->slug() ?>" id="input-<?= $formfield->slug() ?>-<?= $formfield->id() ?>" <?= e($formfield->required()->isTrue(), " required") ?> <?= e($formfield->isInvalid(), "invalid") ?> <?= e($formfield->isInvalid(), "aria-describedby='input-" . $formfield->slug() . '-' . $formfield->id() . "-error-message'") ?>>

    <option value="" disabled <?= e($formfield->value() == "", ' selected') ?>><?= $formfield->placeholder() ?></option>

    <?php foreach ($formfield->options() as $option) : ?>

        <option value="<?= $option->slug() ?>" <?= e($option->selected()->isTrue(), " selected") ?>>
            <?= $option->label() ?>
        </option>
    <?php endforeach ?>

</select>
