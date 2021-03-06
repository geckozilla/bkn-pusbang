<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\Model;

/**
 * Description of MasterPostLanguage
 *
 * @author sfandrianah
 */
class LinkRegistration {

    //put your code here
    public function __construct() {
        
    }

    public $entity = 'link_registration';
    public $id = 'id';
    public $registrationId = 'registration_id';
    public $attachmentParticipantId = 'attachment_participant_id';
    public $attachmentLetterId = 'attachment_letter_id';
    public $subjectId = 'activity_id';
    public $activityId = 'activity_id';
    public $registrationDetailsId = 'registration_details_id';
    public $status = 'status';
    public $description = 'description';
    public $createdBy = 'created_by';
    public $createdOn = 'created_on';

    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

        function getStatus() {
        return $this->status;
    }

    function getDescription() {
        return $this->description;
    }

    function getCreatedBy() {
        return $this->createdBy;
    }

    function getCreatedOn() {
        return $this->createdOn;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setCreatedBy($createdBy) {
        $this->createdBy = $createdBy;
    }

    function setCreatedOn($createdOn) {
        $this->createdOn = $createdOn;
    }

    function getEntity() {
        return $this->entity;
    }

    function getActivityId() {
        return $this->activityId;
    }

    function setActivityId($activityId) {
        $this->activityId = $activityId;
    }

    function getRegistrationId() {
        return $this->registrationId;
    }

    function getAttachmentParticipantId() {
        return $this->attachmentParticipantId;
    }

    function getAttachmentLetterId() {
        return $this->attachmentLetterId;
    }

    function getSubjectId() {
        return $this->subjectId;
    }

    function getRegistrationDetailsId() {
        return $this->registrationDetailsId;
    }

    function setEntity($entity) {
        $this->entity = $entity;
    }

    function setRegistrationId($registrationId) {
        $this->registrationId = $registrationId;
    }

    function setAttachmentParticipantId($attachmentParticipantId) {
        $this->attachmentParticipantId = $attachmentParticipantId;
    }

    function setAttachmentLetterId($attachmentLetterId) {
        $this->attachmentLetterId = $attachmentLetterId;
    }

    function setSubjectId($subjectId) {
        $this->subjectId = $subjectId;
    }

    function setRegistrationDetailsId($registrationDetailsId) {
        $this->registrationDetailsId = $registrationDetailsId;
    }

    public function search($key) {
        return $this->$key;
    }

    function setData($data) {
        $array_data = array();
        foreach ($data as $key => $value) {
            $array_data[$this->$key] = $value;
        }
        return $array_data;
    }

}
