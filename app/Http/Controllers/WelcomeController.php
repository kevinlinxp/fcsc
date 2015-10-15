<?php namespace App\Http\Controllers;

class WelcomeController extends Controller {

	public function __construct() {
		$this->middleware('guest');
	}

	public function index() {
		return "hi";//view('pages/welcome');
		//return view('pages.welcome');
	}

	public function contact() {
		return view('pages/contact');
	}
}