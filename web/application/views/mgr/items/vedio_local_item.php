<tr data-id="<?= $item['id'] ?>">
    <td><?= $item['id'] ?></td>
    <td><?= $item['title'] ?></td>
   
    <td><?= $item['link'] ?></td>
    <td><?= $item['content'] ?></td>
    <td><?=$item['create_date']?></td>
   
    <td>        
        <button class="btn btn-primary btn-xs" onclick="location.href='<?= base_url() ?>mgr/vedio/edit/<?= $item['id'] ?>';" data-toggle="tooltip" data-original-title="編輯"><i class="fa fa-fw ti-pencil"></i></button>
        <button class="btn btn-danger btn-xs del-btn" data-toggle="tooltip" data-original-title="刪除抽獎活動"><i class="fa fa-fw ti-trash"></i></button>
    </td>
</tr>