<?php
/**
 * Created by PhpStorm.
 * User: chmaeera.lakshitha212@gmail.com
 * Date: 5/29/2018
 * Time: 1:59 PM
 */

class ControllerExtensionModuleOpenSendgrid extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/module/open_sendgrid');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('module_open_sendgrid', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');


            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_sendgrid_api_key'] = $this->language->get('entry_sendgrid_api_key');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true),
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/open_sendgrid', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/module/open_sendgrid', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);


        if (isset($this->request->post['module_open_sendgrid_status'])) {
            $data['module_open_sendgrid_status'] = $this->request->post['module_open_sendgrid_status'];
        } else {
            $data['module_open_sendgrid_status'] = $this->config->get('module_open_sendgrid_status');
        }

        if (isset($this->request->post['module_open_sendgrid_api_key'])) {
            $data['module_open_sendgrid_api_key'] = $this->request->post['module_open_sendgrid_api_key'];
        } else {
            $data['module_open_sendgrid_api_key'] = $this->config->get('module_open_sendgrid_api_key');
        }
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/open_sendgrid', $data));

    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/open_sendgrid')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if ((utf8_strlen($this->request->post['module_open_sendgrid_api_key']) < 3) || (utf8_strlen($this->request->post['module_open_sendgrid_api_key']) > 100)) {
            $this->error['module_open_sendgrid_api_key'] = $this->language->get('error_module_open_sendgrid_api_key');
        }

        return !$this->error;
    }
}