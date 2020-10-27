<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <el-table
                :key="tableKey"
                :data="logs"
                fit
                highlight-current-row
                style="width: 100%;"
        >
            <el-table-column label="平台" align="center">
                <template slot-scope="scope">
                    <span>{{ scope.row.operator }}</span>
                </template>
            </el-table-column>

            <el-table-column label="区号" align="center">
                <template slot-scope="scope">
                    <span>{{ scope.row.area_code }}</span>
                </template>
            </el-table-column>

            <el-table-column label="手机号" align="center">
                <template slot-scope="scope">
                    <span>{{ scope.row.recv }}</span>
                </template>
            </el-table-column>

            <el-table-column label="param" align="center">
                <template slot-scope="scope">
                    <span>{{ scope.row.param }}</span>
                </template>
            </el-table-column>

            <el-table-column label="result" align="center">
                <template slot-scope="scope">
                    <span>{{ scope.row.result }}</span>
                </template>
            </el-table-column>

            <el-table-column label="result" align="center">
                <template slot-scope="scope">
                    <span>{{ scope.row.result }}</span>
                </template>
            </el-table-column>

            <el-table-column label="发送日期" align="center">
                <template slot-scope="scope">
                    <span>{{ scope.row.sendtime | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>
                </template>
            </el-table-column>

        </el-table>

        <div class="pagination-container">
            <el-pagination
                    background
                    layout="prev, pager, next, jumper"
                    :total="total"
                    v-show="total > 0"
                    :page-count="page_count"
                    :current-page.sync="listQuery.page"
                    :page-size.sync="listQuery.limit"
                    @current-change="getList"
            >
            </el-pagination>
        </div>

    </el-card>
</div>

<style>

    .pagination-container {
        padding: 32px 16px;
    }
</style>
<script>
    $(document).ready(function () {
        new Vue({
            el: '#app',
            data: {
                form: {},
                logs: [],
                tableKey: 0,
                page_count: 0,
                total: 0,
                listQuery: {
                    page: 1,
                    limit: 20,
                    action: "ajaxList"
                }
            },
            watch: {},
            filters: {
                parseTime: function (time, format) {
                    return Ztbcms.formatTime(time, format)
                }
            },
            methods: {
                getList: function () {
                    var that = this;
                    $.ajax({
                        url: '{:api_url("/sms/Admin/smsLog")}',
                        data: that.listQuery,
                        type: 'get',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status) {
                                that.logs = res.data.items;
                                that.total = res.data.total_page;
                                that.page_count = res.data.total_pages;
                                that.listQuery.page = res.data.page;
                            }
                        }
                    });
                },
            },
            mounted: function () {
                this.getList();
            }
        })
    })
</script>
