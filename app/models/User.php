<?php
namespace app\models;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Clase User
 *
 * Esta clase representa el modelo de datos para un usuario en la base de datos MongoDB.
 * También implementa IdentityInterface para manejar la autenticación en Yii2.
 *
 * @property string $_id ID único del usuario
 * @property string $username Nombre de usuario
 * @property string $password_hash Hash de la contraseña del usuario
 * @property string $auth_key Clave de autenticación
 * @property string $access_token Token de acceso para autenticación
 * @property string $password Contraseña en texto plano (no se almacena en la base de datos)
 */
class User extends ActiveRecord implements IdentityInterface
{
    public string $password;

    /**
     * Devuelve el nombre de la colección de MongoDB asociada a este modelo.
     *
     * @return string Nombre de la colección
     */
    public static function collectionName(): string
    {
        return 'users';
    }

    /**
     * Define los atributos del modelo.
     *
     * @return array Lista de atributos del modelo
     */
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

    /**
     * Define las reglas de validación para los atributos del modelo.
     *
     * @return array Lista de reglas de validación
     */
    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'unique'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Encuentra un usuario por su ID.
     *
     * @param string $id ID del usuario
     * @return static|null El usuario encontrado o null
     */
    public static function findIdentity($id)
    {
        return static::findOne(['_id' => $id]);
    }

    /**
     * Encuentra un usuario por su token de acceso.
     *
     * @param mixed $token Token de acceso
     * @param mixed $type Tipo de token (no utilizado en esta implementación)
     * @return static|null El usuario encontrado o null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Encuentra un usuario por su nombre de usuario.
     *
     * @param string $username Nombre de usuario
     * @return static|null El usuario encontrado o null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Obtiene el ID del usuario.
     *
     * @return string ID del usuario
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Obtiene la clave de autenticación del usuario.
     *
     * @return string Clave de autenticación
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Valida la clave de autenticación del usuario.
     *
     * @param string $authKey Clave de autenticación a validar
     * @return bool Si la clave de autenticación es válida
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Valida la contraseña del usuario.
     *
     * @param string $password Contraseña a validar
     * @return bool Si la contraseña es válida
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Establece la contraseña del usuario.
     *
     * @param string $password Nueva contraseña
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Genera una nueva clave de autenticación para el usuario.
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Genera un nuevo token de acceso JWT para el usuario.
     */
    public function generateAccessToken()
    {
        $now = new \DateTimeImmutable();
        $token = Yii::$app->jwt->getBuilder()
            ->issuedBy('http://example.com')
            ->permittedFor('http://example.org')
            ->identifiedBy('4f1g23a12aa')
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now->modify('+1 minute'))
            ->expiresAt($now->modify('+1 hour'))
            ->withClaim('uid', 1)
            ->withHeader('foo', 'bar')
            ->getToken(
                Yii::$app->jwt->getConfiguration()->signer(),
                Yii::$app->jwt->getConfiguration()->signingKey()
            );
        $tokenString = $token->toString();
    }

    /**
     * Se ejecuta antes de guardar el modelo.
     *
     * @param bool $insert Si esta es una operación de inserción
     * @return bool Si la operación debe continuar
     */
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