<!doctype html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="/assets/apple-touch-icon.png">
    <link rel="shortcut icon" href="/assets/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css">
    <style>
        body {
            padding-top: 50px;
            padding-bottom: 20px;
        }
    </style>
    <link rel="stylesheet" href="/assets/css/main.css">


    <script src="/assets/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    <script src="/assets/js/vendor/jquery-1.11.2.min.js"></script>
</head>
<body style="padding:0; margin:0;">
<!--[if lt IE 8]>
<p class="browserupgrade">Egy <strong>elavult</strong> böngészőt használsz. Kérlek <a
        href="http://browsehappy.com/">frissítsd a böngésződ </a>, hogy javíts a felhasználói élményeden!</p>
<![endif]-->

<nav class="navbar navbar-inverse navbar-static" style="margin-top:0;" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Közösségi háló</a>
        </div>
        <?php if (auth()): ?>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-center">
                <li></li>
            </ul>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span><?=Auth::user()['firstname']." ".Auth::user()['lastname']?></span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/profile/<?=Auth::user()['id']?>">Profilom</a></li>
                            <li><a href="/ownprofile">Profil szerkesztése</a></li>
                            <li><a href="/logout">Kijelentkezés</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!--/.navbar-collapse -->

            <?php else: ?>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a data-toggle="modal" href="#" data-target="#myModal">Bejelentkezés / Regisztráció</a></li>
                    </ul>
                </div><!--/.navbar-collapse -->
            <?php endif; ?>
        </div>
</nav>

<?php if (!auth()) : ?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Bejelentkezés/Regisztráció</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" style="border-right: 1px dotted #C2C2C2;padding-right: 30px;">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#Login" data-toggle="tab">Bejelentkezés</a></li>
                            <li><a href="#Registration" data-toggle="tab">Regisztráció</a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="Login">
                                <form role="form" action="login" method="POST" class="form-horizontal">
                                    <div class="form-group">
                                        <label for="email" class="col-sm-2 control-label">
                                            Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" name="email" id="email1"
                                                   placeholder="Email"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1" class="col-sm-2 control-label">
                                            Jelszó</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" name="password"
                                                   id="exampleInputPassword1"
                                                   placeholder="Jelszó"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2">
                                        </div>
                                        <div class="col-sm-10">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Bejelentkezés
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="Registration">
                                <form role="form" action="/register" method="POST" class="form-horizontal">
                                    <div class="form-group">
                                        <label for="firstname" class="col-sm-2 control-label">
                                            Név</label>
                                        <div class="col-sm-10">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="lastname"
                                                           placeholder="Családnév" required/>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="firstname"
                                                           placeholder="Utónév" required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="col-sm-2 control-label">
                                            E-mail</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" name="email" id="email"
                                                   placeholder="Email" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="col-sm-2 control-label">
                                            Jelszó</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" name="password" id="password"
                                                   placeholder="Jelszó" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="col-sm-2 control-label">
                                            Jelszó újra</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" name="password_again"
                                                   id="password"
                                                   placeholder="Jelszó újra" required/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="col-sm-2 control-label">
                                            Nem</label>
                                        <div class="col-sm-10">
                                            <select name="gender" required>
                                                <option value="1">Férfi</option>
                                                <option value="2">Nő</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="col-sm-2 control-label">
                                            Szül. Dátum</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" name="birthdate" id="password"
                                                   placeholder="Születési dátum" required/>
                                            <i>Formátum: <?= date('Y.m.d'); ?></i>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2">
                                        </div>
                                        <div class="col-sm-10">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Regisztráció
                                            </button>
                                            <button type="button" class="btn btn-default btn-sm">
                                                Mégse
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; // end auth ?>