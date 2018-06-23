<form method="POST">
    <input type="text" name="token" value="<?= $_POST['token'] ?>"/>
    <br/>
    <input type="text" name="group" value="<?= $_POST['group'] ?>"/>
    <br/>
    <input type="date" name="date" value="<?= $_POST['date'] ?>">
    <br/>
    <input type="submit">
</form>

<?php if ($displayResult === TRUE) : ?>
    <table border="1">
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
        Сообщения с момента которых прошло более 15 минут
    <br/><br/>
    
    <table border="1">
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