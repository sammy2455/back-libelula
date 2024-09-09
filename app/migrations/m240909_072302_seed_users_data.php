<?php

class m240909_072302_seed_users_data extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->insert('users', [
            '_id' => '66de784ded27665cd6039271',
            'username' => 'admin',
            'password_hash' => '$2y$13$um/VgF1uPRSaeiqmExWlu.RiCmVBQ7CWtQua8R6Orrb30ibdFFSaa',
            'auth_key' => 'V7vGMOp1MS8yaecNiPUeOZngOmfJf0In',
        ]);
    }

    public function down()
    {
        $this->remove('users', ['_id' => '66de784ded27665cd6039271']);
    }
}
