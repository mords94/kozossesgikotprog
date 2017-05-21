<div class="container" id="Profilkep">
    <h3><?=$user['lastname']?> <?=$user['firstname']?>
    </h3>
    <div class="form-group">
        <label for="photo" class="col-sm-2 control-label">
            Fénykép</label>
        <?php if($user['id'] ==  Auth::user()['id']): ?>
            <a href="/photo" class="btn btn-info"><i class="glyphicon glyphicon-pencil"> Feltöltés</i></a>
        <?php endif;?>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="photo" id="photo" placeholder="URL" value="<?=$user['photo_id']?>" required/>
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
</div>