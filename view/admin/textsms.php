<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <el-col :sm="24" :md="18">
            <template>
                <div>
                    <el-form ref="elForm" :model="formData"  size="medium" label-width="100px">

                        <el-form-item label="手机区号" prop="area_code">
                            <el-input v-model="formData.area_code" placeholder="请输入手机区号" clearable
                                      :style="{width: '100%'}"></el-input>
                        </el-form-item>

                        <el-form-item label="手机号" prop="phone">
                            <el-input v-model="formData.phone" placeholder="请输入手机号" clearable
                                      :style="{width: '100%'}"></el-input>
                        </el-form-item>

                        <el-form-item label="参数">
                            <div>
                                <template v-for="(file, index) in formData.content">
                                    <div>
                                        <p style="margin-top: 0;">

                                            <el-input v-model="file.code" size="small"
                                                      style="margin-left:5px;width: 100px;"
                                                      placeholder="code">
                                            </el-input>

                                            <el-input v-model="file.value" size="small"
                                                      style="margin-left:5px;width: 100px;" placeholder="参数">
                                            </el-input>

                                            <span style="font-size: 22px"
                                                  class="el-icon-delete"
                                                  @click="delContent(index)">
                                            </span>
                                        </p>
                                    </div>
                                </template>
                            </div>

                            <el-button type="primary" @click="addContent('file_data')">添加</el-button>
                        </el-form-item>

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
                    formData: {
                        alias: "{$_GET['alias']}",
                        area_code: '86',
                        phone: '',
                        content: [],
                        action: "aliasSend",
                        platform: "{$_GET['platform']}"
                    }
                }
            },
            computed: {},
            watch: {},
            created: function () {
            },
            mounted: function () {
                this.addContent();
            },
            methods: {
                submitForm: function () {
                    var that = this;
                    // TODO 提交表单

                    var content = {};
                    that.formData.content.map(function (item) {
                        if(item.code) content[item.code] = item.value
                    });

                    var data = {
                        alias : that.formData.alias,
                        area_code : that.formData.area_code,
                        phone : that.formData.phone,
                        action : that.formData.action,
                        platform : that.formData.platform,
                        content : content
                    };

                    $.ajax({
                        url: "{:api_url('/sms/Admin/textsms')}",
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
                },
                //添加参数内容
                addContent :function () {
                    var item = {
                        code : '',
                        value : ''
                    };
                    this.formData.content.push(item)
                },
                //删除参数内容
                delContent :function (index) {
                    Vue.delete(this.formData.content, index);
                }
            }
        })
    })
</script>

<style>

</style>
