<table class="table table-responsive">
    <tr>
        <th style="text-align: center">文章内容</th>
    </tr>
    <?php foreach ($model as $value):?>
    <tr>
        <td><?=$value->content?></td>
    </tr>
    <?php endforeach;?>
</table>
