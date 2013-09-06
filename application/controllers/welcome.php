<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller
{

    public function index()
    {
        $this->load->view('welcome');
    }

    /**
     * Give the scenario a name
     */
    function name()
    {
        $scenario = $this->session->userdata('scenario');

        $name = $this->input->post('name', TRUE);

        if ($name != '')
        {
            $scenario['name'] = $name;
            $this->session->set_userdata(array('scenario' => $scenario));
        }

        redirect('');
    }

    /**
     * Add an initial step to the scenario
     */
    function start()
    {
        $scenario = $this->session->userdata('scenario');

        $url = $this->input->post('url', TRUE);

        if ($url != '')
        {
            $scenario['url'] = prep_url($url);
            $this->session->set_userdata(array('scenario' => $scenario));
        }

        redirect('');
    }

    /**
     * Add a result step to the scenario
     */
    function result()
    {
        $scenario = $this->session->userdata('scenario');

        $result_action = $this->input->post('result', TRUE);
        $result_text = $this->input->post('result_text', TRUE);

        if ($result_action != '' && $result_text != '')
        {
            $scenario['results_array'][] = array(
                'result' => $result_action,
                'result_text' => $result_text
            );

            $this->session->set_userdata(array('scenario' => $scenario));
        }

        redirect('');
    }

    /**
     * Clear the session and start again
     */
    function restart()
    {
        $this->session->sess_destroy();

        redirect('');
    }

    /**
     * export a feature file
     */
    function export()
    {
        $this->load->helper('download');

        $scenario = $this->session->userdata('scenario');

        if (is_array($scenario) && !!empty($scenario))
        {
            if (isset($scenario['url']) && $scenario['url'] != '')
            {
                $parse_url = $parse = parse_url($scenario['url']);
                $host = $parse_url['host'];
                $feature = $this->load->view('feature_export', '', TRUE);
                force_download(str_replace(".", "_", $host) . '.feature', $feature);
            }
        }
        else
        {
            redirect('');
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */