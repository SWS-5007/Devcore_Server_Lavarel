<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lib\Validators;

use Illuminate\Support\Collection;

/**
 * Description of GenericValidator
 *
 * @author pablo
 */
class GenericValidator {

    protected $rules = [];
    protected $data = [];
    protected $action = '';
    protected $messages = [];
    protected $user = null;
    protected $entity = null;

    /**
     *
     * @var \Illuminate\Validation\Validator
     */
    private $validator;

    public function __construct($rules = [], $data = [], $entity = null, $action = '', $messages = []) {
        if($data instanceof Collection){
            $data=$data->all();
        }
        $this->rules = $rules;
        $this->action = $action;
        $this->data = $data;
        $this->entity = $entity;
        $this->messages = $messages;
        $this->constructValidator();
    }

    function getRules() {
        return $this->rules;
    }

    function getData() {
        return $this->data;
    }

    function getAction() {
        return $this->action;
    }

    function getMessages() {
        return $this->messages;
    }

    function getValidator() {
        return $this->validator;
    }

    function getEntity() {
        return $this->entity;
    }

    function setRules($rules) {
        $this->rules = $rules;
        return $this;
    }

    function setData($data) {
        $this->data = $data;
        return $this;
    }

    function setAction($action) {
        $this->action = $action;
        return $this;
    }

    function setMessages($messages) {
        $this->messages = $messages;
        return $this;
    }

    function setValidator(\Illuminate\Validation\Validator $validator) {
        $this->validator = $validator;
        return $this;
    }

    function setEntity($entity) {
        $this->entity = $entity;
        return $this;
    }

    function getUser() {
        return $this->user;
    }

    function setUser($user) {
        $this->user = $user;
        return $this;
    }

    function pases() {
        return $this->validator()->passes();
    }

    function fails() {
        return $this->validator()->fails();
    }

    function errors(){
        return $this->validator()->errors();
    }

    function messages() {
        return $this->validator()->messages();
    }

    function constructValidator($customAttributes = []) {
        $this->validator = validator($this->data, $this->rules, $this->messages, $customAttributes);
        return $this->validator;
    }

    function validator(){
        return $this->validator;
    }

    function execute(){
        return $this->validator()->validate();
    }

}
