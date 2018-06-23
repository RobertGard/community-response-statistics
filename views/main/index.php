<br>
<br>

<div class="container">
    <div class="row">
        <form method="POST">
            <div class="form-group field-order-name required">
                <label class="control-label" for="order-name">Токен подключения</label>
                    <input type="text" name="token" value="<?= $_POST['token'] ?>" class="form-control" />
            </div>
            <div class="form-group field-order-name required">
                <label class="control-label" for="order-name">ID группы</label>
                    <input type="text" name="group" value="<?= $_POST['group'] ?>" class="form-control" />
            </div>
            <div class="form-group field-order-name required">
                <label class="control-label" for="order-name">Дата</label>
                    <input type="date" name="date" value="<?= $_POST['date'] ?>" class="form-control" >
            </div>
            <input type="submit" class="btn btn-success">
        </form>
    </div><!-- /.col-lg-6 -->
    
<br>
<br>

<?php if ($displayResult === TRUE) : ?>

    <br/><br/>
        <h4>Статистика</h4>
        
    <table border="1" class="table table-hover table-striped">
        <tr>
            <td>Макс.</td>
            <td><?= BaseObject::formatTime($maxTime) ?></td>
        </tr>
        <tr>
            <td>Мин.</td>
            <td><?= BaseObject::formatTime($minTime) ?></td>
        </tr>
        <tr>
            <td>Сред.</td>
            <td><?= BaseObject::formatTime($middleTime) ?></td>
        </tr>
    </table>
    
    <br/><br/>
        <h4>Сообщения с момента которых прошло более 15 минут</h4>
    
    <table border="1" class="table table-hover table-striped">
        <thead>
            <th>#</th>
            <th>Сообщение</th>
            <th>Ссылка</th>
            <th>Прошло времени</th>
        </thead>
        <tbody>
            <?php for($i = 0; $i < count($more15min); $i++) : ?>
                <tr>
                    <td><?= ($i + 1) ?></td>
                    <td><?= $more15min[$i]['messageText'] ?></td>
                    <td><a href="https://vk.com/gim<?= $groupId ?>?sel=<?= $more15min[$i]['peerId'] ?>" target="_blank">Cсылка</a></td>
                    <td><?= BaseObject::formatTime($more15min[$i]['lastMessageTime']) ?></td>
                </tr>
            <?php endfor ?>
        </tbody>
    </table>
<?php endif ?>

</div>