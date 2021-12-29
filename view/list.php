<div class="row" style="margin-top: 10vh;">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
        <button class="btn btn-primary" onclick="openModel(0,'',0)">
            <i class="fa fa-plus"></i> Thêm mới
        </button>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 10px;">
        <table class="table table-hover  table-bordered">
            <thead>
                <tr>
                    <th class="text-center">STT</th>
                    <th>Category name</th>
                    <th class="text-center">Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $k = 1;
                foreach ($data['data'] as $item) { ?>
                    <tr>
                        <td class="text-center">
                            <?php echo $k; ?>
                        </td>
                        <td>
                            <?php if ($item['level'] != 0) {
                                $str = '';
                                for ($i = 1; $i <= $item['level']; $i++) {
                                    $str .= '<span class="children"></span>';
                                    if ($i == $item['level']) {
                                        $str .= '<span class="children-last"></span>';
                                    }
                                }
                                echo $str;
                            } else {
                                echo $str = '<span class="parent"></span>';
                            }
                            ?>
                            <?php echo $item['name'] ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-xs" onclick="openModel(<?php echo $item['id'] ?>,'<?php echo $item['name'] ?>',<?php echo $item['parent_id'] ?>)">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-primary btn-xs" onclick="copyName('<?php echo $item['name'] ?>')">
                                <i class="fa fa-copy"></i>
                            </button>
                            <a href="/category/deleteCategory.php?id=<?php echo $item['id'] ?>" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php $k++;
                } ?>
            </tbody>
        </table>
    </div>

    <?php $total_page = ceil($data['total'] / $limit); ?>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        Hiển thị từ <?php echo $page ?> đến <?php echo $page - 1 + COUNT($data['data']) ?> trên tổng số <?php echo $data['total'] ?>

        <ul class="pagination pull-right">
            <?php if ($page > 1 && $total_page > 1) { ?>
                <li><a href="/category/index.php?page=<?php echo $page - 1 ?>">&laquo;</a></li>
            <?php } ?>

            <?php for ($i = 1; $i <= $total_page; $i++) {
                if ($i == $page) {
                    echo '<li><span>' . $i . '</span> </li>';
                } else {
                    echo '<li><a href="/category/index.php?page=' . $i . '">' . $i . '</a> </li> ';
                }
            }
            ?>

            <?php
            if ($page < $total_page && $total_page > 1) { ?>
                <li><a href="/category/index.php?page=<?php echo $page + 1 ?>">&raquo;</a></li>
            <?php } ?>
        </ul>
    </div>
</div>

<div class="modal fade" id="category">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <form action="postCategory.php" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" id="idctgr" value="" name="id">
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" class="form-control" name="name" value="name" required="required" id="nameCt">
                            </div>
                            <div class="form-group">
                                <label for="">parent Category</label>
                                <select class="form-control" required="required" id="parentId" name="parent_id">
                                    <option value="0">--No--</option>
                                    <?php foreach ($list as $key => $value) { ?>
                                        <option value="<?php echo $value->id ?>"><?php echo $value->name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="btnsave" type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function openModel(id, name, prid) {
        $('.modal-title').text(id > 0 ? 'Update category' : 'Create category');
        $('.btnsave').text(id > 0 ? 'Save changes' : 'Save');
        $('#nameCt').val(name);
        $('#idctgr').val(id);
        $('#parentId').val(prid).change();;
        $('#category').modal('show');
    }

    function copyToClipboard(text) {
        var sampleTextarea = document.createElement("textarea");
        document.body.appendChild(sampleTextarea);
        sampleTextarea.value = text; //save main text in it
        sampleTextarea.select(); //select textarea contenrs
        document.execCommand("copy");
        document.body.removeChild(sampleTextarea);
    }

    function copyName(name) {
        copyToClipboard(name);
    }
</script>

<style>
    .parent {
        position: relative;
        padding-left: 20px;
    }

    .children,
    .children-last {
        margin-left: 20px;
        padding-left: 20px;
        position: relative;
    }

    .children {
        padding-left: 0;
    }

    .children-last:before,
    .parent:before {
        content: ' ';
        position: absolute;
        display: block;
        width: 1px;
        height: 35px;
        border-left: 1px dashed #000;
        top: -12px;
        left: 0;
    }

    .children-last:after,
    .parent:after {
        content: ' ';
        position: absolute;
        display: block;
        width: 10px;
        height: 1px;
        background-color: #000;
        top: 8px;
        left: 0;
    }
</style>