<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Config;
use common\components\GoogleAuthenticator;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $verifyCode;
    public $rememberMe = false;

    public $code;

    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'required'],
            ['rememberMe', 'boolean'],

            ['password', 'required', 'on' => 'stepOne'],
            ['password', 'validatePassword', 'on' => 'stepOne'],
            ['verifyCode', 'captcha', 'on' => 'stepOne'],
            
            ['code', 'integer', 'on' => 'stepTwo'],
            ['code', 'required', 'on' => 'stepTwo'],
            ['code', 'validateCode', 'on' => 'stepTwo'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => '2FA Code',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function validateCode($attribute, $params)
    {
        $user = $this->getUser();
        if ($user) {
            $secret = $user->getExtraValue('_2fa_secret');
            if (!GoogleAuthenticator::verifyCode($secret, $this->code)) {
                $this->addError($attribute, 'Incorrect 2FA code.');
            }
        } else {
            $this->addError($attribute, 'Invalid User.');
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username, 'manager');
        }

        return $this->_user;
    }
}
