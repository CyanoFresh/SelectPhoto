<?php

namespace app\commands;

use app\models\User;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\VarDumper;

class UserController extends Controller
{
    public function actionRegister($email, $password)
    {
        $user = new User([
            'scenario' => User::SCENARIO_CREATE,
        ]);

        $user->email = $email;
        $user->generateAuthKey();
        $user->setPassword($password);

        if ($user->save(false)) {
            echo $this->ansiFormat('Successfully registered' . PHP_EOL, Console::FG_GREEN);
            return 1;
        }

        echo $this->ansiFormat('Cannot save user model. Errors:' . PHP_EOL . PHP_EOL, Console::FG_RED);

        VarDumper::dump($user->errors);

        echo PHP_EOL;

        return 0;
    }

    public function actionChangePassword($id, $newPassword)
    {
        $user = User::findOne($id);

        if (!$user) {
            echo $this->ansiFormat('User was not found' . PHP_EOL, Console::FG_RED);
            return 0;
        }

        $user->scenario = User::SCENARIO_UPDATE;
        $user->setPassword($newPassword);
        $user->generateAuthKey();

        if ($user->save()) {
            echo $this->ansiFormat('Password successfully changed' . PHP_EOL, Console::FG_GREEN);
            return 1;
        }

        echo $this->ansiFormat('Cannot save user model. Errors:' . PHP_EOL . PHP_EOL, Console::FG_RED);

        VarDumper::dump($user->errors);

        return 0;
    }

    public function actionCheckPassword($id, $password)
    {
        $user = User::findOne($id);

        if (!$user) {
            echo $this->ansiFormat('User was not found' . PHP_EOL, Console::FG_RED);
            return 0;
        }

        if ($user->validatePassword($password)) {
            echo $this->ansiFormat('Password is valid' . PHP_EOL, Console::FG_GREEN);
            return 1;
        }

        echo $this->ansiFormat('Password is invalid' . PHP_EOL, Console::FG_RED);

        return 1;
    }
}
