<form class="item-contact jsform">
    <div class="form-group">
      <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
        <input  name="name" placeholder="Nombre y Apellido" class="form-control"  type="text">
      </div>
    </div>
    <div class="form-group">
      <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
        <input  name="email" placeholder="Email" class="form-control"  type="email">
      </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
            <input  name="phone" placeholder="Teléfono" class="form-control"  type="text">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
            <textarea class="form-control" name="message" rows="9" placeholder="Mensaje"></textarea>
        </div>
    </div>
    <input type="hidden" name="place" value="<?=$place?>">
    <div class="jsError"></div>
    <input type="submit" class="btn btn-primary" value="Enviar">
</form>