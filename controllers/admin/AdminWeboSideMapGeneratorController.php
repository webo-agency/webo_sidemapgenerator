<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminWeboSideMapGeneratorController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
        $this->id_lang = $this->context->language->id;
        $this->default_from_language = $this->context->language->id;
        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Abc', array(), "Modules.WeboSideMapGenerator.Admin"),
                'icon' => 'icon-list-ul'
            ],
            'input' => [
                ['name'=>'name','type'=>'input','label'=>'Select Tag:','required'=>true],
            ],
        ];
    }

    public function renderForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Tag', [], 'Admin.Shopparameters.Feature'),
                'icon' => 'icon-list-ul'
            ],
            'input' => [
                ['name'=>'name','type'=>'select','label'=>'Select Tag:','required'=>true, 'options'=>['query'=> $tag, 'id'=> 'id_tag', 'name'=> 'name']],
            ],
            'submit' => [
                'title' => $this->trans('Save', [], 'Admin.Actions'),
            ]
        ];
        return parent::renderForm();
    }

}