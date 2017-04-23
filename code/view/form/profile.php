<div class="tab-pane" id="Profilom">
    <form role="form" action="/register" method="POST" class="form-horizontal">
        <div class="form-group">
            <label for="firstname" class="col-sm-2 control-label">
                Név</label>
            <div class="col-sm-10">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="lastname" placeholder="Családnév" required/>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="firstname" placeholder="Utónév" required/>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">
                E-mail</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required/>
            </div>

        </div>

        <div class="form-group">
            <label for="password" class="col-sm-2 control-label">
                Nem</label>
            <div class="col-sm-10">
                <select name="gender" required>
                    <option value="1">Férfi   </option>
                    <option value="2">Nő      </option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-sm-2 control-label">
                Szül. Dátum</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="birthdate" id="password"
                       placeholder="Születési dátum" required/>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-10">
                <button type="submit" class="btn btn-primary btn-sm">
                    Vissza a Kezdőlapra
                </button>
                <button type="button" class="btn btn-default btn-sm">
                    Mégse
                </button>
            </div>
        </div>
    </form>
</div>