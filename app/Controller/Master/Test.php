<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\Controller\Master;

/**
 * Description of Test
 *
 * @author sfandrianah
 */
use app\Util\Database;

class Test {

    //put your code here
    public function test() {
        echo 'welcome';
    }

    public function migration() {
        $dbNew = new Database();
        $dbNew->connect();
//        $dbNew->sql("SELECT * FROM security_user WHERE LOWER(user_email) = 'sfandrianah2@gmail.com' AND (user_password = SHA1(CONCAT(user_salt, SHA1(CONCAT(user_salt, SHA1('fandrianah2'))))) OR user_password = '".md5('fandrianah2')."') AND status = '1' AND user_approved = '1'");
//        $rsPostNew = $dbNew->getResult();
//        print_r($rsPostNew);
//        echo '<p>';
        $dbOld = new Database();
        /*
         * $dbOld->setDb_host('192.168.1.9');
          $dbOld->setDb_name('ticketing_ecommerce');
          $dbOld->setDb_user('eth');
          $dbOld->setDb_pass('3thS1gm42016.');
         * 
         */
        $dbOld->setDb_host('localhost');
        $dbOld->setDb_name('talaind1_web');
        $dbOld->setDb_user('talaind1_dbuser');
        $dbOld->setDb_pass('galaxys6.');
//        echo $dbOld->getDb_host();
        $dbOld->connect();
        /* for ($no = 1; $no <= 34; $no++) {
          $dbOld->insert('sec_function_assignment', array(
          "id" => $no,
          "status" => 1,
          "created_by" => "admin",
          "created_ip" => "0.0.0.0",
          "created_time" => date('Y-m-d h:i:s'),
          "group_id" => 1,
          "function_id" => $no,
          "assignment_order" => 0,
          "action_type" => "view"
          ));
          }


          $rsPostOld = $dbOld->getResult();
         */

//        print_r($rsPostOld);
        $rsPostOld = $dbOld->getResult();
        foreach ($rsPostOld as $value) {
//            echo $publishDate.'<br/>';

            /* MIGRATION TABLE USER */
//            if($value['email'] != )
            $code = explode("@", $value['email']);
            $dbNew->insert('security_user', array(
//          'user_id' => $value['customer_id'],
                'user_code' => $code[0],
                'user_email' => $value['email'],
                'user_password' => $value['password'],
                'user_salt' => $value['salt'],
                'user_newsletter' => $value['newsletter'],
                'created_by_ip' => $value['ip'],
                'created_on' => $value['date_added'],
                'status' => $value['status'],
                'user_approved' => $value['approved'],
                'group_id' => 2,
            ));



            /* MIGRATION TABLE USER PROFILE */

            $code = explode("@", $value['email']);
            $dbNew->insert('security_user_profile', array(
//          'user_id' => $value['customer_id'],
                'user_code' => $code[0],
                'user_fullname' => $value['firstname'] . " " . $value['lastname'],
                'user_email' => $value['email'],
                'user_telp' => $value['telephone'],
            ));
        }

        /*
         * MIGRATION MANY TO MANY TABLE POST CATEGORIES
         * $dbNew->insert('mst_post_function', array(
          'post_id' => $value['post_id'],
          'function_id' => $value['category_id']
          ));
         */


        /* $dbNew->sql("select COUNT(post_code) as countCode FROM mst_post WHERE post_code='" . $value['slug'] . "'");
          $rsCountCode = $dbNew->getResult();
          if ($rsCountCode[0]['countCode'] == 0) {
          $publishDate = date("Y-m-d H:i:s", $value['publish_date']);
          $createdOn = date("Y-m-d H:i:s", $value['created_on']);
          $dbNew->insert('mst_post', array(
          'post_id' => $value['id'],
          'post_code' => $value['slug'],
          'author_code' => $value['author_slug'],
          'author_name' => $value['author'],
          'post_title' => $value['title'],
          'post_subtitle' => $value['subtitle'],
          'post_content' => $value['content'],
          'post_status' => $value['status'],
          'publish_on' => $publishDate,
          'created_on' => $createdOn,
          'created_by' => $value['created_by'],
          'modified_on' => $value['updated_on'],
          'modified_by' => $value['updated_by'],
          'post_featured' => $value['featured'],
          'post_type' => $value['type'],
          'post_url_img' => $value['image'],
          'post_url_thumbnail' => $value['thumbnail'],
          'post_template' => $value['template'],
          'post_comment_enable' => $value['comment_enable'],
          'read_count' => $value['read_count'],
          'post_extra_flag' => $value['extra_flag'],
          'status' => 1,
          ));
          }
         * 
         */
//            echo $rsCountCode[0]['countCode'];
//        }
    }
    
    public function hashing(){
        echo password_hash('trijep3t3', PASSWORD_BCRYPT);
    }

}
