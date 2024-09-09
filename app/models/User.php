<?php
namespace app\models;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public string $password;

    public static function collectionName(): string
    {
        return 'users';
    }

    public function attributes(): array
    {
        return [
            '_id',
            'username',
            'password_hash',
            'auth_key',
            'access_token',
        ];
    }

    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'unique'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['_id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generateAccessToken()
    {
        $now = new \DateTimeImmutable();
        $token = Yii::$app->jwt->getBuilder()
            // Configures the issuer (iss claim)
            ->issuedBy('http://example.com')
            // Configures the audience (aud claim)
            ->permittedFor('http://example.org')
            // Configures the id (jti claim)
            ->identifiedBy('4f1g23a12aa')
            // Configures the time that the token was issued (iat claim)
            ->issuedAt($now)
            // Configures the time that the token can be used (nbf claim)
            ->canOnlyBeUsedAfter($now->modify('+1 minute'))
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify('+1 hour'))
            // Configures a new claim, called "uid"
            ->withClaim('uid', 1)
            // Configures a new header, called "foo"
            ->withHeader('foo', 'bar')
            // Builds a new token
            ->getToken(
                Yii::$app->jwt->getConfiguration()->signer(),
                Yii::$app->jwt->getConfiguration()->signingKey()
            );
        $tokenString = $token->toString();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->setPassword($this->password);
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }
}