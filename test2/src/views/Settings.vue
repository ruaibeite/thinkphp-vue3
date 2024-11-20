<template>
    <div>
      <!-- 显示错误消息 -->
      <el-alert v-if="errorMessage" :title="errorMessage" type="error" show-icon></el-alert>
  
      <!-- 搜索框 -->
      <el-input
        v-model="searchQuery"
        placeholder="请输入搜索内容"
        clearable
        @clear="handleSearch"
        style="width: 250px; margin-bottom: 20px; display: inline-block"
      />
      <el-button type="primary" @click="handleSearch" style="margin-left: 10px">搜索</el-button>
      <el-button @click="handleReset" style="margin-left: 10px">重置</el-button>
      
      <!-- 添加数据按钮 -->
      <el-button type="primary" @click="handleAdd" style="margin-left: 10px">添加数据</el-button>
  
      <!-- 数据表格 -->
      <el-table :data="currentPageData" border style="width: 100%">
        <el-table-column prop="data" label="Data" />
        <el-table-column prop="name" label="Name" width="180" />
        <el-table-column prop="address" label="Address" />
        <el-table-column label="操作">
          <template #default="{ row }">
            <el-button type="danger" size="small" @click="handleDelete(row)">
              删除
            </el-button>
            <el-button type="primary" size="small" @click="handleEdit(row)" style="margin-left: 10px">
              编辑
            </el-button>
          </template>
        </el-table-column>
      </el-table>
  
      <!-- 分页组件 -->
      <el-pagination
        :current-page="currentPage"
        :page-size="pageSize"
        :total="filteredData.length"
        @current-change="handlePageChange"
        layout="total, prev, pager, next, jumper"
        background
      />
      <!-- 编辑数据的 Dialog -->
      <el-dialog title="编辑数据" v-model="editDialogVisible" width="40%">
        <el-form :model="currentRow" ref="formRef" label-width="80px">
          <el-form-item label="Data" :label-width="'80px'">
            <el-input v-model="currentRow.data" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="Name" :label-width="'80px'">
            <el-input v-model="currentRow.name" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="Address" :label-width="'80px'">
            <el-input v-model="currentRow.address" autocomplete="off"></el-input>
          </el-form-item>
        </el-form>
        <span slot="footer" class="dialog-footer">
          <el-button @click="editDialogVisible = false">取消</el-button>
          <el-button type="primary" @click="handleSave">保存</el-button>
        </span>
      </el-dialog>
  
      <!-- 添加数据的 Dialog -->
      <el-dialog title="添加数据" v-model="addDialogVisible" width="40%" @close="resetForm">
        <el-form :model="newData" ref="form" label-width="100px">
          <el-form-item label="Data" :rules="[ { required: true, message: '请输入数据', trigger: 'blur' } ]">
            <el-input v-model="newData.data" />
          </el-form-item>
          <el-form-item label="Name" :rules="[ { required: true, message: '请输入名称', trigger: 'blur' } ]">
            <el-input v-model="newData.name" />
          </el-form-item>
          <el-form-item label="Address" :rules="[ { required: true, message: '请输入地址', trigger: 'blur' } ]">
            <el-input v-model="newData.address" />
          </el-form-item>
        </el-form>
  
        <div slot="footer" class="dialog-footer">
          <el-button @click="addDialogVisible = false">取消</el-button>
          <el-button type="primary" @click="handleSubmit">提交</el-button>
        </div>
      </el-dialog>
      
    </div>
  </template>
  
  <script lang="ts" setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  import { ElMessage, ElButton, ElDialog } from 'element-plus'
  
  const tableData = ref<any[]>([]) // 用于存储所有表格数据
  const currentPageData = ref<any[]>([]) // 当前页面显示的数据
  const errorMessage = ref<string | null>(null) // 用于存储错误消息
  const searchQuery = ref('') // 用于存储搜索框输入的内容
  const currentRow = ref<any>({});  // 当前编辑行的数据
  // 分页相关的变量
  const currentPage = ref(1) // 当前页
  const pageSize = ref(10) // 每页显示的条数
  
  // 用于存储筛选后的数据
  const filteredData = ref<any[]>([])
  
  // 新数据对象和 Dialog 显示状态
  const dialogVisible = ref(false) // 控制 Dialog 弹出
  const newData = ref({
    data: '',
    name: '',
    address: ''
  })
  
  const editDialogVisible = ref(false) // 控制编辑 Dialog 的显示
  const addDialogVisible = ref(false) // 控制编辑 Dialog 的显示
  // 获取数据的方法
  const fetchData = async () => {
    try {
      const token = sessionStorage.getItem('token');
      if (!token) {
        errorMessage.value = 'Token 不存在，访问被拒绝';
        tableData.value = [];
        return;
      }
  
      // 向后台请求数据
      const response = await axios.get('http://localhost:8000/api/data/getdata', {
        headers: {
          Authorization: `${token}` // 发送 token 作为认证
        }
      });
  
      if (response.data.code === 1) {
        tableData.value = response.data.data; // 更新表格数据
        filteredData.value = tableData.value; // 默认显示所有数据
        errorMessage.value = null; // 清空错误消息
        updatePageData(); // 更新当前页数据
      } else {
        errorMessage.value = response.data.msg; // 显示错误消息
        tableData.value = [];
        filteredData.value = [];
      }
    } catch (error) {
      console.error('Failed to fetch data:', error);
      errorMessage.value = '数据加载失败，请稍后重试';
      tableData.value = []; // 清空表格数据
      filteredData.value = []; // 清空筛选数据
    }
  }
  
  // 更新当前页数据
  const updatePageData = () => {
    const start = (currentPage.value - 1) * pageSize.value;
    const end = start + pageSize.value;
    currentPageData.value = filteredData.value.slice(start, end); // 获取当前页的数据
  }
  
  // 分页切换时更新数据
  const handlePageChange = (page: number) => {
    currentPage.value = page;
    updatePageData(); // 更新当前页的数据
  }
  
  // 删除操作
  const handleDelete = async (row: any) => {
    try {
      const token = sessionStorage.getItem('token'); // 从 sessionStorage 获取 token
      if (!token) {
        errorMessage.value = 'Token 不存在，访问被拒绝';
        return;
      }
      const response = await axios.delete(`http://localhost:8000/api/data/delete/${row.id}`, {
        headers: {
          Authorization: `${token}` 
        }
      });
  
      console.log(response.data);
      let cleanedData = response.data;
      if (typeof cleanedData === 'string' && cleanedData.startsWith(row.id.toString())) {
        cleanedData = cleanedData.substring(row.id.toString().length); // 去掉 row.id 的长度部分
        cleanedData = JSON.parse(cleanedData); 
      }
  
      if (cleanedData.code === 1) {
        fetchData(); 
        const index = tableData.value.findIndex(item => item.id === row.id); // 找到对应的行
        if (index !== -1) {
          tableData.value.splice(index, 1); // 删除该行数据
        }
        errorMessage.value = null; // 清空错误消息
  
        ElMessage({
          message: cleanedData.msg || '删除成功',
          type: 'success'
        });
      } else {
        // 删除失败，显示错误消息
        errorMessage.value = cleanedData.msg || '删除失败';
  
        // 删除失败，显示提示信息
        ElMessage({
          message: `删除失败: ${cleanedData.msg}`,
          type: 'error'
        });
      }
    } catch (error) {
      console.error('Failed to delete data:', error);
      errorMessage.value = '删除失败，请稍后重试';
  
      // 删除失败，显示提示信息
      ElMessage({
        message: '删除失败，请稍后重试',
        type: 'error'
      });
    }
  };
  
  // 搜索操作
  const handleSearch = () => {
    if (searchQuery.value.trim()) {
      filteredData.value = tableData.value.filter(item => 
        item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        item.address.toLowerCase().includes(searchQuery.value.toLowerCase())
      );
    } else {
      filteredData.value = tableData.value; // 搜索框为空时，显示所有数据
    }
    currentPage.value = 1; // 搜索时重置为第一页
    updatePageData(); // 更新当前页数据
  };
  
  // 重置操作
  const handleReset = () => {
    searchQuery.value = ''; // 清空搜索框
    filteredData.value = tableData.value; // 显示所有数据
    currentPage.value = 1; // 重置为第一页
    updatePageData(); // 更新当前页数据
  };
  
  // 添加数据操作
  const handleAdd = () => {
    addDialogVisible.value = true; // 显示添加数据的弹窗
  };
  
  // 提交添加的数据
  const handleSubmit = async () => {
    try {
      const token = sessionStorage.getItem('token');
      if (!token) {
        errorMessage.value = 'Token 不存在，访问被拒绝';
        return;
      }
  
      const response = await axios.post('http://localhost:8000/api/data/insert', newData.value, {
        headers: {
          Authorization: `${token}` 
        }
      });
  
      if (response.data.code === 1) {
        ElMessage({
          message: response.data.msg || '添加成功',
          type: 'success'
        });
        dialogVisible.value = false; // 关闭 Dialog
        fetchData(); // 刷新数据
        resetForm(); // 重置表单
      } else {
        ElMessage({
          message: response.data.msg || '添加失败',
          type: 'error'
        });
      }
    } catch (error) {
      console.error('Failed to add data:', error);
      ElMessage({
        message: '添加失败，请稍后重试',
        type: 'error'
      });
    }
  };
  
  const handleEdit = (row: any) => {
    // 将当前行数据赋值给 currentRow 以便在对话框中显示
    newData.value = { ...row }; // 将选中的行数据赋值给 newData
    currentRow.value = { ...row };
    editDialogVisible.value = true; // 打开编辑对话框
  }
  
  // 保存编辑的数据
  const handleSave = async () => {
    try {
      const token = sessionStorage.getItem('token'); // 从 sessionStorage 获取 token
      if (!token) {
        errorMessage.value = 'Token 不存在，访问被拒绝';
        return;
      }
  
      // 将编辑的数据提交给后端
      const response = await axios.post(`http://localhost:8000/api/data/update/${currentRow.value.id}`, currentRow.value, {
        headers: {
          Authorization: `${token}` // 发送 token 作为认证
        }
      });
  
      if (response.data.code === 1) {
        ElMessage({ message: '数据更新成功', type: 'success' });
        fetchData(); // 刷新数据
        dialogVisible.value = false; // 关闭对话框
      } else {
        ElMessage({ message: response.data.msg, type: 'error' });
      }
    } catch (error) {
      console.error('Failed to update data:', error);
      errorMessage.value = '数据更新失败，请稍后重试';
    }
  }
  
  // 重置表单
  const resetForm = () => {
    newData.value = { data: '', name: '', address: '' }; // 重置表单内容
  };
  
  // 组件加载时请求数据
  onMounted(() => {
    fetchData(); // 初始化加载数据
  });
  </script>
  
  <style scoped>
  /* 自定义样式 */
  </style>
  