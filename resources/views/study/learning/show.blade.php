@extends('study.layouts.template')

@section('content')
    <div class="row container-fluid">
        <div class="section col s10 col-10">
            <table class="highlight responsive-table">
                <thead>
                    <tr>
                        <th>選擇新增</th>
                        <th>日文</th>
                        <th>級別</th>
                        <th>漢字</th>
                        <th>中文</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="word in words">
                        <tr>
                            <td><label><input type="checkbox" :value="word.id" v-model="selected_words"><span></span></label></td>
                            <td>@{{ word.japanese }}</td>
                            <td>@{{ word.level }}</td>
                            <td>@{{ word.word }}</td>
                            <td>@{{ word.chinese }}</td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <div class="section right">
                <a class="btn btn-primary" @click="storeWords">新增</a>
            </div>

            <paginate
                    :page-count="paginate.pageCount"
                    :click-handler="clickPage"
                    :prev-text="'上一頁'"
                    :next-text="'下一頁'"
                    :container-class="'pagination center'"
                    :value="paginate.currentPage"
                    :page-range="paginate.range"
            >
            </paginate>
        </div>
        <div class="section row">
            <div class="col s2 col-2 right">
                <select v-model="selected_option">
                    <option value="12345">全部</option>
                    <option value="45">初級</option>
                    <option value="3">中級</option>
                    <option value="12">高級</option>
                </select>
            </div>
        </div>
    </div>
    <input type="hidden" id="words-list" value="{{ route('list') }}">
    <input type="hidden" id="store-words" value="{{ route('reserve') }}">
@endsection

@section('js')
    <script>
        var vm = new Vue({
            el: '#app',
            data: {
                words: {},
                paginate: {
                    pageCount: 1,
                    currentPage: 1,
                    range: 5,
                },
                selected_words: [],
                selected_option: 12345,
            },
            mounted: function () {
                this.getList(1,this.selected_option);
            },
            watch: {
              selected_option: function (level) {
                  this.getList(1,level);
              }
            },
            methods: {
                getList: function (page,level) {
                    let that = this;

                    $.ajax({
                        type: 'get',
                        url: $('#words-list').val(),
                        data: {
                            page: page,
                            level: level,
                        },
                        success: function (res) {
                            if (res.status === 202) {
                                that.words = res.datas.data;
                                that.paginate.currentPage = res.datas.current_page;
                                that.paginate.pageCount = res.datas.last_page;
                                console.log(res);
                            }
                        }
                    });
                },
                clickPage: function (pageNum) {
                    this.paginate.currentPage = pageNum;
                    this.getList(pageNum,this.selected_option);
                    this.$toast({
                        message: `切換到第${pageNum}頁`,
                        position: 'middle',
                        duration: 700,
                    });
                },
                storeWords: function () {
                    let that = this;
                    let datas = that.selected_words;

                    $.ajax({
                        type: 'post',
                        url: $('#store-words').val(),
                        headers:{'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
                        data: {'datas' : datas},
                        success: function (res) {
                            if (res.status === 202) {
                                that.$toast({
                                    message: '新增成功',
                                    position: 'middle',
                                    duration: 700,
                                });
                            }
                        }
                    });
                }
            }
        });
    </script>
@endsection
