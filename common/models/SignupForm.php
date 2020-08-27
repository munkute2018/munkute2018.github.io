<?php
namespace common\models;
use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Avatar;
/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $password;
    public $password_repeat;
    public $firstname;
    public $lastname;
    public $gender = 1;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required','message' => 'Email không được để trống'],
            ['username', 'email','message' => 'Email không hợp lệ'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Email này đã được sử dụng'],
            ['username', 'string', 'min' => 1, 'max' => 50],
            ['password', 'required','message' => 'Mật khẩu không được để trống'],
            ['password', 'string', 'min' => 6,'message' => 'Mật khẩu không thể ít hơn 6 kí tự'],
            ['password_repeat', 'required','message' => 'Mật khẩu không được để trống'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Mật khẩu không khớp" ],
            ['firstname', 'trim'],
            ['firstname', 'required','message' => 'Họ không được để trống'],
            ['firstname', 'string', 'min' => 1, 'max' => 50],
            ['lastname', 'trim'],
            ['lastname', 'required','message' => 'Họ không được để trống'],
            ['lastname', 'string', 'min' => 1, 'max' => 50],
            ['gender', 'required', 'message' => 'Vui lòng chọn giới tính'],
        ];
    }
    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->firstname = $this->firstname;
        $user->lastname = $this->lastname;
        $user->gioitinh = $this->gender;
        $user->generateAuthKey();
        return $user->save() && /*$this->sendEmail($user) &&  */ $this->createAvatar($user);
    }
    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->username)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }

    protected function createAvatar($user)
    {
        $ava = new Avatar();
        $ava->id_user = $user->id;
        $ava->link = 'default.png';
        $ava->status = 1;
        return $ava->save();
    }
}