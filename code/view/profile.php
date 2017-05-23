<!--
User Profile Sidebar by @keenthemes
A component of Metronic Theme - #1 Selling Bootstrap 3 Admin Theme in Themeforest: http://j.mp/metronictheme
Licensed under MIT
-->

<div class="container">
    <div class="row profile">
        <div class="col-md-3">
            <div class="profile-sidebar">

                <div class="profilkep_outer text-center" <?=Auth::user()['id'] != $user['id']? 'style="cursor:pointer;" onclick="window.location.href=\'/photo/'.$user['id'].'\'"' : ''?>>
                    <div class="profilkep_container text-center">
                        <img src="<?= $photo['src'] ?>" title="<?= $photo['title'] ?>" alt="<?= $photo['title'] ?>"
                             class="profilkep"/>
                       <?php if(Auth::user()['id'] == $user['id']): ?> <label class="kepfeltoltes btn btn-success">
                            Kép feltöltés
                            <input type="file" accept="image/*"/>
                            <input type="hidden"/>
                        </label>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name">
                        <?= $user['firstname'] ?> <?= $user['lastname'] ?>
                    </div>
                </div>
                <div class="profile-userbuttons">
                    <?php if ($user['id'] == Auth::user()['id']): ?>
                        <a href="/ownprofile" class="btn btn-info"><i class="glyphicon glyphicon-pencil"></i> Szerkesztés</a>
                    <?php else: ?>
                        <?php if ($known): ?>
                            <form method="post" action="/deleteFriend" style="display:inline;">
                                <input type="hidden" name="friend" value="<?= $user['id'] ?>"/>
                                <button type="submit" class="btn btn-danger">
                                    <i class="glyphicon glyphicon-remove"></i> Ismerős törlése
                                </button>
                            </form>
                        <?php elseif ($requestSent): ?>
                            <form method="post" action="/deleteFriend" style="display:inline;">
                                <input type="hidden" name="friend" value="<?= $user['id'] ?>"/>
                                <button type="submit" class="btn btn-warning">
                                    <i class="glyphicon glyphicon-user"></i> Ismerősnek jelölve
                                </button>
                            </form>
                        <?php elseif ($requestGot): ?>
                            <form method="post" action="/approve" style="display:inline;">
                                <input type="hidden" name="friend" value="<?= $user['id'] ?>"/>
                                <button type="submit" class="btn btn-primary">
                                    Megerősítés
                                </button>
                            </form>
                            <form method="post" action="/deleteFriend" style="display:inline;">
                                <input type="hidden" name="friend" value="<?= $user['id'] ?>"/>
                                <button type="submit" class="btn btn-default">
                                    Elutasítás
                                </button>
                            </form>
                        <?php else: ?>
                            <form method="post" action="/addFriend" style="display:inline;">
                                <input type="hidden" name="friend" value="<?= $user['id'] ?>"/>
                                <button type="submit" class="btn btn-success">
                                    <i class="glyphicon glyphicon-user"></i> Ismerősnek jelölöm
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="profile-usermenu">
                    <ul class="nav">
                        <li class="active">
                            <a href="#">
                                <i class="glyphicon glyphicon-home"></i>
                                Adatlap </a>
                        </li>
                        <li>
                            <a href="/friends/<?=$user['id']?>">
                                <i class="glyphicon glyphicon-user"></i>
                                Ismerősök </a>
                        </li>
                        <li>
                            <a href="/findfriends">
                                <i class="glyphicon glyphicon-search"></i>
                                Kit ismerhetek? </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="profile-content">
                <div id="Profil">
                    <div class="form-group">

                        <div class="col-sm-12">
                            <span><i class="fa fa-envelope"></i> E-mail:</span>
                            <?= $user['email'] ?>
                        </div>

                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <span><i class="fa fa-venus-mars"></i> Nem:</span>
                            <?php
                            $gender = [0 => 'Férfi', 1 => 'Nő'];
                            echo $gender[$user['gender']];
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <span><i class="fa fa-birthday-cake"></i> Szül. Dátum</span>
                            <?= $user['birthdate'] ?>
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="form-group">
                        <h5>Iskolák</h5>
                        <hr>
                        <div class="col-sm-12">
                            <?php foreach ($schools as $school): ?>

                                <?= $school['selected'] ? $school['name'] . '<br>' : '' ?>

                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <h5>Munkahelyek</h5>
                        <hr>
                        <div class="col-sm-12">
                            <?php foreach ($workplaces as $workplace): ?>

                                <?= $workplace['selected'] ? $workplace['name'] . '<br>' : '' ?>

                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <h5>Klubok</h5>
                        <hr>
                        <div class="col-sm-12">
                            <?php foreach ($clubs as $club): ?>

                                <?= $club['selected'] ? $club['name'] . '<br>' : '' ?>

                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<br>
<br>

<br>
<br>
<br>
<br>


<br>
<br>
<br>
<br>

<script>
    File.prototype.convertToBase64 = function (callback) {
        var FR = new FileReader();
        FR.onload = function (e) {
            callback(e.target.result)
        };
        FR.readAsDataURL(this);
    };
    $('input[type=file]').on('change', function (e) {
        var selectedFile = this.files[0];
        selectedFile.convertToBase64(function (base64) {
            $.post('/upload_picture', {photo: base64}, function (data) {
                if (data !== "SUCCESS") {
                    alert('Hiba történt a kép feltöltésekor! :(');
                    console.log(data);
                } else {
                    $('.profilkep').attr('src', base64);
                }
            })
        });
    });


</script>