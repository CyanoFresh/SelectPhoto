Yii2 EL Template
============================

This is my template for quick project development. Yii2 Basic template Lightweight + Environments + Refactor

EL = Environments Lightweight

Common uses: simple web applications without database, landing pages with forms, ajax applications

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      runtime/            contains files generated during runtime
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources


INSTALLATION
------------

Get project files:

~~~
git clone https://github.com/CyanoFresh/yii2-el-template PROJECTNAME
cd PROJECTNAME
composer install
~~~

If installation has not started, run init command manually:

~~~
php init
~~~

Also check files in `config` folder for more customization
