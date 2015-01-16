<?php

/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class Instagram extends Model {

    var     $name   = 'Instagram';
    private $API    = array();

    public function instaAPI() {
        App::uses('HttpSocket', 'Network/Http');
        App::uses('CakeSession', 'Model/Datasource');
        $HttpSocket = new HttpSocket();
        $API['client_id'] = '657efa9632744a88bf1d8791b02907bf';
        // $API['link'] = "https://instagram.com/oauth/authorize/?client_id={$API['client_id']}&redirect_uri=http://localhost&response_type=token";
        // $API['get'] = "https://api.instagram.com/v1/users/search?q={$name}&client_id={$API['client_id']}";
        // $API['chicken'] = "https://api.instagram.com/v1/users/430556958/media/recent?client_id={$API['client_id']}";
        $API['chicken'] = "https://api.instagram.com/v1/users/2279522/media/recent?client_id=657efa9632744a88bf1d8791b02907bf&count=90";
        if (CakeSession::read('API.end') === true){
            CakeSession::delete('API.end');
            return 0;
        }
        if (CakeSession::read('API.next_url') && (CakeSession::read('API.next_max_id'))) {
            if (stripos(CakeSession::read('API.next_url'), 'max_id') !== false) {
                $API['chicken'] = CakeSession::read('API.next_url');
                CakeSession::delete('API.next_url');
            }
        }
//        $rs = $this->Common->curl_get($API['chicken']);

        $rs = $HttpSocket->get($API['chicken']);
        $rsArray = json_decode($rs, true);

        if (!$rsArray['pagination'])
            CakeSession::write('API.end', true);
        else {
            CakeSession::write('API.next_url', $rsArray['pagination']['next_url']);
            CakeSession::write('API.next_max_id', $rsArray['pagination']['next_max_id']);
        }

        foreach ($rsArray['data'] as $key => $value) {
            foreach ($value['images']['standard_resolution'] as $k => $v) {
                if (is_string($v))
                    $images[] = $v;
            }
        }

        $images_result = json_encode($images);
        return $images_result;
    }

}
