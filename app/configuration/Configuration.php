<?php
include_once("helper/MysqlDatabase.php");
include_once("helper/MysqlObjectDatabase.php");
include_once("helper/IncludeFilePresenter.php");
include_once("helper/Router.php");
include_once("helper/MustachePresenter.php");
include_once("helper/EmailHelper.php");

include_once("controller/HomeController.php");
include_once("controller/LoginController.php");
include_once("controller/LogoutController.php");
include_once("controller/RegisterController.php");
include_once("controller/ProfileController.php");
include_once("controller/PartidaController.php");

include_once("model/LoginModel.php");
include_once("model/HomeModel.php");
include_once("model/RegisterModel.php");
include_once("model/ProfileModel.php");
include_once("model/PartidaModel.php");


include_once('vendor/mustache/src/Mustache/Autoloader.php');

class Configuration
{
    public function __construct() {}


    public function getHomeController()
    {
        return new HomeController($this->getPresenter(), $this->getHomeModel());
    }

    public function getRegisterController()
    {
        return new RegisterController($this->getRegisterModel(), $this->getPresenter());
    }

    public function getProfileController()
    {
        return new ProfileController($this->getProfileModel(), $this->getPresenter());
    }

    public function getPartidaController()
    {
        return new PartidaController($this->getPresenter(), $this->getPartidaModel());
    }


    public function getLogoutController()
    {
        return new LogoutController();
    }
    public function getLoginController()
    {
        return new LoginController($this->getPresenter(), $this->getLoginModel());
    }
    private function getRegisterModel()
    {
        return new RegisterModel($this->getDatabase(), $this->getEmailHelper());
    }
    private function getHomeModel()
    {
        return new HomeModel($this->getDatabase());
    }

    private function getLoginModel()
    {
        return new LoginModel($this->getDatabase());
    }

    private function getProfileModel()
    {
        return new ProfileModel($this->getDatabase());
    }

    private function getPartidaModel()
    {
        return new PartidaModel($this->getDatabase());
    }

    private function getPresenter()
    {
        return new MustachePresenter("./view");
    }

    private function getDatabase()
    {
        $config = parse_ini_file('configuration/config.ini');
        return new MysqlObjectDatabase(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password'],
            $config["database"]
        );
    }

    public function getRouter()
    {
        return new Router(
            $this,
            "getHomeController",
            "list"
        );
    }

    public function getEmailHelper()
    {
        return new EmailHelper();
    }
}
