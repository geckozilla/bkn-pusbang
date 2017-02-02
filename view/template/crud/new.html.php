<?= $Form->formHeader(); ?>
<?php

use app\Util\Database;

$db = new Database();
$db->connect();
//echo $this->modelData->getEntity();
$autoData = $_SESSION[SESSION_ADMIN_AUTO_DATA];
$arrayNew = array();
$countAutoData;

if ($this->modelData == null) {
    foreach ($autoData as $valueData) {
        if ($valueData != 'id') {
            if (!in_array($valueData, $this->unsetAutoData)) {
                echo $Form->id($valueData)->title(lang('general.' . $valueData))->placeholder(lang('general.' . $valueData) . ' ....')->textbox();
            }
        }
    }
} else {
    $relation = $db->selectRelation($this->modelData->getEntity());
    $checkLov = array();
    foreach ($autoData as $valueData) {
        if ($valueData != 'id') {
            if (!in_array($valueData, $this->unsetAutoData)) {
//                echo $Form->id($valueData)->title(lang('general.' . $valueData))->placeholder(lang('general.' . $valueData) . ' ....')->textbox();
//                if (!empty($relation)) {
                $check = false;

                foreach ($relation as $val_relation) {
                    if ($valueData == $val_relation['COLUMN_NAME']) {
                        $check = true;
                        $db->select($val_relation['REFERENCED_TABLE_NAME'], "*", null);
                        $res_rel = $db->getResult();
                        $cv = convertJsonCombobox($res_rel, 'id', 'name', null);
                        $ex_val2 = explode('_', $valueData);
                        $langData = '';
                        if (isset($ex_val2[1])) {
                            if ($ex_val2[1] == 'id') {
                                $langData = $ex_val2[0];
                                array_push($checkLov, $langData);
                            }
                        } else {
                            $langData = $valueData;
                        }
                        echo $Form->id($valueData)->title(lang('general.' . $langData))
                                ->data($cv)->placeholder('Select')->combobox();
                    }
                }

//                } else {
                if ($check == false) {
//                    echo $valueData;
                    if (in_array($valueData, $checkLov)) {
                        
                    } else {
                        echo $Form->id($valueData)->title(lang('general.' . $valueData))->placeholder(lang('general.' . $valueData) . ' ....')->textbox();
                    }
                }
//                }
//            }
            }
        }
    }
}
?>
<?= $Form->formFooter($this->insertUrl); ?>
