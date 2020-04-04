<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use \InvalidArgumentException;

class Controller extends BaseController
{

    protected $request;

    protected $errors;
    protected $warnings;
    protected $successes;


    public function __construct(Request $request) {
        $this->request = $request;

        #Get Alerts from querystring
        if( $this->request->has('error')) {
            $a = $this->request->input('error');
            $a = is_array($a) ? $a : array($a);
            foreach( $a as $i ) {
                if ( !empty($i) ) $this->errors[] = urldecode($i);
            }
        }

        if( $this->request->has('warning')) {
            $a = $this->request->input('warning');
            $a = is_array($a) ? $a : array($a);
            foreach( $a as $i ) {
                if ( !empty($i) ) $this->warnings[] = urldecode($i);
            }
        }

        if( $this->request->has('success')) {
            $a = $this->request->input('success');
            $a = is_array($a) ? $a : array($a);
            foreach( $a as $i ) {
                if ( !empty($i) ) $this->successes[] = urldecode($i);
            }
        }
    }

    #Quickly build view with class variables
    protected function quickView($view, $vars = []) {

        if( !empty($this->user) ) $vars['user'] = $this->user;
        if( !empty($this->users) ) $vars['users'] = $this->users;

        if( !empty($this->gate) ) $vars['gate'] = $this->gate;
        if( !empty($this->gates) ) $vars['gates'] = $this->gates;

        if( !empty($this->gateManager) ) $vars['gateManager'] = $this->gateManager;
        if( !empty($this->gateManagers) ) $vars['gateManagers'] = $this->gateManagers;


        if( !empty($this->token) ) $vars['token'] = $this->token;
        if( !empty($this->tokens) ) $vars['tokens'] = $this->tokens;

        if( !empty($this->errors) ) $vars['errors'] = $this->errors;
        if( !empty($this->warnings) ) $vars['warnings'] = $this->warnings;
        if( !empty($this->successes) ) $vars['successes'] = $this->successes;

        return view($view, $vars);
    }

    #Redirect with Alerts in Querystring
    protected function redirectWithAlerts($route, $vars = [], $force_view=false) {

        if( $this->errors !== NULL) $vars['error'] = $this->errors;
        if( $this->warnings !== NULL) $vars['warning'] = $this->warnings;
        if( $this->successes !== NULL) $vars['success'] = $this->successes;

        #If there was a valid source route in the querystring go back there, otherwise got to $route
        if( $this->request->filled('source') && $force_view == false) {
            $input = $this->request->input('source');
            if( preg_match('/^([\w.])+$/', $input ) === 1) {
                try {
                    if ($this->request->filled('sourceid') && filter_var($this->request->input('sourceid'), FILTER_VALIDATE_INT)) $vars['id'] = $this->request->input('sourceid');
                    return redirect()->route($input, $vars);
                }
                catch (InvalidArgumentException $e) {
                }
            }
        }
        return redirect()->route($route, $vars);

    }
}
