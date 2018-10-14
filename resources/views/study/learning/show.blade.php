@extends('study.layouts.template')

@section('content')
    <!-- Modal Structure -->
    <div v-show="modalShow">
        <div id="modal1" class="modal">
            <div class="modal-content">
                <h5>編輯</h5>
                <div class="row">
                    <form class="col s12">
                        <div class="row">
                            <div class="col s12">
                                <label>日文</label>
                                <p class="text-danger" v-if="errors.japanese">@{{ errors.japanese }}</p>
                                <input v-model="modalWord.japanese" id="modalJapanese" type="text">
                            </div>
                            <div class="col s12">
                                <label for="modalLevel">級別</label>
                                <select id="modalLevel" v-model="modalWord.level">
                                    <option disabled>請選擇</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="col s12">
                                <label>姓名</label>
                                <p class="text-danger" v-if="errors.word">@{{ errors.word }}</p>
                                <input v-model="modalWord.word" id="modalWord" type="text">
                            </div>
                            <div class="col s12">
                                <label>中文</label>
                                <p class="text-danger" v-if="errors.chinese ">@{{ errors.chinese }}</p>
                                <input v-model="modalWord.chinese" id="modalChinese" type="text">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <div class="center">
                    <a  class="waves-effect waves-green btn-flat" @click="store()">儲存</a>
                    <a  class="modal-close waves-effect waves-green btn-flat">關閉</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row container-fluid">
        <div class="section col s10 col-10">
            <table class="highlight responsive-table">
                <thead>
                    <tr>
                        @auth
                        <th>選擇新增</th>
                        @endauth
                        <th>日文</th>
                        <th>級別</th>
                        <th>漢字</th>
                        <th>中文</th>
                        @auth
                        <th></th>
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    <template v-for="word,index in words">
                        <tr>
                            @auth
                            <td><label><input type="checkbox" :value="word.id" v-model="selected_words"><span></span></label></td>
                            @endauth
                            <td>@{{ word.japanese }}</td>
                            <td>@{{ word.level }}</td>
                            <td>@{{ word.word }}</td>
                            <td>@{{ word.chinese }}</td>
                            @auth
                            <td>
                                <a class="waves-effect waves-light btn modal-trigger btn-floating btn cyan pulse"
                                    href="#modal1" @click="edit(index,word.id)">
                                    <i class="material-icons">edit</i>
                                </a>
                            </td>
                            @endauth
                        </tr>
                    </template>
                </tbody>
            </table>
            @auth
            <div class="section right">
                <a class="btn btn-primary" @click="storeWords">新增</a>
            </div>
            @endauth
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
    <input type="hidden" id="update" value="{{ route('update') }}">
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
                modalWord: {
                    id: "",
                    japanese: "",
                    level: "",
                    word: "",
                    chinese: "",
                },
                modalShow: false,
                wordIndex: "",
                errors: {},
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
                            if (res.status === 200) {
                                that.words = res.datas.data;
                                that.paginate.currentPage = res.datas.current_page;
                                that.paginate.pageCount = res.datas.last_page;
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
                            if (res.status === 200) {
                                that.$toast({
                                    message: '新增成功',
                                    position: 'middle',
                                    duration: 700,
                                });
                            }
                        }
                    });
                },
                store: function () {
                    let that = this;
                    let data = this.modalWord;

                    $.ajax({
                        url: $('#update').val(),
                        headers:{'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
                        data: data,
                        type: 'put',
                        success: function (res) {console.log(res)
                            if (res.status == 200) {
                                that.$toast({
                                    message: '修改成功',
                                    position: 'middle',
                                    duration: 700,
                                });
                                that.words[that.wordIndex]= that.modalWord;
                                $('#modal1').modal('close');
                                that.modalShow = false;
                            }
                            if (res.status == 422) {
                                that.errors = res.errors;
                            }
                        }
                    });
                },
                edit: function (index,japanese_id) {
                    //避免 object reference
                    var word = Object.assign({}, this.words[index]);
                    this.modalWord = word;
                    this.wordIndex = index;
                    this.modalWord.id = japanese_id;
                    this.modalShow = true;
                },
            }
        });
    </script>
@endsection
