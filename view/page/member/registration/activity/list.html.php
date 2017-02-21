<?php

use app\Model\MasterWaitingList;
use app\Model\MasterUserAssignment;
use app\Util\Database;


$db = new Database();
$waitingList = new MasterWaitingList();
$userAssignment =  new MasterUserAssignment();
$db->connect();
?>
<div class="col-md-12">
    <?php
//    $Datatable->createButton(false);
    $Datatable->deleteCollection(false);
    $Datatable->setPageTable('pageListActivity');

//    $Datatable->styleHeader(array("text-align:center;"));
    $Datatable->styleColumn(array("text-align:center;width:5%;", "", "", "", "", "text-align:center;width:10px;", "text-align:center;width:80px;"));
    $Datatable->header(array(lang("general.no"), lang("member.activity_type"),
        lang("member.alumni"),
        lang("member.budget_of_type"),
        lang("member.execution_time"),
        lang("member.participant"),
        lang("general.status"),
    ));
    $no = $list_data['from'];

    foreach ($list_data['item'] as $value) {

//        $detailSubject = '<a href="javascript:void(0)" onclick="postAjaxEdit(\'' . URL('member/activity-agenda/activity/view') . '\',\'id=' . $value[$data->getId()] . '\')">' . subMonth($value[$data->getStartActivity()]) . ' - ' . subMonth($value[$data->getEndActivity()]) . '</a>';
        $detailSubject = '' . subMonth($value[$data->getStartActivity()]) . ' - ' . subMonth($value[$data->getEndActivity()]) . '';
        $rs_waiting_list = $db->selectByID($waitingList, $waitingList->getActivityId() . "='" . $value[$data->getId()] . "' AND " . $waitingList->getUserMainId() . EQUAL . "'" . $rs_user_main[0][$userMain->getId()] . "'");
        
        $db->sql("SELECT COUNT(".$userAssignment->getId().") as count FROM ".$userAssignment->getEntity()." WHERE ".$userAssignment->getActivity_id().EQUAL.$value[$data->getId()]);
        $rs_assign = $db->getResult();
        $status = "";
//        print_r($rs_assign);
        if (!empty($rs_waiting_list)) {
            $status = lang('member.registered');
        } else {
            if ($value[$data->getStatus()] == 1) {
                $status = lang('member.full');
            } else if ($value[$data->getStatus()] == 0 || $value[$data->getStatus()] == null || $value[$data->getStatus()] == "") {
                $status = '<a href="javascript:void(0)" alert-title="'.lang('member.register').'" 
                alert-message="'.lang('member.notif_registered') . $value[$data->getSubjectName()] . '"
                alert-button-title="'.lang('member.yes').'"
                onclick="postAjaxByAlertManual(this,\'' . URL('member/registration/activity/approve') . '\',\'id=' . $value[$data->getId()] . '\')">'.lang('member.register').'</a>';
            } else {
                $status = lang('member.full');
            }
        }
        $Datatable->body(array($no,
            $value[$data->getSubjectName()],
            $value[$data->getGeneration()],
            $value[$data->getBudgetTypeName()],
            $detailSubject,
            $rs_assign[0]['count']."/".$value[$data->getQuota()],
            $status));
        $no += 1;
    }

    echo $Datatable->show();
    ?>
</div>
<script>
    $(function () {
        $('#list_search_by-pageListActivity').attr("class", "input-sm input-xsmall input-inline");
        $('#search_pagination-pageListActivity').attr("class", "form-control");
        $('#search_pagination-pageListActivity').attr("style", "height:18px;");
        $('.pagination').attr("style", "margin-top:0");
    });
</script>
<!--<script>location.reload(true);</script>-->
