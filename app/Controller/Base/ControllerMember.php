<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\Controller\Base;

/**
 * Description of ControllerMember
 *
 * @author sfandrianah
 */

use app\Util\Form;
use app\Util\DataTable;
use app\Util\Database;
use app\Util\Button;
//use app\Constant\IViewMemberConstant;
use app\Constant\IViewMemberConstant;
use app\Controller\Base\IController;
use app\Model\Auditrail;

class ControllerMember implements IController {

    public $indexUrl = '';
    public $editUrl = '';
    public $deleteUrl = '';
    public $updateUrl = '';
    public $insertUrl = '';
    public $viewIndex = IViewMemberConstant::CRUD_VIEW_INDEX;
    public $viewPath = '';
    public $viewList = '';
    public $viewCreate = '';
    public $viewEdit = '';
    public $urlDeleteCollection = '';
    public $search_by = '';
    public $select_entity = null;
    public $search_filter = array();
    public $where_list = null;
    public $search_list = null;
    public $join_list = null;
    public $group_by = null;
    public $modelData;
    public $orderBy = null;
    public $per_page = 5;
    public $auditrail = true;
    public $autoData = false;
    public $listAutoData = array();
    public $unsetAutoData = array();
    public $issetAutoData = array();
    public $result = '';
    public $changeValueNewEdit = array();
    public $get_data;
    public $list_parameter = false;
    public $param_body = array();
    public $admin_theme_url = "";

    public function __construct() {
        if (empty($this->search_filter)) {
            $this->search_filter = array(
                "code" => lang('general.code'),
                "name" => lang('general.name')
            );
        }
        $this->admin_theme_url = getAdminTheme();
    }

    function listWithParameter($value = false) {
        $this->list_parameter = $value;
    }

    public function setChangevalueNewEdit($value) {
        $this->changeValueNewEdit = $value;
    }

    public function setBreadCrumb($breadcrumb = array()) {
        setBreadCrumb($breadcrumb);
    }

    public function setTitle($title) {
        setTitle($title);
        setTitleBody($title);
    }

    public function setSubtitle($subtitle) {
        setSubtitleBody($subtitle);
    }

    public function index() {
        $Form = new Form();
        $Datatable = new DataTable();
        $data = $this->modelData;

//        $group = new SecurityGroup();
        include_once FILE_PATH($this->viewIndex);
    }

    public function save() {
        $db = new Database();
//        $group = new SecurityGroup();
        $data = $this->modelData;
        $db->connect();
        if ($this->auditrail == true) {
            $db->createAuditrail();
        }
        $datas = $data->setData($_POST);
        $db->insert($data->getEntity(), $datas);
        $rs = $db->getResult();
        if (is_numeric($rs[0])) {
            echo toastAlert("success", lang('general.title_insert_success'), lang('general.message_insert_success'));
            echo '<script>$(function(){postAjaxPagination()});</script>';
        } else {
            echo toastAlert("error", lang('general.title_insert_error'), lang('general.message_insert_error'));
            echo resultPageMsg('danger', lang('general.title_insert_error'), $rs[0]);
        }
    }

    public function update() {
        $id = $_POST['id'];
        $code = $_POST['code'];
        $name = $_POST['name'];
        $db = new Database();
        $data = $this->modelData;
//        $group = new SecurityGroup();

        $db->connect();
        if ($this->auditrail == true) {
            $db->updateAuditrail();
        }
        $datas = $data->setData($_POST);
        $db->update($data->getEntity(), $datas, $data->getId() . EQUAL . $id
        );
//        print_r($db->getResult());
        if ($db->getResult()[0] == 1) {
            echo toastAlert("success", lang('general.title_update_success'), lang('general.message_update_success'));
            echo '<script>$(function(){postAjaxPagination()});</script>';
        } else {
            echo toastAlert("error", lang('general.title_update_error'), lang('general.message_update_error') . '<br/>' .
                    json_encode($db->getResult()));
        }
    }

    public function listData() {
        $Form = new Form();
        $Datatable = new DataTable();
        $Button = new Button();
        $db = new Database();
//        $group = new SecurityGroup();
        $data = $this->modelData;
//        if ($_POST['per_page'] == "") {
        if ($_POST['per_page'] == "") {
            $Datatable->per_page = 10;
        } else {
            $Datatable->per_page = $_POST['per_page'];
        }

//        }
        $Datatable->urlDeleteCollection($this->urlDeleteCollection);
//        $search = $_POST['search_pagination'];
        $Datatable->searchFilter($this->search_filter);
        $Datatable->current_page = $_POST['current_page'];
        if ($_POST['current_page'] == '') {
            $Datatable->current_page = 1;
        }
        $search = $_POST['search_pagination'];
        if ($_POST['search_by'] == '') {
            $Datatable->search = 'code>' . $search;
        } else if ($_POST['search_by'] == 'null') {
            $Datatable->search = 'code>' . $search;
        } else {
            $Datatable->search = $_POST['search_by'] . '>' . $search;
        }
        if (getActionType(ACTION_TYPE_CREATE) != true) {
            $Datatable->createButton(false);
        }

        if ($this->orderBy != null) {
            $list_data = $Datatable->select_pagination($data, $data->getEntity(), $this->where_list, $this->join_list, $this->search_list, $this->orderBy, $this->select_entity);
        } else if ($this->where_list == null && $this->join_list == null) {
            $list_data = $Datatable->select_pagination($data, $data->getEntity());
        } else {
            $list_data = $Datatable->select_pagination($data, $data->getEntity(), $this->where_list, $this->join_list, $this->search_list, $this->orderBy, $this->select_entity,  $this->group_by);
        }

        $db->connect();
        $db->sql('SHOW COLUMNS FROM ' . $data->getEntity());
        $rs_column = $db->getResult();
//        print_r($rs_column);
        if ($this->autoData == true) {
            $this->listAutoData = $this->unsetDataModel($rs_column);
//            $this->listAutoData = $this->unsetDataModel($list_data['item']);
//            print_r($this->listAutoData);
            include_once FILE_PATH(IViewMemberConstant::CRUD_LIST_VIEW_INDEX);
        } else {
            include_once FILE_PATH($this->viewList);
        }
        echo '<script>$(function(){$(\'#form-search\').show()});</script>';
    }

//    public $dataModel = '';

    public function unsetDataModel($data) {
        $auditrail = new Auditrail();
//        print_r($auditrail);
        $_SESSION[SESSION_ADMIN_AUTO_DATA] = array();
        $createdOn = $auditrail->getCreatedOn();
        $createdBy = $auditrail->getCreatedByUsername();
        $modifiedOn = $auditrail->getModifiedOn();
        $modifiedBy = $auditrail->getModifiedByUsername();
        $status = $auditrail->getStatus();
//        print_r($data);
        $datas = array();
        if (!empty($data)) {
            foreach ($data as $value) {
                if(!in_array($value['Field'], array($createdOn,$createdBy,$modifiedOn,$modifiedBy,$status))){
                    $datas[] = $value['Field'];
                }
                
//            foreach (array_keys($data) as $key) {
//            echo $data[$key]['created_on'];
//                $rs_column[0]
//                unset($data[$key][$createdOn]);
//                unset($data[$key][$createdBy]);
//                unset($data[$key][$modifiedOn]);
//                unset($data[$key][$modifiedBy]);
//                unset($data[$key][$status]);
            }
//            $_SESSION[SESSION_ADMIN_AUTO_DATA] = array_keys((array) $data[0]);
//            return array_keys((array) $data[0]);
            $_SESSION[SESSION_ADMIN_AUTO_DATA] = $datas;
            return $datas;
        } else {
            return array();
        }
//        print_r(array_keys((array) $data[0]));
    }

    public function create() {
        $Form = new Form();
        if ($this->autoData == true) {
            include_once FILE_PATH(IViewMemberConstant::CRUD_NEW_VIEW_INDEX);
        } else {
            include_once FILE_PATH($this->viewCreate);
        }
        echo '<script>$(function(){$(\'#form-search\').hide()});</script>';
    }

    public function getData($id) {
        $data = $this->modelData;
        $db = new Database();
        $db->connect();
        $select_data = $db->selectByID($data, $data->getId() . EQUAL . $id);
        $this->get_data = $select_data[0];
//        return $select_data;
    }

    public function edit() {
        $id = $_POST['id'];
        $Form = new Form();
        $db = new Database();
        $db->connect();
        $Button = new Button();
//        $group = new SecurityGroup();
//        $this->getData($id);
        $data = $this->modelData;
//        $db = new Database();
//        $db->connect();
        $select_data = $db->selectByID($data, $data->getId() . EQUAL . $id);
        $this->get_data = $select_data[0];

        $get_data = $this->get_data;
        if ($this->autoData == true) {
            include_once FILE_PATH(IViewMemberConstant::CRUD_EDIT_VIEW_INDEX);
        } else {
            include_once FILE_PATH($this->viewEdit);
        }
        echo '<script>$(function(){$(\'#form-search\').hide()});</script>';
    }

    public function delete() {
        $id = $_POST['id'];
        $Form = new Form();
        $db = new Database();
//        $group = new SecurityGroup();
        $data = $this->modelData;
        $db->connect();
        $get_data = $db->delete($data->getEntity(), $data->getId() . EQUAL . $id);
        echo $get_data;
//        print_r($get_group);
    }

    public function deleteCollection() {
        $id = $_POST['id'];
        $Form = new Form();
        $db = new Database();
//        $group = new SecurityGroup();
        $data = $this->modelData;
        $db->connect();
        $where = $data->getId() . " IN (" . $id . ")";
//        $delete = 'DELETE FROM ' . $data->getEntity() . ' WHERE ' . $where;
//        print_r($delete);
        $delete_data = $db->delete($data->getEntity(), $where);
//        $get_data = $db->delete($data->getEntity(), $data->getId() . EQUAL . $id);
        echo $delete_data;
//        print_r($get_group);
    }

    public function setAutoCrud() {
        $this->autoViewURL();
        $this->autoViewPath();
    }

    public function autoViewURL() {
        $this->admin_theme_url = getAdminTheme();
        setCreateURL(URL($this->indexUrl . '/create'));
        setDatatableURL(URL($this->indexUrl . '/list'));
        $this->editUrl = URL($this->indexUrl . '/edit');
        $this->deleteUrl = URL($this->indexUrl . '/delete');
        $this->insertUrl = URL($this->indexUrl . '/save');
        $this->updateUrl = URL($this->indexUrl . '/update');
        $this->urlDeleteCollection = URL($this->indexUrl . '/deleteCollection');
    }

    public function autoViewPath() {
        $this->viewList = $this->viewPath . '/list.html.php';
        $this->viewCreate = $this->viewPath . '/new.html.php';
        $this->viewEdit = $this->viewPath . '/edit.html.php';
    }


}
