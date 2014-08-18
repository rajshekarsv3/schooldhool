<?php
class ControllerModuleVoodoo extends Controller {

    private $error = array();

    public function index() {
        //Load the language file for this module
        $this->load->language('module/voodoo');

        //Set the title from the language file $_['heading_title'] string
        $this->document->setTitle($this->language->get('heading_title'));

        //Load the settings model. You can also add any other models you want to load here.
        $this->load->model('setting/setting');

        //Save the settings if the user has submitted the admin form (ie if someone has pressed save).
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->model_setting_setting->editSetting('voodoo', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        //This is how the language gets pulled through from the language file.
        //
        // If you want to use any extra language items - ie extra text on your admin page for any reason,
        // then just add an extra line to the $text_strings array with the name you want to call the extra text,
        // then add the same named item to the $_[] array in the language file.
        //
        // 'voodoo_example' is added here as an example of how to add - see admin/language/english/module/voodoo.php for the
        // other required part.

        $text_strings = array(
            'heading_title',
            'text_voodoo_balance',
            'text_contact_example',
            'text_start_voodoo',
            'text_send_sms',
            'text_admin_alert_customer_register',
            'text_admin_alert_customer_checkout',
            'text_customer_alert_ckeckout',
            'text_customer_alert_order_status',
            'text_admin_alert_additional_settings',
            'text_admin_alert_include_items',
            'text_admin_alert_allow_long_message',
            'text_admin_alert_order_status',
            'button_save',
            'button_cancel',
            'button_send_sms',
            'entry_voodoo_balance',
            'entry_voodoo_admin_contact',
            'entry_voodoo_message_type',
            'entry_voodoo_username',
            'entry_voodoo_admin_alert',
            'entry_voodoo_customer_alert',
            'entry_voodoo_password',
            'order_enabled',
            'order_sender',
            'order_status_sender',
            'order_message',
            'order_link_your_account',
            'notify_admin',
            'admin_telephone',
        );

        foreach ($text_strings as $text) {
            $this->data[$text] = $this->language->get($text);
        }
        //END LANGUAGE

        //The following code pulls in the required data from either config files or user
        //submitted data (when the user presses save in admin). Add any extra config data
        // you want to store.
        //
        // NOTE: These must have the same names as the form data in your voodoo.tpl file
        //
        $config_data = array(
            'voodoo_admin_contact',
            'voodoo_message_type',
            'voodoo_username',
            'voodoo_admin_alert_customer_register',
            'voodoo_admin_alert_customer_checkout',
            'voodoo_admin_alert_include_items',
            'voodoo_admin_alert_allow_long_message',
            'voodoo_customer_alert_ckeckout',
            'voodoo_customer_alert_order_status',
            'voodoo_password',
            'order_sender_number',
            'order_status_sender_number',
            'order_message_value',
            'order_enabled_value',
            'notify_admin_value',
            'admin_telephone_value',
            'message_of_order_status',
            'order_status_enabled',
            'admin_alert_order_status',
        );

        foreach ($config_data as $conf) {
            if (isset($this->request->post[$conf])) {
                $this->data[$conf] = $this->request->post[$conf];
            } else {
                $this->data[$conf] = $this->config->get($conf);
            }
        }

        if($this->data['voodoo_username'] == "" || $this->data['voodoo_password'] == ""){
            $this->data['voodoo_balance'] = $this->data['text_voodoo_balance'];
        }
        else{
            $voodoo = new Voodoo;
            $voodoo->setApi($this->data['voodoo_username'], $this->data['voodoo_password']);
            $this->data['voodoo_balance'] = $voodoo->getBalance();
        }

        if($this->data['voodoo_message_type'] == ""){
            $this->data['voodoo_message_type'] = 1;
        }

        //This creates an error message. The error['warning'] variable is set by the call to function validate() in this controller (below)
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        //SET UP BREADCRUMB TRAIL. YOU WILL NOT NEED TO MODIFY THIS UNLESS YOU CHANGE YOUR MODULE NAME.
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('module/voodoo', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('module/voodoo', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['text_start_voodoo'] = sprintf($this->data['text_start_voodoo'], $this->url->link('module/voodoo', 'token=' . $this->session->data['token'], 'SSL'));
        $this->data['send_sms'] = $this->url->link('module/voodoo', 'token=' . $this->session->data['token'], 'SSL');

        //This code handles the situation where you have multiple instances of this module, for different layouts.
        $this->data['modules'] = array();

        if (isset($this->request->post['voodoo_module'])) {
            $this->data['modules'] = $this->request->post['voodoo_module'];
        } elseif ($this->config->get('voodoo_module')) {
            $this->data['modules'] = $this->config->get('voodoo_module');
        }

        $this->load->model('design/layout');

        $this->data['layouts'] = $this->model_design_layout->getLayouts();

        //Choose which template file will be used to display this request.
        $this->template = 'module/voodoo.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
        );

        //Send the output.
        $this->response->setOutput($this->render());
    }

    public function install() {

        $this->load->model('module/voodoo');

        $this->model_module_voodoo->createVoodooTables();
    }

    public function uninstall() {

        $this->load->model('module/voodoo');

        $this->model_module_voodoo->deleteVoodooTables();
    }
    /*
     *
     * This function is called to ensure that the settings chosen by the admin user are allowed/valid.
     * You can add checks in here of your own.
     *
     */
    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/voodoo')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function verify_api(){
        $user = $_REQUEST['user'];
        $pass = $_REQUEST['pass'];
        $link = "http://www.voodoosms.com/vsapi/server.php?method=get_credit&username=$user&password=$pass";
        $http = curl_init($link);
        // do your curl thing here
        curl_setopt($http, CURLOPT_RETURNTRANSFER, TRUE);
        $http_result = curl_exec($http);
        $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
        curl_close($http);
        if($http_result=='401:	Unauthorized'){
            $http_result = 'There is an error in username and password';
        }
        else{
            $http_result = 'API is Correct';
        }
        echo $http_result;



    }



}
?>