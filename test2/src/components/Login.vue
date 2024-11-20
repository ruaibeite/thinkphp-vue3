<template>
  <form @submit.prevent="fetchData" style="max-width: 600px; margin: 0 auto;">
    <div style="margin-bottom: 20px;">
      <label for="name">Activity Name:</label>
      <input type="text" v-model="form.name" id="name" required style="width: 100%; padding: 8px; margin-top: 5px;" />
    </div>
    <div style="margin-bottom: 20px;">
      <label for="password">Password:</label>
      <input type="password" v-model="form.password" id="password" required style="width: 100%; padding: 8px; margin-top: 5px;" />
    </div>
    <div>
      <button type="submit" style="padding: 10px 20px; background-color: #409EFF; color: white; border: none;">Login</button>
      <button type="button" style="padding: 10px 20px; margin-left: 10px; background-color: #f44336; color: white; border: none;" @click="resetForm">Cancel</button>
    </div>
  </form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { ElMessage } from 'element-plus'
import { useRouter } from 'vue-router'

// 定义表单数据
const router = useRouter()
const form = ref({
  name: '',
  password: ''  // 添加 password 字段
})

// 登录请求
const fetchData = async () => {
  try {
    // 发送 POST 请求
    const response = await axios.post('http://localhost:8000/api/login/login', {
      name: form.value.name,
      password: form.value.password
    })

    console.log(response.data)

    if (response.data.token) {
      // 设置 token
      window.sessionStorage.setItem('token', response.data.token)

      // 登录成功后跳转到首页
      router.push('/')  // 直接跳转
    } else {
      console.error("Token not found!")
      ElMessage.error("登录失败，未返回有效的token。")
    }
  } catch (error) {
    console.error(error)
    ElMessage.error('请求失败，请稍后再试')
  }
}

// 重置表单方法
const resetForm = () => {
  form.value.name = ''
  form.value.password = ''  // 重置 password 字段
}

// 页面加载时检查是否有token，如果没有则跳转到 home 页面
if (!window.sessionStorage.getItem('token')) {
  router.push('/login')  // 没有token时跳转到 home 页面
}
</script>

<style scoped>
/* 你可以根据需要添加自定义样式 */
</style>
