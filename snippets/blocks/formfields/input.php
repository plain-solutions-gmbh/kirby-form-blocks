
<input
    type="<?= $formfield->inputtype() ?>"
    id="<?= $formfield->slug() ?>"
    name="<?= $formfield->slug() ?>"
    value="<?= $formfield->value() ?>"
    <?= e($formfield->required()->isTrue(), "required") ?>
    />
