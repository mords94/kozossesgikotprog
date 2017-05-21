<div class="panel container">
    <div class="panel-body">
      <h1><i class="fa fa-pencil"></i> Profil szerkesztése</h1>
    </div>
</div>
<div class="panel container">
    <div class="panel-body">
        <?php if ($message): ?>
            <h5 class="row text-center text-success"><?= $message ?></h5>
            <meta http-equiv="refresh" content="3;url=/profile/<?= Auth::user()['id'] ?>"/>
        <?php endif; ?>
        <form role="form" action="/update_profile" method="POST" class="form-horizontal">
            <div class="form-group">
                <label for="firstname" class="col-sm-2 control-label">
                    Név</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="lastname" placeholder="Családnév"
                                   value="<?= $user['lastname'] ?>" required/>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="firstname" placeholder="Utónév"
                                   value="<?= $user['firstname'] ?>" required/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">
                    E-mail</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                           value="<?= $user['email'] ?>" disabled required/>
                </div>

            </div>

            <div class="form-group">
                <label for="gender" class="col-sm-2 control-label">
                    Nem</label>
                <div class="col-sm-10">
                    <select name="gender" style="padding: 0 20px 0 0;" id="gender" required>
                        <option value="1" <?= $user['gender'] == 1 ? 'selected' : '' ?>>Férfi</option>
                        <option value="2" <?= $user['gender'] == 2 ? 'selected' : '' ?>>Nő</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="birthdate" class="col-sm-2 control-label">
                    Szül. Dátum</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control" name="birthdate" id="birthdate"
                           value="<?= $user['birthdate'] ?>"
                           placeholder="Születési dátum" required disabled/>
                </div>
            </div>

            <div class="form-group">
                <label for="schools" class="col-sm-2 control-label">
                    Iskolák</label>
                <div class="col-sm-10">
                    <select name="schools[]" class="multi-select" style="width:100%" multiple>
                        <?php foreach ($schools as $school): ?>

                            <option value="<?= $school['id'] ?>" <?= $school['selected'] ? 'selected' : '' ?>><?= $school['name'] ?></option>

                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="workplaces" class="col-sm-2 control-label">
                    Munkahelyek</label>
                <div class="col-sm-10">
                    <select name="workplaces[]" style="width:100%" class="multi-select" multiple>
                        <?php foreach ($workplaces as $workplace): ?>

                            <option value="<?= $workplace['id'] ?>" <?= $workplace['selected'] ? 'selected' : '' ?>>
                                <?= $workplace['name'] ?></option>

                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary btn-sm">
                        Mentés
                    </button>
                    <button type="button" class="btn btn-default btn-sm">
                        Mégse
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".multi-select").select2();
    });
</script>