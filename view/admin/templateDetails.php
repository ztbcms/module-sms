<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <el-col :sm="24" :md="18">
            <template>
                <div>
                    <el-form ref="elForm" :model="formData"  size="medium" label-width="150px">

                        <div v-for="(v, k) in table">
                            <el-form-item :label="v.remarks" prop="area_code">
                                <el-input v-model="table[k].val" :placeholder="v.name" clearable
                                          :style="{width: '100%'}"></el-input>
                            </el-form-item>
                        </div>

                        <el-form-item size="large">
                            <el-button type="primary" @click="submitForm">提交</el-button>
                        </el-form-item>
                    </el-form>
                </div>
            </template>
        </el-col>
    </el-card>
</div>

<script>
    $(document).ready(function () {
        new Vue({
            el: '#app',
            // 插入export default里面的内容
            components: {},
            props: [],
            data: function () {
                return {
                    table : [

                    ],
                    formData: {}
                }
            },
            computed: {},
            watch: {},
            created: function () {
            },
            mounted: function () {
                this.getDetails();
            },
            methods: {
                //获取详情
                getDetails :function () {
                    var that = this;
                    $.ajax({
                        url: "{:api_url('/sms/Admin/templateDetails')}",
                        data: {
                            action : "getTableParameters",
                            platform : "{$_GET['platform']}"
                        },
                        type: "post",
                        dataType: 'json',
                        success: function (res) {
                            that.table = res.data.parameters;
                        }
                    })
                },
                submitForm: function () {
                    var that = this;
                    var data = {
                        action : 'addTableParameters',
                        platform : "{$_GET['platform']}",
                        table : that.table
                    };
                    $.ajax({
                        url: "{:api_url('/sms/Admin/templateDetails')}",
                        data: data,
                        type: "post",
                        dataType: 'json',
                        success: function (res) {
                            if (res.status) {
                                //添加成功
                                layer.msg(res.msg);
                            } else {
                                layer.msg(res.msg)
                            }
                        }
                    })
                }
            }
        })
    })
</script>

<style>

</style>
