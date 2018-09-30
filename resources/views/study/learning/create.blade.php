@extends('study.layouts.template')

@section('content')
    <form id="form_data">
        <div class="form-group">
            <div class="row">
                <div class="col-10 col s10">
                    <label for="japanese"><h5>日文</h5></label>
                    <input id="japanese" name="japanese" type="text" class="form-control">
                    <template v-if="show_japanese_errors">
                        <div class="red-text"><h6>@{{ error_messages.japanese }}</h6></div>
                    </template>
                </div>
                <div class="col-10 col s10">
                    <label for="level"><h5>級別</h5></label>
                    <select id="level" name="level">
                        <option disable>請選擇</option>
                        <option value="1" selected>1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    <template v-if="show_level_errors">
                        <div  class="red-text"><h6>@{{ error_messages.level }}</h6></div>
                    </template>
                </div>
                <div class="col-10 col s10">
                    <label for="word"><h5>中日</h5></label>
                    <input id="word" name="word" type="text" class="form-control">
                    <template v-if="show_word_errors">
                        <div class="red-text"><h6>@{{ error_messages.word }}</h6></div>
                    </template>
                </div>
                <div class="col-10 col s10">
                    <label for="chinese"><h5>中文</h5></label>
                    <input id="chinese" name="chinese" type="text" class="form-control">
                    <template v-if="show_chinese_errors">
                        <div class="red-text"><h6>@{{ error_messages.chinese }}</h6></div>
                    </template>
                </div>
            </div>
        </div>
        <a class="btn waves-effect waves-light" @click="addWord">送出</a>
    </form>
    <input type="hidden" id="store" value="{{ route('store') }}">
@endsection

@section('js')
    <script>
        var vm = new Vue({
            el: "#app",
            data: {
                error_messages: [],
                status: 0,
                show_japanese_errors: false,
                show_level_errors: false,
                show_word_errors: false,
                show_chinese_errors: false,
            },
            watch: {
                status: function (status) {
                    if (status==422){
                        if (this.error_messages.japanese){
                            this.show_japanese_errors = true;
                        }
                        if (this.error_messages.level){
                            this.show_level_errors = true;
                        }
                        if (this.error_messages.word){
                            this.show_word_errors = true;
                        }
                        if (this.error_messages.chinese){
                            this.show_chinese_errors = true;
                        }
                    }else{
                        this.show_japanese_errors = false;
                        this.show_level_errors = false;
                        this.show_word_errors = false;
                        this.show_chinese_errors = false;
                    }
                }
            },
            methods: {
                addWord() {
                    let that = this;
                    let url = $("#store").val();
                    let data = $('#form_data').serializeArray();

                    $.ajax({
                        url: url,
                        data: data,
                        headers:{
                            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'post',
                        cache: false,
                        success: function (res) {
                            if (res.status==200){
                                that.status = res.status;
                                that.$toast({
                                    message: '新增成功',
                                    position: 'middle',
                                    duration: 800,
                                })
                            }
                            if (res.status==202){
                                that.status = res.status;
                                that.$toast({
                                    message: res.word.word+'已存在',
                                    position: 'middle',
                                    duration: 800,
                                })
                            }
                            if (res.status==422){
                                that.error_messages = res.error_messages;
                                that.status = res.status;
                            }
                        },
                    })
                },
            }
        });
    </script>
@endsection
