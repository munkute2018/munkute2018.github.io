$(function() {
    var url_add = $(".btn-add-grid").attr("data-url");
    var url_import = $(".btn-import-grid").attr("data-url");
    var url_nhasx = $(".btn-show-nhasx").attr("data-url");
    var url_nuocsx = $(".btn-show-nuocsx").attr("data-url");
    loadEvent();
    function loadEvent(){
        $(".btn-add-grid").off('click').on('click', (function(event){  
            btnShowModalInGrid($(this), url_add);
        }));

        $(".btn-show-nhasx").off('click').on('click', (function(event){  
            btnShowModalInGrid($(this), url_nhasx);
        }));

        $(".btn-show-nuocsx").off('click').on('click', (function(event){  
            btnShowModalInGrid($(this), url_nuocsx);
        }));

        $(".btn-import-grid").off('click').on('click', (function(event){  
            btnShowModalInGrid($(this), url_import);
        }));

        $(".btn-reload-grid").off('click').on('click', (function(event){  
            btnProcessMoreInGridView($(this), 1);
        }));

        $(".btn-update-grid").off('click').on('click', (function(event){  
            btnProcessMoreInGridView($(this), 3);
        }));

        $(".btn-delete-grid").off('click').on('click', (function(event){  
            btnProcessMoreInGridView($(this), 2);
        }));

        $(".activity-edit").off('click').on('click', (function(event){  
            event.preventDefault();
            btnEditInGridView($(this));
            return false;
        }));

        $(".activity-reload").off('click').on('click', (function(event){  
            event.preventDefault();
            btnProcessSingleInGridView($(this), 1);
            return false;
        }));

        $(".btn-reset-role").off('click').on('click', (function(event){  
            event.preventDefault();
            changeGroupUserRole($('#group_user'));
            return false;
        }));

        $(".btn-submit-role").off('click').on('click', (function(event){  
            event.preventDefault();
            saveGroupUserRole($('#group_user'), $('input[name=role-menu]'));
            return false;
        }));

        $('#group_user').off('change').on('change', (function(event) {
            event.preventDefault();
            changeGroupUserRole($(this));
            return false;
        }));

        $(".activity-delete").off('click').on('click', (function(event){  
            event.preventDefault();
            btnProcessSingleInGridView($(this), 2);
            return false;
        }));

        $(".activity-lock").off('click').on('click', (function(event){  
            event.preventDefault();
            btnChangeStatus($(this), 1);
            return false;
        }));

        $(".activity-unlock").off('click').on('click', (function(event){  
            event.preventDefault();
            btnChangeStatus($(this), 0);
            return false;
        }));

        $('#group_thamso').off('change').on('change', (function(event) {
            event.preventDefault();
            changeThamSo($(this));
            return false;
        }));
    }
    $(document).on('ready pjax:success', function() {
        loadEvent();
    });

    function btnShowModalInGrid($object, $urlaction){
        $object.attr("disabled", "disabled");
        $.ajax({
            url: $urlaction,               
            type: 'post',    
            dataType: 'JSON',                      
            beforeSend: function() {
            },
            success: function(response){
                if(response.status){
                    $('#addModal').modal('show').find('.modal-body').html(response.data);
                    $('#addModal').find('.modal-title').html(response.title);
                    eventFormModal();
                }
                else{
                    toastr.warning("",response.message); 
                } 
            },
            complete: function() { 
                $object.removeAttr("disabled");  
            },
            error: function (jqXHR, timeout, message) {
                if (jqXHR.status === 403) {
                    window.location.reload();
                }
                else{
                    toastr.warning("",'Vui lòng kiểm tra lại dữ liệu hoặc kết nối của bạn!');
                } 
            }
        });
    }

    function btnEditInGridView($object){
        $object.attr("disabled", "disabled");
        $.ajax({
            url: $object.attr("data-url"),               
            type: 'post',    
            dataType: 'JSON',                      
            beforeSend: function() {
            },
            success: function(response){
                if(response.status){
                    $('#addModal').modal('show').find('.modal-body').html(response.data);
                    $('#addModal').find('.modal-title').html(response.title);
                    eventFormModal();
                }
                else{
                    toastr.warning("",response.message); 
                } 
            },
            complete: function() { 
                $object.removeAttr("disabled");  
            },
            error: function (jqXHR, timeout, message) {
                if (jqXHR.status === 403) {
                    window.location.reload();
                }
                else{
                    toastr.warning("",'Vui lòng kiểm tra lại dữ liệu hoặc kết nối của bạn!');
                } 
            }
        });
    }

    function btnProcessSingleInGridView($object, $type){
        var content = '';
        switch($type){
            case 1: content = '<p>Bạn có chắc muốn tính toán lại đơn giá của dòng dữ liệu (DVTT là <b>'+$object.attr("data-dvtt")+'</b> và mã dịch vụ là <b>'+$object.attr("data-madv")+'</b>) hay không?</p>';
            break;
            case 2: content = '<p>Bạn có chắc muốn xóa dòng dữ liệu ('+$object.attr("data-confirm")+') hay không?</p>';
            break;
            default: content = '';
        }
        var modalHtml = '<section class="section">\
                            <div class="row">\
                                <div class="col-xs-12 col-sm-12 col-md-12">\
                                    '+content+'\
                                </div>\
                            </div>\
                        </section>';
        var buttonHtml = '<button type="button" class="btn btn-info" data-dismiss="modal">Hủy bỏ</button>\
                            <button type="button" class="btn btn-danger btn-submit-row">Xác nhận</button>';
        $('#confirmModal').modal('show').find('.modal-body').html(modalHtml);  
        $('#confirmModal').find('.modal-title').html('Xác nhận'); 
        $('#confirmModal').find('.modal-footer').html(buttonHtml);
        $(".btn-submit-row").off('click').on('click', (function(event){
            var objectClick = $(this);  
            objectClick.attr("disabled", "disabled");
            $('#confirmModal').block({
                message: "Đang xử lý dữ liệu. Vui lòng đợi...", css: {
                    border: 'none',
                    padding: '10px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                }
            });
            $.ajax({
                url: $object.attr("data-url"),               
                type: 'post',    
                dataType: 'JSON',                      
                beforeSend: function() {
                },
                success: function(response){
                    $('#confirmModal').unblock();
                    objectClick.removeAttr("disabled"); 
                    if(response.status){
                        toastr.success("",response.message);
                        $('#mygrid').yiiGridView('applyFilter');
                        $('#confirmModal').modal('hide');
                    }
                    else{
                        toastr.warning("",response.message);
                        if(response.hideModal){
                            $('#mygrid').yiiGridView('applyFilter');
                            $('#confirmModal').modal('hide');
                        }
                    }
                },
                complete: function() { 
                    $object.removeAttr("disabled");  
                },
                error: function (jqXHR, timeout, message) {
                    if (jqXHR.status === 403) {
                        window.location.reload();
                    }
                    else{
                        $('#confirmModal').unblock();
                        $('#confirmModal').modal('hide');
                        toastr.warning("",'Vui lòng kiểm tra lại dữ liệu hoặc kết nối của bạn!');
                    } 
                }
            });
        }));       
    }

    function btnProcessMoreInGridView($object, $type){
        var listkey = $('#mygrid').yiiGridView('getSelectedRows');
        if(listkey.length == 0){
            toastr.warning("","Bạn cần chọn dòng dữ liệu cần xử lý!");
        }
        else{
            var content = '';
            var xacnhan = 'Xác nhận';
            switch($type){
                case 1: content = '<p>Bạn có chắc muốn tính toán lại đơn giá của <b>'+listkey.length+'</b> dòng dữ liệu đã chọn hay không?</p>';
                        xacnhan = 'Xác nhận';

                break;
                case 2: content = '<p>Bạn có chắc muốn xóa <b>'+listkey.length+'</b> dòng dữ liệu đã chọn hay không?</p>';
                        xacnhan = 'Xác nhận';
                break;
                case 3: content = '<div class="form-group">\
                                        <input type="text" class="form-control" id="fgiatri" max-length="255" placeholder="Nhập giá trị...." autocomplete="off">\
                                    </div>';
                        xacnhan = 'Cập nhật cấu hình ('+$object.attr('data-thamso')+')';
                break;
                default: content = ''
                        xacnhan = '';
            }
            var modalHtml = '<section class="section">\
                                <div class="row">\
                                    <div class="col-xs-12 col-sm-12 col-md-12">\
                                        '+content+'\
                                    </div>\
                                </div>\
                            </section>';
            var buttonHtml = '<button type="button" class="btn btn-warning" data-dismiss="modal">Hủy bỏ</button>\
                                <button type="button" class="btn btn-primary btn-submit-all">Xác nhận</button>';
            $('#confirmModal').find('.modal-title').html(xacnhan);
            $('#confirmModal').modal('show').find('.modal-body').html(modalHtml);  
            $('#confirmModal').find('.modal-footer').html(buttonHtml);
            $(".btn-submit-all").off('click').on('click', (function(event){
                var objectClick = $(this);  
                objectClick.attr("disabled", "disabled");
                $('#confirmModal').block({
                    message: "Đang xử lý dữ liệu. Vui lòng đợi...", css: {
                        border: 'none',
                        padding: '10px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .5,
                        color: '#fff'
                    }
                });
                if($type == 3){
                    value = {'list': listkey, 'id_thamso': $object.attr('data-thamso'), 'fgiatri': $("#fgiatri").val()};
                }
                else
                    value = {'value': listkey};
                $.ajax({
                    url: $object.attr("data-url"),               
                    type: 'post', 
                    data: value , 
                    dataType: 'JSON',                      
                    beforeSend: function() {
                    },
                    success: function(response){
                        $('#confirmModal').unblock();
                        objectClick.removeAttr("disabled"); 
                        if(response.status){
                            toastr.success("",response.message);
                            $('#mygrid').yiiGridView('applyFilter');
                            $('#confirmModal').modal('hide');
                        }
                        else{
                            toastr.warning("",response.message);
                            if(response.hideModal){
                                $('#mygrid').yiiGridView('applyFilter');
                                $('#confirmModal').modal('hide');
                            }
                        }
                    },
                    complete: function() { 
                        $object.removeAttr("disabled");  
                    },
                    error: function (jqXHR, timeout, message) {
                        if (jqXHR.status === 403) {
                            window.location.reload();
                        }
                        else{
                            $('#confirmModal').unblock();
                            $('#confirmModal').modal('hide');
                            toastr.warning("",'Vui lòng kiểm tra lại dữ liệu hoặc kết nối của bạn!');
                        } 
                    }
                });
            })); 
        }      
    }

    function btnChangeStatus($object, $status){
        var content = '';
        switch($status){
            case 0: content = '<p>Bạn có chắc muốn mở khóa phiếu có mã <b>'+$object.attr("data-phieu")+'</b> để điều chỉnh hay không?</p>';
            break;
            case 1: content = '<p>Bạn có chắc muốn xác nhận phiếu có mã <b>'+$object.attr("data-phieu")+'</b> đã hoàn thành hay không?</p>';
            break;
            default: content = '';
        }
        var modalHtml = '<section class="section">\
                            <div class="row">\
                                <div class="col-xs-12 col-sm-12 col-md-12">\
                                    '+content+'\
                                </div>\
                            </div>\
                        </section>';
        var buttonHtml = '<button type="button" class="btn btn-info" data-dismiss="modal">Hủy bỏ</button>\
                            <button type="button" class="btn btn-danger btn-submit-status">Xác nhận</button>';
        $('#confirmModal').modal('show').find('.modal-body').html(modalHtml);  
        $('#confirmModal').find('.modal-title').html('Xác nhận'); 
        $('#confirmModal').find('.modal-footer').html(buttonHtml);
        $(".btn-submit-status").off('click').on('click', (function(event){
            var objectClick = $(this);  
            objectClick.attr("disabled", "disabled");
            $('#confirmModal').block({
                message: "Đang xử lý dữ liệu. Vui lòng đợi...", css: {
                    border: 'none',
                    padding: '10px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                }
            });
            $.ajax({
                url: $object.attr("data-url"),               
                type: 'post', 
                data: {'status': $status} ,   
                dataType: 'JSON',                      
                beforeSend: function() {
                },
                success: function(response){
                    $('#confirmModal').unblock();
                    objectClick.removeAttr("disabled"); 
                    if(response.status){
                        toastr.success("",response.message);
                        $('#mygrid').yiiGridView('applyFilter');
                        $('#confirmModal').modal('hide');
                    }
                    else{
                        toastr.warning("",response.message);
                        if(response.hideModal){
                            $('#mygrid').yiiGridView('applyFilter');
                            $('#confirmModal').modal('hide');
                        }
                    }
                },
                complete: function() { 
                    $object.removeAttr("disabled");  
                },
                error: function (jqXHR, timeout, message) {
                    if (jqXHR.status === 403) {
                        window.location.reload();
                    }
                    else{
                        $('#confirmModal').unblock();
                        $('#confirmModal').modal('hide');
                        toastr.warning("",'Vui lòng kiểm tra lại dữ liệu hoặc kết nối của bạn!');
                    } 
                }
            });
        }));       
    }

    function eventFormModal(){ 
        $('#form-modal :input[type=submit]').on('click',function()
        {
            $(this).attr('disabled','disabled');
            $('#addModal').block({
                message: "Đang xử lý dữ liệu. Vui lòng đợi...", css: {
                    border: 'none',
                    padding: '10px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                }
            });
            $('#form-modal').submit();
        });

        $('#form-modal').on('afterValidateAttribute', function (event, attribute, message) {
            if(message!=''){
                $('#form-modal :input[type=submit]').attr('disabled', false);
                $('#addModal').unblock();
            }
        });

        $('#form-modal').on('ajaxComplete', function (event, xhr, settings) {
            if (xhr.status === 403) {
                window.location.reload();
            }
            else if (xhr.status !== 200) {
                $('#addModal').unblock();
                $('#form-modal :input[type=submit]').attr('disabled', false);
                toastr.warning("",'Vui lòng kiểm tra lại dữ liệu hoặc kết nối của bạn!');
            } 
        });

        $("#form-modal").on('beforeSubmit', function (event) { 
            var form_data = new FormData($('#form-modal')[0]);
            $.ajax({
                url: $("#form-modal").attr('action'), 
                dataType: 'JSON',  
                cache: false,
                contentType: false,
                processData: false,
                data: form_data, //$(this).serialize(),                      
                type: 'post',                        
                beforeSend: function() {
                },
                success: function(response){ 
                    $('#addModal').unblock();
                    if(response.status){                       
                        toastr.success("",response.message); 
                        $('#mygrid').yiiGridView('applyFilter');
                        $('#addModal').modal('hide');
                    }
                    else{
                        toastr.warning("",response.message); 
                        if(response.hideModal){
                            $('#mygrid').yiiGridView('applyFilter');
                            $('#addModal').modal('hide');
                        }
                        $('#form-modal :input[type=submit]').attr('disabled', false);
                    }
                },
                complete: function() {
                },
                error: function (jqXHR, timeout, message) {
                    if (jqXHR.status === 403) {
                        window.location.reload();
                    }
                    else{
                        $('#addModal').unblock();
                        $('#form-modal :input[type=submit]').attr('disabled', false); 
                        toastr.warning("",'Vui lòng kiểm tra lại dữ liệu hoặc kết nối của bạn!');
                    } 
                }
            });                
            return false;
        });
    }

    function changeGroupUserRole($object) {
        $('.role-zone').html('');
        $.blockUI({
            message: "Đang tải dữ liệu. Vui lòng đợi...", css: {
                border: 'none',
                padding: '10px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
            }
        });
        $.ajax({
            url: 'load-phan-quyen',               
            type: 'post',
            data: { 'group' : $object.val() },
            dataType: 'JSON',                      
            beforeSend: function() {
            },
            success: function(response){
                $.unblockUI();
                if(response.status){
                    $('.role-zone').html(response.data);
                }
                else{
                    toastr.warning("",response.message); 
                } 
            },
            complete: function() { 
            },
            error: function (jqXHR, timeout, message) {
                if (jqXHR.status === 403) {
                    window.location.reload();
                }
                else{
                    $.unblockUI();
                    toastr.warning("",'Vui lòng kiểm tra lại dữ liệu hoặc kết nối của bạn!');
                } 
            }
        });
    }

    function saveGroupUserRole($object, $input) {
        $.blockUI({
            message: "Đang xử lý dữ liệu. Vui lòng đợi...", css: {
                border: 'none',
                padding: '10px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
            }
        });
        $.ajax({
            url: 'save-phan-quyen',               
            type: 'post',
            data: { 'group' : $object.val(), 'value' : $input.val() },
            dataType: 'JSON',                      
            beforeSend: function() {
            },
            success: function(response){
                $.unblockUI();
                if(response.status){
                    toastr.success("",response.message);
                }
                else{
                    toastr.warning("",response.message); 
                } 
            },
            complete: function() { 
            },
            error: function (jqXHR, timeout, message) {
                if (jqXHR.status === 403) {
                    window.location.reload();
                }
                else{
                    $.unblockUI();
                    toastr.warning("",'Vui lòng kiểm tra lại dữ liệu hoặc kết nối của bạn!');
                } 
            }
        });
    }

    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

    if( isMobile.any() ) {
        $(window).resize(function () {
            if ($dialog.hasClass('kv-popover-active')) {
                self.refreshPosition();
            }
        });
    }
});

