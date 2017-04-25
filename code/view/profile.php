<br>
<br>
<br>
<br>
<div class="container" id="Profil">

    <h3><?= $user['lastname'] ?> <?= $user['firstname'] ?>
        <a href="/friends/<?= $user['id'] ?>" class="btn btn-default">
            <i class="glyphicon glyphicon-user"> </i> Barátai
            <?= ($user['id'] == Auth::user()['id']) ? 'm' : '' ?>
        </a>
        <?php if ($user['id'] == Auth::user()['id']): ?>
            <a href="/ownprofile" class="btn btn-info"><i class="glyphicon glyphicon-pencil"></i> Szerkesztés</a>
        <?php else: ?>
            <?php if ($known): ?>
                <form method="post" action="/deleteFriend" style="display:inline;">
                    <input type="hidden" name="friend" value="<?= $user['id'] ?>"/>
                    <button type="submit" class="btn btn-danger">
                        <i class="glyphicon glyphicon-user"></i> Ismerős törlése
                    </button>
                </form>
            <?php elseif ($requestSent): ?>
                <form method="post" action="/deleteFriend" style="display:inline;">
                    <input type="hidden" name="friend" value="<?= $user['id'] ?>"/>
                    <button type="submit" class="btn btn-warning">
                        <i class="glyphicon glyphicon-user"></i> Ismerősnek jelölve
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
    </h3>

    <img src="/assets/images/user.png" class="profilkep"/>
    <div class="form-group">
        <label for="email" class="col-sm-12 control-label">
            E-mail</label>
        <div class="col-sm-12">
            <?= $user['email'] ?>
        </div>

    </div>
    <div class="form-group">
        <label for="gender" class="col-sm-12 control-label">
            Nem</label>
        <div class="col-sm-12">
            <?php
            $gender = [1 => 'Férfi', 2 => 'Nő'];
            echo $gender[$user['gender']];
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="birthdate" class="col-sm-12 control-label">
            Szül. Dátum</label>
        <div class="col-sm-12">
            <?= $user['birthdate'] ?>
        </div>
    </div>

    <div class="form-group">
        <label for="schools" class="col-sm-12 control-label">
            Iskolák</label>
        <div class="col-sm-12">
            <?php foreach ($schools as $school): ?>

                <?= $school['selected'] ? $school['name'] . '<br>' : '' ?>

            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group">
        <label for="workplaces" class="col-sm-12 control-label">
            Munkahelyek</label>
        <div class="col-sm-12">
            <?php foreach($workplaces as $workplace):?>

                <?=$workplace['selected'] ? $workplace['name']. '<br>' : ''?>

            <?php endforeach;?>
        </div>
    </div>

</div>

<br>
<br>
<br>
<br>