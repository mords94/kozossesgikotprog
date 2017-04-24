
<div class="container" id="Profil">

        <h3><?=$user['lastname']?> <?=$user['firstname']?>
            <?php if($user['id'] ==  Auth::user()['id']): ?>
                <a href="/ownprofile" class="btn btn-info"><i class="glyphicon glyphicon-pencil"> Szerkesztés</i></a>
            <?php endif;?>
        </h3>
        <img src="/assets/images/user.png" class="profilkep" />
        <div class="form-group">
            <label for="email" class="col-sm-12 control-label">
                E-mail</label>
            <div class="col-sm-12">
                <?=$user['email']?>
            </div>

        </div>
        <div class="form-group">
            <label for="gender" class="col-sm-12 control-label">
                Nem</label>
            <div class="col-sm-12">
                <?php
                $gender = [1=>'Férfi', 2 => 'Nő'];
                echo $gender[$user['gender']];
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="birthdate" class="col-sm-12 control-label">
                Szül. Dátum</label>
            <div class="col-sm-12">
                <?=$user['birthdate']?>
            </div>
        </div>

        <div class="form-group">
            <label for="schools" class="col-sm-12 control-label">
                Iskolák</label>
            <div class="col-sm-12">
                    <?php foreach($schools as $school):?>

                        <?=$school['selected'] ? $school['name']. '<br>' : ''?>

                    <?php endforeach;?>
            </div>
        </div>


</div>

<br>
<br>
<br>
<br>