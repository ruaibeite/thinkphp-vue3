<template>
  <div class="container">
    <!-- 左侧导航栏 -->
    <el-aside width="250px" class="sidebar">
      <div class="sidebar-header">
        <h2 class="title">My App</h2>
      </div>
      <el-menu
        default-active="/"
        class="menu"
        :router="true"
        background-color="#2c3e50"
        text-color="#fff"
        active-text-color="#409EFF"
      >
        <!-- 菜单项 -->
        <el-menu-item index="/" icon="el-icon-house">Home</el-menu-item>
        <el-menu-item index="/profile" icon="el-icon-user">Profile</el-menu-item>
        <el-menu-item index="/settings" icon="el-icon-setting">Settings</el-menu-item>
        <el-menu-item @click="logout" icon="el-icon-lock">Logout</el-menu-item>
      </el-menu>
    </el-aside>

    <!-- 右侧内容区域 -->
    <el-main class="main-content">
      <!-- 通过 router-view 显示右侧区域的内容 -->
      <router-view />
    </el-main>

    <!-- 右上角的退出按钮 -->
    <button @click="logout" class="logout-button">
      Logout
    </button>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { ElMenu, ElMenuItem, ElAside, ElMain } from 'element-plus'

const router = useRouter()

// 退出登录方法
const logout = () => {
  // 清除 sessionStorage 中的 token
  window.sessionStorage.removeItem('token')

  // 跳转到登录页
  router.push('/login')
}
</script>

<style scoped>
/* 根容器填满整个屏幕 */
.container {
  display: flex;
  height: 100vh;
  position: relative;
}

/* 左侧导航栏样式 */
.sidebar {
  background-color: #2c3e50;
  color: white;
  width: 250px;
  height: 100%;
}

/* 右侧内容区域样式 */
.main-content {
  flex-grow: 1;
  padding: 20px;
  background-color: #ecf0f1;
  overflow-y: auto; /* 允许内容区域滚动 */
}

/* 右上角的退出按钮样式 */
.logout-button {
  position: absolute;
  top: 20px;
  right: 20px;
  padding: 10px 20px;
  background-color: #f44336;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 4px;
  font-size: 16px;
}

.logout-button:hover {
  background-color: #d32f2f;
}
</style>
