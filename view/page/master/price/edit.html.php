<?= $Form->formHeader(); ?>

<?php echo $Form->id('code')->title(lang('general.code'))->value($get_data[$data->getCode()])->placeholder(lang('general.code') . ' ....')->textbox(); ?>
<?php echo $Form->id('name')->title(lang('general.name'))->value($get_data[$data->getName()])->placeholder(lang('general.name') . ' ....')->textbox(); ?>
<?php echo $Form->id('amount')->title(lang('master.amount'))->value($get_data[$data->getAmount()])->placeholder(lang('master.amount') . ' ....')->textbox(); ?>

<input type="hidden" id="id" name="id" value="<?= $_POST['id']; ?>"/>

<?= $Form->formFooter($this->updateUrl); ?>
