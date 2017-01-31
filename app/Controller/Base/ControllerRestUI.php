<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\Controller\Base;

/**
 * Description of Controller
 *
 * @author sfandrianah
 */
use app\Util\Form;
use app\Util\DataTable;
use app\Util\Database;
use app\Util\Button;
use app\Constant\IViewConstant;
use app\Constant\IRestCommandConstant;
use app\Controller\Base\IController;
use app\Util\RestClient\TripoinRestClient;

abstract class ControllerRestUI implements IController{

    //put your code here

    public $indexUrl = '';
    public $editUrl = '';
    public $deleteUrl = '';
    public $updateUrl = '';
    public $insertUrl = '';
    public $viewIndex = IViewConstant::CRUD_VIEW_INDEX;
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
    public $modelData;
    public $orderBy = null;
    public $per_page = 5;
    public $auditrail = true;
    public $restURL = '';
    public $url_api = URL_REST . IRestCommandConstant::API . SLASH . IRestCommandConstant::VERSI . SLASH;

    public function __construct() {
        if (empty($this->search_filter)) {
            $this->search_filter = array(
                "code" => lang('general.code'),
                "name" => lang('general.name')
            );
        }
    }

    public function setBreadCrumb($breadcrumb = array()) {
        setBreadCrumb($breadcrumb);
    }

    public function setTitle($title) {
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
        $tripoinRestClient = new TripoinRestClient();
        $url = $this->url_api . $this->restURL . SLASH . IRestCommandConstant::COMMAND_STRING . EQUAL . IRestCommandConstant::INSERT_SINGLE_DATA;
        $result = $tripoinRestClient->doPOST($url, array(), array(), $_POST);
        if ($result == false) {
            $tripoinRestClient = new TripoinRestClient();
            $tripoinRestClient->doPOSTLoginNoAuth();
            return $this->save();
        }
//        print_r(json_decode($result->getBody));
        if (is_numeric(json_decode($result->getBody)) > 0) {
            echo toastAlert("success", lang('general.title_insert_success'), lang('general.message_insert_success'));
            echo '<script>$(function(){postAjaxPagination()});</script>';
        } else {
//            echo toastAlert("error", lang('general.title_insert_error'), lang('general.message_insert_error'));
            echo toastAlert("error", lang('general.title_insert_error'), lang('error.' . json_decode($result->getBody)));
            echo '<script>$(function(){postAjaxPagination()});</script>';
//            echo resultPageMsg('danger', lang('general.title_insert_error'), $rs[0]);
        }
    }

    public function update() {
//        print_r($db->getResult());
        $tripoinRestClient = new TripoinRestClient();
        $url = $this->url_api . $this->restURL . SLASH . IRestCommandConstant::COMMAND_STRING . EQUAL . IRestCommandConstant::UPDATE_SINGLE_DATA;
        $result = $tripoinRestClient->doPut($url, array(), array(), $_POST);
        if ($result == false) {
            $tripoinRestClient = new TripoinRestClient();
            $tripoinRestClient->doPOSTLoginNoAuth();
            return $this->update();
        }
        if (is_numeric(json_decode($result->getBody)) > 0) {
            echo toastAlert("success", lang('general.title_update_success'), lang('general.message_update_success'));
            echo '<script>$(function(){postAjaxPagination()});</script>';
        } else {
//            echo toastAlert("error", lang('general.title_update_error'), lang('general.message_update_error'));
            echo toastAlert("error", lang('general.title_insert_error'), lang('error.' . json_decode($result->getBody)));
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
            $Datatable->per_page = 5;
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

//        print_r($testLogin);
        $sorting = array();
        if (!empty($this->orderBy)) {
            $sorting = array(key($this->orderBy) => $this->orderBy[key($this->orderBy)]);
        }
        $list_data = $Datatable->select_pagination_rest($this->url_api . $this->restURL, null, $sorting);
//        print_r($list_data);


        include_once FILE_PATH($this->viewList);
    }

    public function create() {
        $Form = new Form();
        include_once FILE_PATH($this->viewCreate);
    }

    public function edit() {
        $id = $_POST['id'];
        $Form = new Form();
        $Button = new Button();
        $tripoinRestClient = new TripoinRestClient();
        $url = $this->url_api . $this->restURL . SLASH . IRestCommandConstant::COMMAND_STRING . EQUAL . IRestCommandConstant::FIND_SINGLE_DATA_BY_ID;
        $result = $tripoinRestClient->doPOST($url, array(), array(), $id);
//        $db->connect();
//        $get_data = $db->selectByID($data, $data->getId() . EQUAL . $id);
        if ($result == false) {
            $tripoinRestClient = new TripoinRestClient();
            $tripoinRestClient->doPOSTLoginNoAuth();
            return $this->edit();
        }
//        print_r($result);
        $get_data = json_decode($result->getBody);
        include_once FILE_PATH($this->viewEdit);
    }

    public function delete() {
        $id = $_POST['id'];
        $Form = new Form();
        /*  $db = new Database();
          //        $group = new SecurityGroup();
          $data = $this->modelData;
          $db->connect();
          $get_data = $db->delete($data->getEntity(), $data->getId() . EQUAL . $id);
          echo $get_data;
         */
        $tripoinRestClient = new TripoinRestClient();
        $url = $this->url_api . $this->restURL . SLASH . IRestCommandConstant::COMMAND_STRING . EQUAL . IRestCommandConstant::DELETE_SINGLE_DATA;
        $result = $tripoinRestClient->doDelete($url, array(), array(), $id);
        if ($result == false) {
            $tripoinRestClient = new TripoinRestClient();
            $tripoinRestClient->doPOSTLoginNoAuth();
            return $this->update();
        }
        if (is_numeric(json_decode($result->getBody)) > 0) {
//            echo json_decode($result->getBody);
            echo 1;
        } else {
            echo 0;
        }

//        print_r($get_group);
    }

    public function deleteCollection() {
        $id = $_POST['id'];
        $Form = new Form();
//        $db = new Database();
//        $group = new SecurityGroup();
//        $data = $this->modelData;
//        $db->connect();
//        $where = $data->getId() . " IN (" . $id . ")";
//        $delete = 'DELETE FROM ' . $data->getEntity() . ' WHERE ' . $where;
//        print_r($delete);
//        $delete_data = $db->delete($data->getEntity(), $where);
//        $get_data = $db->delete($data->getEntity(), $data->getId() . EQUAL . $id);
//        echo $delete_data;
//        print_r($get_group);
        $tripoinRestClient = new TripoinRestClient();
        $url = $this->url_api . $this->restURL . SLASH . IRestCommandConstant::COMMAND_STRING . EQUAL . IRestCommandConstant::DELETE_COLLECTION;
        $result = $tripoinRestClient->doDelete($url, array(), array(), $id);
        if ($result == false) {
            $tripoinRestClient = new TripoinRestClient();
            $tripoinRestClient->doPOSTLoginNoAuth();
            return $this->update();
        }
        if (is_numeric(json_decode($result->getBody)) > 0) {
//            echo json_decode($result->getBody);
            echo 1;
        } else {
            echo 0;
        }
    }

    public function setAutoCrud() {
        $this->autoViewURL();
        $this->autoViewPath();
    }

    public function autoViewURL() {
        setCreateURL(URL(getAdminTheme() . $this->indexUrl . '/create'));
        setDatatableURL(URL(getAdminTheme() . $this->indexUrl . '/list'));
        $this->editUrl = URL(getAdminTheme() . $this->indexUrl . '/edit');
        $this->deleteUrl = URL(getAdminTheme() . $this->indexUrl . '/delete');
        $this->insertUrl = URL(getAdminTheme() . $this->indexUrl . '/save');
        $this->updateUrl = URL(getAdminTheme() . $this->indexUrl . '/update');
        $this->urlDeleteCollection = URL(getAdminTheme() . $this->indexUrl . '/deleteCollection');
    }

    public function autoViewPath() {
        $this->viewList = $this->viewPath . '/list.html.php';
        $this->viewCreate = $this->viewPath . '/new.html.php';
        $this->viewEdit = $this->viewPath . '/edit.html.php';
    }

}