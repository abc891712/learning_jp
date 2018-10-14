@extends('study.layouts.template')

@section('content')
    <div class="row container-fluid">
        <div class="section">
            <table class="highlight responsive-table">
                <thead>
                <tr>
                    <th>日文</th>
                    <th>級別</th>
                    <th>漢字</th>
                    <th>中文</th>
                    <th>刪除</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="word in userNotes">
                    <tr>
                        <td>@{{ word.japanese }}</td>
                        <td>@{{ word.level }}</td>
                        <td>@{{ word.word }}</td>
                        <td>@{{ word.chinese }}</td>
                        <td><a class="btn-floating pulse red" @click="destroy(word.id)"><i class="material-icons">close</i></a></td>
                    </tr>
                </template>
                </tbody>
            </table>
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
    </div>
    <input type="hidden" id="destroy" value="{{ route('destroy') }}">
    <input type="hidden" id="get-user-notes" value="{{ route('getUserNotes') }}">
@endsection

@section('js')
    <script>
        var vm = new Vue({
            el: '#app',
            data: {
                userNotes: {},
                paginate: {
                    pageCount: 1,
                    currentPage: 1,
                    range: 5,
                },
            },
            mounted:function () {
                this.getWords(1);
            },
            methods:{
                getWords: function (page) {
                    let that = this;

                    $.ajax({
                        url: $('#get-user-notes').val(),
                        type: 'get',
                        data:{page:page},
                        success: function (res) {
                            if (res.status === 200) {
                                that.userNotes = res.userNotes.data;
                                that.paginate.currentPage = res.userNotes.current_page;
                                that.paginate.pageCount = res.userNotes.last_page;
                            }
                        }
                    });
                },
                clickPage: function (pageNum) {
                    this.paginate.currentPage = pageNum;
                    this.getWords(pageNum);
                    this.$toast({
                        message: `切換到第${pageNum}頁`,
                        position: 'middle',
                        duration: 700,
                    });
                },
                destroy: function(id) {
                    let that = this;
                    $.ajax({
                        url: $('#destroy').val(),
                        type: 'delete',
                        headers:{'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
                        data: {'id':id},
                        success: function () {
                            that.getWords(1);
                            that.$toast({
                                message: '刪除成功',
                                position: 'middle',
                                duration: 700,
                            });
                        }
                    });
                }
            }
        });
    </script>
@endsection
