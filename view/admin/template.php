<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <el-row>
            <el-col :span="5"><h3>模板列表</h3></el-col>
        </el-row>

        <el-button type="primary" size="mini" @click="templateDetails(0)">新增模板</el-button>

        <el-table
                :key="tableKey"
                :data="tableData"
                highlight-current-row
                style="width: 100%;"
        >

            <div v-for="(v, k) in table">
                <el-table-column :label="v.remarks" align="" :prop="v.name"></el-table-column>
            </div>

            <el-table-column label="操作" align="center" width="230" class-name="small-padding fixed-width">
                <template slot-scope="scope">

                    <div style="margin-bottom: 5px;">
                        <el-button type="primary" size="mini" @click="templateDetails(scope.row.id)">编辑</el-button>
                        <el-button type="danger" size="mini" @click="delsms(scope.row.id)">删除短信</el-button>
                    </div>

                    <el-button type="success" size="mini" @click="textsms(scope.row.alias)">测试</el-button>

                </template>
            </el-table-column>
        </el-table>
    </el-card>
</div>

<script>
    $(document).ready(function () {
        new Vue({
            el: '#app',
            data: {
                table : [],
                tableKey: 0,
                tableData: [],
                total: 0,
                where: {
                    page: 1,
                    limit: 20,
                    type: "",
                    action: 'ajaxList',
                    platform: "{$_GET['platform']}"
                }
            },
            watch: {},
            filters: {},
            methods: {
                //获取模板列表
                getList: function () {
                    var that = this;
                    $.ajax({
                        url: "{:api_url('/sms/Admin/template')}",
                        data: that.where,
                        type: 'get',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status) {
                                that.tableData = res.data.template;
                            }
                        }
                    });
                },
                //获取详情
                getTableDetails :function () {
                    var that = this;
                    $.ajax({
                        url: "{:api_url('/sms/Admin/templateDetails')}",
                        data: {
                            action : "getTableParameters",
                            platform : "{$_GET['platform']}",
                        },
                        type: "post",
                        dataType: 'json',
                        success: function (res) {
                            that.table = res.data.parameters;
                        }
                    })
                },
                //删除短信
                delsms :function (id) {
                    var that = this;
                    $.ajax({
                        url: "{:api_url('/sms/Admin/template')}",
                        data: {
                            id : id,
                            action : 'delsms',
                            platform : "{$_GET['platform']}"
                        },
                        type: 'get',
                        dataType: 'json',
                        success: function (res) {
                            that.getList();
                        }
                    });
                },
                //发送测试短信
                textsms: function (alias) {
                    var url = '{:api_url("/sms/Admin/textsms")}&alias='+alias+'&platform='+"{$_GET['platform']}";
                    this.__link(url);
                },
                //模板详情
                templateDetails :function (id) {
                    var url = '{:api_url("/sms/Admin/templateDetails")}&platform='+"{$_GET['platform']}";
                    url += '&id=' + id;
                    this.__link(url);
                },
                __link :function (url) {
                    var that = this;
                    layer.open({
                        type: 2,
                        title: '管理',
                        content: url,
                        area: ['95%', '95%'],
                        end: function () {
                            that.getList();
                        }
                    });
                }
            },
            mounted: function () {

                this.getTableDetails();

                this.getList();

            }
        })
    })
</script>
