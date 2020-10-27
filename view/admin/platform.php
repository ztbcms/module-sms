<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <el-row>
            <el-col :span="5"><h3>平台列表</h3></el-col>
        </el-row>

        <el-table
                :key="tableKey"
                :data="tableData"
                highlight-current-row
                style="width: 100%;"
        >
            <el-table-column label="运营商名称" align="center">
                <template slot-scope="scope">
                    <span>{{ scope.row.name }}</span>
                </template>
            </el-table-column>

            <el-table-column label="平台模块" align="">
                <template slot-scope="scope">
                    <span>{{ scope.row.tablename }}</span>
                </template>
            </el-table-column>

            <el-table-column label="操作" align="center" width="230" class-name="small-padding fixed-width">
                <template slot-scope="scope">
                    <el-button type="primary" size="mini" @click="templateList(scope.row.tablename)">模板列表</el-button>
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
                tableKey: 0,
                tableData: [],
                total: 0,
                where: {
                    page: 1,
                    limit: 20,
                    type: "",
                    action: 'ajaxList'
                },
            },
            watch: {},
            filters: {
                parseTime: function (time, format) {
                    return Ztbcms.formatTime(time, format)
                },
                ellipsis: function (value) {
                    if (!value) return "";
                    if (value.length > 120) {
                        return value.slice(0, 120) + "...";
                    }
                    return value;
                }
            },
            methods: {
                getList: function () {
                    var that = this;
                    $.ajax({
                        url: "{:api_url('/sms/Admin/platform')}",
                        data: that.where,
                        type: 'get',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status) {
                                that.tableData = res.data;
                                that.total = 1;
                            }
                        }
                    });
                },
                templateList: function (tablename) {
                    var that = this;
                    $.ajax({
                        url: "{:api_url('/sms/Admin/isExistPlatform')}",
                        data: {
                            'platform': tablename
                        },
                        type: 'post',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status) {
                                var url = '{:api_url("/sms/Admin/template")}&platform=' + tablename;
                                layer.open({
                                    type: 2,
                                    title: '管理',
                                    content: url,
                                    area: ['95%', '95%'],
                                    end: function () {
                                        that.getList();
                                    }
                                })
                            } else {
                                layer.msg(res.msg);
                            }
                        }
                    });
                }
            },
            mounted: function () {
                this.getList();
            }
        })
    })
</script>
