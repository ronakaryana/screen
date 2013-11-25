<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index()
        {
          echo "Home page and login goes here ";
           echo "<a href='".base_url().'agent/dashboard' ."'>[main dashboard]</a>";
        }

}



?>