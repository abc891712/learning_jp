@extends('study.layouts.template')

@section('content')
    <form id="form" enctype="multipart/form-data">
        <div class="row">
            <div class="file-field input-field col-md-6 col s6">
                <div class="btn">
                    <span>File</span>
                    <input id="excel" type="file" name="excel">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                </div>
            </div>
            <div class="col-2 col s2 input-field">
                <a class="btn btn-primary right " @click.prevent="uploadExcel">上傳</a>
            </div>
        </div>
    </form>
    <template v-if="errors">
        <div class="section red-text">
            <ul>
                <li v-for="error in errors">@{{ error  }}</li>
            </ul>
        </div>
    </template>
@endsection

@section('js')
    <script>
        var vm = new Vue({
            el: "#app",
            data: {
                status: 0,
                errors: [],
            },
            methods: {
                uploadExcel() {
                    let that = this;
                    let fileExtension = ($("#excel").val().split(".").pop().toLowerCase());
                    if( fileExtension === "" ){
                        that.$toast('尚未選取資料');
                        return ;
                    }

                    if( fileExtension!=="xlsx" && fileExtension !== "xls" ){
                        that.$toast('格式錯誤');
                        return ;
                    }

                    let url = '{{ route('save') }}';
                    let excelFile = $('#form')[0];
                    let data = new FormData(excelFile);

                    $.ajax({
                        url: url,
                        data: data,
                        headers:{
                            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'post',
                        cache: false,
                        contentType : false,
                        processData : false,
                        success: function (res) {
                            if (res.status === 200){
                                that.$toast({
                                    message: '上傳成功',
                                    position: 'middle',
                                    duration: 800,
                                })
                            }
                            if (res.hasOwnProperty('errors')) {
                                $.each(res.errors,function (index,message) {
                                    let nth = index.split('.').reverse().pop();
                                    message = '第'+nth+'筆資料'+message;
                                    that.errors.push(message);
                                })
                            }
                        },
                    })
                },
            }
        });
    </script>
@endsection
