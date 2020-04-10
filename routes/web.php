<?php
namespace App;

use App\Http\Controllers\Admin\GateManagerController;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

#Route::get('/', ['uses' => 'TestController@root']);

Route::group(['prefix' => 'admin', 'middleware' => 'admin_auth', 'namespace' => 'Admin'], function () {

    Route::get('/', 'adminController@showStart');

    Route::get('users', ['as' => 'admin.listUsers', 'uses' => 'UserController@listUsers']);
    Route::post('user/ldapimport', ['as' => 'admin.ldapImport', 'uses' => 'UserController@ldapInport']);
    Route::get('user/new', ['as' => 'admin.addUser', 'uses' => 'UserController@editUser']);
    Route::get('user/{id}', ['as' => 'admin.editUser', 'uses' => 'UserController@editUser']);
    Route::get('user/{id}/enable', ['as' => 'admin.enableUser', 'uses' => 'UserController@enableUser']);
    Route::get('user/{id}/disable', ['as' => 'admin.disableUser', 'uses' => 'UserController@disableUser']);
    Route::get('user/{id}/delete', ['as' => 'admin.deleteUser', 'uses' => 'UserController@deleteUser']);
    Route::post('user/{id}', ['as' => 'admin.saveUser', 'uses' => 'UserController@saveUser']);
    Route::post('user/{id}/gates', ['as' => 'admin.saveUserGates', 'uses' => 'UserController@saveUserGates']);

    Route::get('tokens', ['as' => 'admin.listTokens', 'uses' => 'TokenController@listTokens']);
    Route::get('token/new', ['as' => 'admin.addToken', 'uses' => 'TokenController@editToken']);
    Route::get('token/{id}', ['as' => 'admin.editToken', 'uses' => 'TokenController@editToken']);
    Route::get('token/{id}/enable', ['as' => 'admin.enableToken', 'uses' => 'TokenController@enableToken']);
    Route::get('token/{id}/disable', ['as' => 'admin.disableToken', 'uses' => 'TokenController@disableToken']);
    Route::get('token/{id}/delete', ['as' => 'admin.deleteToken', 'uses' => 'TokenController@deleteToken']);
    Route::post('token/{id}', ['as' => 'admin.saveToken', 'uses' => 'TokenController@saveToken']);

    Route::get('gates', ['as' => 'admin.listGates', 'uses' => 'GateController@listGates']);
    Route::get('gate/new', ['as' => 'admin.addGate', 'uses' => 'GateController@editGate']);
    Route::get('gate/{id}', ['as' => 'admin.editGate', 'uses' => 'GateController@editGate']);
    Route::get('gate/{id}/enable', ['as' => 'admin.enableGate', 'uses' => 'GateController@enableGate']);
    Route::get('gate/{id}/disable', ['as' => 'admin.disableGate', 'uses' => 'GateController@disableGate']);
    Route::get('gate/{id}/delete', ['as' => 'admin.deleteGate', 'uses' => 'GateController@deleteGate']);
    Route::post('gate/{id}', ['as' => 'admin.saveGate', 'uses' => 'GateController@saveGate']);
    Route::post('gate/{id}/users', ['as' => 'admin.saveGateUsers', 'uses' => 'GateController@saveGateUsers']);



    Route::get('gatemanagers', ['as' => 'admin.listGateManagers', 'uses' => 'GateManagerController@listGateManagers']);
    Route::get('gatemanager/new', ['as' => 'admin.addGateManager', 'uses' => 'GateManagerController@editGateManager']);
    Route::get('gatemanager/{id}', ['as' => 'admin.editGateManager', 'uses' => 'GateManagerController@editGateManager']);
    Route::get('gatemanager/{id}/regenerate', ['as' => 'admin.regenerateGateManager', 'uses' => 'GateManagerController@regenerateGateManager']);
    Route::get('gatemanager/{id}/enable', ['as' => 'admin.enableGateManager', 'uses' => 'GateManagerController@enableGateManager']);
    Route::get('gatemanager/{id}/disable', ['as' => 'admin.disableGateManager', 'uses' => 'GateManagerController@disableGateManager']);
    Route::get('gatemanager/{id}/delete', ['as' => 'admin.deleteGateManager', 'uses' => 'GateManagerController@deleteGateManager']);
    Route::post('gatemanager/{id}', ['as' => 'admin.saveGateManager', 'uses' => 'GateManagerController@saveGateManager']);

});

Route::group(['prefix' => 'api', 'middleware' => 'controller_auth', 'namespace' => 'Api'], function () {

    Route::get('gatemanager', ['as' => 'api.getManagerStatus', 'uses' => 'GateManagerApiController@getStatus']);
    Route::post('gatemanager/event', ['as' => 'api.postManagerStatus', 'uses' => 'GateManagerApiController@postEvent']);

    Route::get('gate/{gate}', ['as' => 'api.getGateStatus', 'uses' => 'GateApiController@getStatus']);
    Route::get('gate/{gate}/authenticate', ['as' => 'api.authenticateToken', 'uses' => 'GateApiController@authenticate']);
    Route::post('gate/{gate}/event', ['as' => 'api.postGateStatus', 'uses' => 'GateApiController@postEvent']);

});

